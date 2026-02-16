<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TicketComment;
use App\Models\TicketLog;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Show dealer's tickets list
     */
    public function dealerIndex(Request $request)
    {
        $user = auth()->user();
        $query = Ticket::where('created_by', $user->id)
                      ->with(['category', 'subCategory', 'assignedHelpdesk']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('dealer.tickets.index', compact('tickets'));
    }

    /**
     * Show create ticket form
     */
    public function create()
    {
        $categories = Category::active()->ordered()->get();
        return view('dealer.tickets.create', compact('categories'));
    }

    /**
     * Store new ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'subject' => 'required|string|max:200',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:5120', // Max 5MB
        ]);

        $user = auth()->user();
        $subCategory = SubCategory::findOrFail($request->sub_category_id);

        // Generate ticket number
        $date = now()->format('Ymd');
        $lastTicket = Ticket::whereDate('created_at', today())->latest()->first();
        $sequence = $lastTicket ? (intval(substr($lastTicket->ticket_number, -4)) + 1) : 1;
        $ticketNumber = "TKT-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Calculate SLA deadline
        $slaDeadline = now()->addMinutes($subCategory->sla_minutes);

        // Create ticket
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'dealer_branch_id' => $user->dealer_branch_id,
            'created_by' => $user->id,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $subCategory->priority,
            'sla_minutes' => $subCategory->sla_minutes,
            'sla_deadline' => $slaDeadline,
            'assigned_to' => $subCategory->default_specialist_id,
            'status' => 'assigned', // Auto-assign
            'assigned_at' => now(),
        ]);

        // Handle file upload if exists
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('ticket_attachments', $fileName);

            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $user->id,
            ]);
        }

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => null,
            'action' => 'created',
            'description' => 'Ticket created by ' . $user->name,
        ]);

        // Create log for auto-assignment
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => null,
            'action' => 'assigned',
            'new_value' => $ticket->assignedHelpdesk->name ?? 'N/A',
            'description' => 'Auto-assigned to ' . ($ticket->assignedHelpdesk->name ?? 'Helpdesk'),
        ]);

        return redirect()->route('dealer.tickets.show', $ticket->id)
                        ->with('success', 'Ticket created successfully! Ticket Number: ' . $ticketNumber);
    }

    /**
     * Show ticket detail
     */
    public function show($id)
    {
        $user = auth()->user();
        $ticket = Ticket::with([
            'dealerBranch',
            'creator',
            'category',
            'subCategory',
            'assignedHelpdesk',
            'attachments.uploader',
            'comments.user',
            'logs.user'
        ])->findOrFail($id);

        // Check if user is authorized to view this ticket
        if ($user->isDealer() && $ticket->created_by != $user->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        // Get only public comments for dealer
        if ($user->isDealer()) {
            $ticket->load(['comments' => function($query) {
                $query->where('is_internal', false);
            }]);
        }

        return view('dealer.tickets.show', compact('ticket'));
    }

    /**
     * Add comment to ticket (dealer)
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $user = auth()->user();
        $ticket = Ticket::findOrFail($id);

        // Verify ticket belongs to current dealer
        if ($user->isDealer() && $ticket->created_by != $user->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'is_internal' => false, // Dealer comments are always public
        ]);

        // If ticket was pending, change to in_progress
        if ($ticket->status == 'pending') {
            $ticket->update(['status' => 'in_progress']);

            TicketLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'action' => 'status_changed',
                'old_value' => 'pending',
                'new_value' => 'in_progress',
                'description' => 'Dealer responded. Ticket moved back to in_progress.',
            ]);
        }

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'commented',
            'description' => $user->name . ' added a comment',
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Reopen resolved ticket
     */
    public function reopen(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $user = auth()->user();
        $ticket = Ticket::findOrFail($id);

        // Verify ticket belongs to current dealer
        if ($user->isDealer() && $ticket->created_by != $user->id) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        if ($ticket->status != 'resolved') {
            return back()->withErrors(['error' => 'Only resolved tickets can be reopened.']);
        }

        $ticket->update([
            'status' => 'reopened',
            'resolved_at' => null,
            'resolution_note' => null,
            'sla_met' => null,
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'reopened',
            'description' => $user->name . ' reopened this ticket. Reason: ' . $request->reason,
        ]);

        // Add comment
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'comment' => "**TICKET REOPENED**\n\nReason: " . $request->reason,
            'is_internal' => false,
        ]);

        return redirect()->back()->with('success', 'Ticket reopened successfully!');
    }

    /**
     * Get sub-categories by category (AJAX)
     */
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
                                   ->where('is_active', true)
                                   ->with('defaultSpecialist')
                                   ->get();

        return response()->json($subCategories);
    }
}