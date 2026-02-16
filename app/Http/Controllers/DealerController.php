<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class DealerController extends Controller
{
    /**
     * Show dealer dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $branch = $user->dealerBranch;

        // Get statistics
        $stats = [
            'total_tickets' => Ticket::where('created_by', $user->id)->count(),
            'open_tickets' => Ticket::where('created_by', $user->id)
                                   ->whereIn('status', ['new', 'assigned', 'in_progress', 'pending'])
                                   ->count(),
            'resolved_tickets' => Ticket::where('created_by', $user->id)
                                       ->where('status', 'resolved')
                                       ->count(),
            'closed_tickets' => Ticket::where('created_by', $user->id)
                                     ->where('status', 'closed')
                                     ->count(),
        ];

        // Get recent tickets
        $recentTickets = Ticket::where('created_by', $user->id)
                              ->with(['category', 'subCategory', 'assignedHelpdesk'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return view('dealer.dashboard', compact('user', 'branch', 'stats', 'recentTickets'));
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = auth()->user();
        $branch = $user->dealerBranch;

        return view('dealer.profile', compact('user', 'branch'));
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

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('dealer.profile')->with('success', 'Profile updated successfully!');
    }
}