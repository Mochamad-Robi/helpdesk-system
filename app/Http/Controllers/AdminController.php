<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\DealerBranch;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TicketLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with statistics
     */
    public function dashboard()
    {
        // Overall Statistics
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::whereIn('status', ['new', 'assigned', 'in_progress', 'pending'])->count(),
            'resolved_today' => Ticket::where('status', 'resolved')
                                     ->whereDate('resolved_at', today())
                                     ->count(),
            'sla_breach' => Ticket::where('sla_deadline', '<', now())
                                 ->whereNotIn('status', ['resolved', 'closed'])
                                 ->count(),
        ];

        // Tickets by Priority
        $ticketsByPriority = [
            'high' => Ticket::where('priority', 'high')->whereNotIn('status', ['closed'])->count(),
            'medium' => Ticket::where('priority', 'medium')->whereNotIn('status', ['closed'])->count(),
            'low' => Ticket::where('priority', 'low')->whereNotIn('status', ['closed'])->count(),
        ];

        // Tickets by Category
        $ticketsByCategory = Ticket::select('category_id', DB::raw('count(*) as total'))
                                  ->groupBy('category_id')
                                  ->with('category')
                                  ->get();

        // SLA Compliance Rate
        $totalResolvedTickets = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        $slaMetTickets = Ticket::whereIn('status', ['resolved', 'closed'])
                              ->where('sla_met', true)
                              ->count();
        $slaComplianceRate = $totalResolvedTickets > 0 
            ? round(($slaMetTickets / $totalResolvedTickets) * 100, 1) 
            : 0;

        // Average Response Time (in minutes)
        $avgResponseTime = Ticket::whereNotNull('started_at')
                                ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, started_at)) as avg_minutes')
                                ->first()
                                ->avg_minutes ?? 0;

        // Recent tickets requiring attention
        $urgentTickets = Ticket::with(['dealerBranch', 'category', 'subCategory', 'assignedHelpdesk'])
                              ->where(function($query) {
                                  $query->where('status', 'new')
                                        ->orWhere(function($q) {
                                            $q->where('sla_deadline', '<', now())
                                              ->whereNotIn('status', ['resolved', 'closed']);
                                        });
                              })
                              ->orderBy('priority', 'desc')
                              ->orderBy('created_at', 'asc')
                              ->limit(10)
                              ->get();

        return view('admin.dashboard', compact(
            'stats',
            'ticketsByPriority',
            'ticketsByCategory',
            'slaComplianceRate',
            'avgResponseTime',
            'urgentTickets'
        ));
    }

    /**
     * Show all tickets
     */
    public function tickets(Request $request)
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

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::active()->get();

        return view('admin.tickets.index', compact('tickets', 'categories'));
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

        // Get all helpdesk users for reassignment
        $helpdeskUsers = User::where('role', 'helpdesk')
                            ->where('is_active', true)
                            ->get();

        return view('admin.tickets.show', compact('ticket', 'helpdeskUsers'));
    }

    /**
     * Assign ticket to helpdesk (for new tickets)
     */
    public function assignTicket(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'new_value' => $ticket->assignedHelpdesk->name,
            'description' => 'Ticket assigned to ' . $ticket->assignedHelpdesk->name . ' by ' . auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully!');
    }

    /**
     * Reassign ticket to different helpdesk
     */
    public function reassignTicket(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'reason' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $oldHelpdesk = $ticket->assignedHelpdesk->name ?? 'Unassigned';

        $ticket->update([
            'assigned_to' => $request->assigned_to,
        ]);

        // Create log
        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'reassigned',
            'old_value' => $oldHelpdesk,
            'new_value' => $ticket->assignedHelpdesk->name,
            'description' => "Reassigned from {$oldHelpdesk} to {$ticket->assignedHelpdesk->name}. Reason: {$request->reason}",
        ]);

        return redirect()->back()->with('success', 'Ticket reassigned successfully!');
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::with('dealerBranch')->orderBy('role')->orderBy('name')->get();
        $branches = DealerBranch::active()->get();

        return view('admin.users.index', compact('users', 'branches'));
    }

    /**
     * Show create user form
     */
    public function createUser()
    {
        $branches = DealerBranch::active()->get();
        return view('admin.users.create', compact('branches'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:dealer,admin_it,helpdesk,super_admin',
            'dealer_branch_id' => 'required_if:role,dealer|nullable|exists:dealer_branches,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'dealer_branch_id' => $request->role == 'dealer' ? $request->dealer_branch_id : null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $branches = DealerBranch::active()->get();

        return view('admin.users.edit', compact('user', 'branches'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:dealer,admin_it,helpdesk,super_admin',
            'dealer_branch_id' => 'required_if:role,dealer|nullable|exists:dealer_branches,id',
            'is_active' => 'required|boolean',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->dealer_branch_id = $request->role == 'dealer' ? $request->dealer_branch_id : null;
        $user->is_active = $request->is_active;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting user with tickets
        if ($user->createdTickets()->count() > 0 || $user->assignedTickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete user with existing tickets. Deactivate instead.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Show branches management
     */
    public function branches()
    {
        $branches = DealerBranch::withCount('users', 'tickets')->get();
        return view('admin.branches.index', compact('branches'));
    }

    /**
     * Store new branch
     */
    public function storeBranch(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:100',
            'branch_code' => 'required|string|max:20|unique:dealer_branches,branch_code',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:100',
            'pic_email' => 'nullable|email|max:100',
        ]);

        DealerBranch::create($request->all());

        return redirect()->route('admin.branches.index')->with('success', 'Branch created successfully!');
    }

    /**
     * Update branch
     */
    public function updateBranch(Request $request, $id)
    {
        $branch = DealerBranch::findOrFail($id);

        $request->validate([
            'branch_name' => 'required|string|max:100',
            'branch_code' => 'required|string|max:20|unique:dealer_branches,branch_code,' . $branch->id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'pic_name' => 'nullable|string|max:100',
            'pic_email' => 'nullable|email|max:100',
            'is_active' => 'required|boolean',
        ]);

        $branch->update($request->all());

        return redirect()->route('admin.branches.index')->with('success', 'Branch updated successfully!');
    }

    /**
     * Delete branch
     */
    public function deleteBranch($id)
    {
        $branch = DealerBranch::findOrFail($id);

        // Prevent deleting branch with users or tickets
        if ($branch->users()->count() > 0 || $branch->tickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete branch with existing users or tickets. Deactivate instead.']);
        }

        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', 'Branch deleted successfully!');
    }

    /**
     * Show reports
     */
    public function reports(Request $request)
    {
        // Date range filter
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Tickets report
        $ticketsReport = Ticket::whereBetween('created_at', [$startDate, $endDate])
                              ->select('status', DB::raw('count(*) as total'))
                              ->groupBy('status')
                              ->get();

        // SLA report
        $slaReport = Ticket::whereBetween('created_at', [$startDate, $endDate])
                          ->whereIn('status', ['resolved', 'closed'])
                          ->select('sla_met', DB::raw('count(*) as total'))
                          ->groupBy('sla_met')
                          ->get();

        // Category report
        $categoryReport = Ticket::whereBetween('created_at', [$startDate, $endDate])
                               ->select('category_id', DB::raw('count(*) as total'))
                               ->groupBy('category_id')
                               ->with('category')
                               ->get();

        // Branch report
        $branchReport = Ticket::whereBetween('created_at', [$startDate, $endDate])
                             ->select('dealer_branch_id', DB::raw('count(*) as total'))
                             ->groupBy('dealer_branch_id')
                             ->with('dealerBranch')
                             ->get();

        // Helpdesk performance
        $helpdeskPerformance = Ticket::whereBetween('created_at', [$startDate, $endDate])
                                    ->whereNotNull('assigned_to')
                                    ->select(
                                        'assigned_to',
                                        DB::raw('count(*) as total_tickets'),
                                        DB::raw('SUM(CASE WHEN sla_met = 1 THEN 1 ELSE 0 END) as sla_met_count'),
                                        DB::raw('AVG(actual_minutes_taken) as avg_resolution_time')
                                    )
                                    ->groupBy('assigned_to')
                                    ->with('assignedHelpdesk')
                                    ->get();

        return view('admin.reports.index', compact(
            'ticketsReport',
            'slaReport',
            'categoryReport',
            'branchReport',
            'helpdeskPerformance',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export report (CSV/Excel)
     */
    public function exportReport(Request $request)
    {
        // TODO: Implement export functionality
        // You can use maatwebsite/excel package for this

        return back()->with('info', 'Export functionality will be implemented soon.');
    }
}