<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketLog;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class HelpdeskController extends Controller
{
    /**
     * Show helpdesk dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get statistics for assigned tickets
        $stats = [
            'assigned_to_me' => Ticket::where('assigned_to', $user->id)
                                     ->whereNotIn('status', ['closed'])
                                     ->count(),
            'in_progress' => Ticket::where('assigned_to', $user->id)
                                  ->where('status', 'in_progress')
                                  ->count(),
            'resolved_today' => Ticket::where('assigned_to', $user->id)
                                     ->where('status', 'resolved')
                                     ->whereDate('resolved_at', today())
                                     ->count(),
            'sla_critical' => Ticket::where('assigned_to', $user->id)
                                   ->whereNotIn('status', ['resolved', 'closed'])
                                   ->where('sla_deadline', '<', now()->addMinutes(30))
                                   ->count(),
        ];

        // My urgent tickets
        $urgentTickets = Ticket::where('assigned_to', $user->id)
                              ->whereNotIn('status', ['resolved', 'closed'])
                              ->with(['dealerBranch', 'category', 'subCategory'])
                              ->orderByRaw("CASE 
                                  WHEN priority = 'high' THEN 1 
                                  WHEN priority = 'medium' THEN 2 
                                  ELSE 3 
                              END")
                              ->orderBy('sla_deadline', 'asc')
                              ->limit(10)
                              ->get();

        return view('helpdesk.dashboard', compact('user', 'stats', 'urgentTickets'));
    }

    /**
     * Show my assigned tickets
     */
    public function myTickets(Request $request)
    {
        $user = auth()->user();
        $query = Ticket::where('assigned_to', $user->id)
                      ->with(['dealerBranch', 'category', 'subCategory']);

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

        $tickets = $query->orderBy('priority', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('helpdesk.tickets.index', compact('tickets'));
    }

    /**
     * Show all tickets (not just assigned to me)
     */
    public function allTickets(Request $request)
    {
        $query = Ticket::with(['dealerBranch', 'creator', 'category', 'subCategory', 'assignedHelpdesk']);

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

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('helpdesk.tickets.all', compact('tickets'));
    }

    /**
     * Show ticket detail
     */
    public function ticketDetail($id)
    {
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

        // Check if ticket is assigned to current user or show warning
        $canEdit = ($ticket->assigned_to == auth()->id()) || auth()->user()->isSuperAdmin();

        return view('helpdesk.tickets.show', compact('ticket', 'canEdit'));
    }

    /**
     * Start working on ticket
     */
    public function startWorking($id)
    {
        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        if ($ticket->status != 'assigned') {
            return back()->withErrors(['error' => 'Ticket is not in assigned status.']);
        }

        $ticket->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'old_value' => 'assigned',
            'new_value' => 'in_progress',
            'description' => auth()->user()->name . ' started working on this ticket',
        ]);

        return redirect()->back()->with('success', 'Started working on ticket!');
    }

    /**
     * Add comment to ticket
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'is_internal' => $request->has('is_internal') ? true : false,
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'commented',
            'description' => auth()->user()->name . ' added a comment',
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Resolve ticket
     */
    public function resolve(Request $request, $id)
    {
        $request->validate([
            'resolution_note' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        if (!in_array($ticket->status, ['in_progress', 'pending'])) {
            return back()->withErrors(['error' => 'Ticket must be in progress or pending to resolve.']);
        }

        $ticket->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolution_note' => $request->resolution_note,
        ]);

        // Calculate SLA status
        $ticket->calculateSlaStatus();

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'resolved',
            'description' => auth()->user()->name . ' resolved this ticket',
        ]);

        // Add resolution as comment
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => "**RESOLVED**\n\n" . $request->resolution_note,
            'is_internal' => false,
        ]);

        return redirect()->back()->with('success', 'Ticket resolved successfully!');
    }

    /**
     * Set ticket to pending (waiting for dealer response)
     */
    public function setPending(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        if ($ticket->status != 'in_progress') {
            return back()->withErrors(['error' => 'Ticket must be in progress to set as pending.']);
        }

        $ticket->update([
            'status' => 'pending',
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'status_changed',
            'old_value' => 'in_progress',
            'new_value' => 'pending',
            'description' => auth()->user()->name . ' set ticket to pending - waiting for dealer response',
        ]);

        // Add comment
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => "**PENDING - Waiting for your response**\n\n" . $request->comment,
            'is_internal' => false,
        ]);

        return redirect()->back()->with('success', 'Ticket set to pending. Waiting for dealer response.');
    }

    /**
     * Escalate ticket to admin
     */
    public function escalate(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        // Create log for escalation
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'escalated',
            'description' => auth()->user()->name . ' escalated this ticket to Admin IT. Reason: ' . $request->reason,
        ]);

        // Add internal comment
        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'comment' => "**ESCALATED TO ADMIN IT**\n\nReason: " . $request->reason,
            'is_internal' => true,
        ]);

        return redirect()->back()->with('success', 'Ticket escalated to Admin IT successfully!');
    }

    /**
     * Upload attachment
     */
    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // Max 5MB
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verify ticket is assigned to current user
        if ($ticket->assigned_to != auth()->id() && !auth()->user()->isSuperAdmin()) {
            return back()->withErrors(['error' => 'This ticket is not assigned to you.']);
        }

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('ticket_attachments', $fileName);

        TicketAttachment::create([
            'ticket_id' => $ticket->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'attachment_uploaded',
            'description' => auth()->user()->name . ' uploaded a file: ' . $file->getClientOriginalName(),
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = auth()->user();
        return view('helpdesk.profile', compact('user'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('helpdesk.profile')->with('success', 'Profile updated successfully!');
    }
}