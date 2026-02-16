<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'old_value',
        'new_value',
        'description',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Log belongs to a ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * User who performed this action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope by action type
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get action display name
     */
    public function getActionDisplayAttribute()
    {
        $actions = [
            'created' => 'Ticket Created',
            'assigned' => 'Assigned to Helpdesk',
            'status_changed' => 'Status Changed',
            'commented' => 'Added Comment',
            'reopened' => 'Ticket Reopened',
            'closed' => 'Ticket Closed',
            'resolved' => 'Ticket Resolved',
            'escalated' => 'Escalated',
            'reassigned' => 'Re-assigned',
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }
}