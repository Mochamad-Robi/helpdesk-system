<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'dealer_branch_id',
        'created_by',
        'category_id',
        'sub_category_id',
        'subject',
        'description',
        'priority',
        'sla_minutes',
        'sla_deadline',
        'assigned_to',
        'status',
        'assigned_at',
        'started_at',
        'resolved_at',
        'closed_at',
        'resolution_note',
        'actual_minutes_taken',
        'sla_met',
    ];

    protected $casts = [
        'sla_deadline' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'sla_minutes' => 'integer',
        'actual_minutes_taken' => 'integer',
        'sla_met' => 'boolean',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Ticket belongs to a dealer branch
     */
    public function dealerBranch()
    {
        return $this->belongsTo(DealerBranch::class, 'dealer_branch_id');
    }

    /**
     * User who created this ticket
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Category of this ticket
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Sub-category of this ticket
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    /**
     * Helpdesk assigned to this ticket
     */
    public function assignedHelpdesk()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Attachments on this ticket
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class, 'ticket_id');
    }

    /**
     * Comments on this ticket
     */
    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id')->orderBy('created_at');
    }

    /**
     * Logs for this ticket
     */
    public function logs()
    {
        return $this->hasMany(TicketLog::class, 'ticket_id')->orderBy('created_at');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for open tickets (not closed)
     */
    public function scopeOpen($query)
    {
        return $query->whereNotIn('status', ['closed']);
    }

    /**
     * Scope for tickets assigned to specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for tickets created by specific user
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope for SLA breach
     */
    public function scopeSlaBreached($query)
    {
        return $query->where('sla_deadline', '<', now())
                     ->whereNotIn('status', ['resolved', 'closed']);
    }

    /**
     * Scope for tickets from specific branch
     */
    public function scopeFromBranch($query, $branchId)
    {
        return $query->where('dealer_branch_id', $branchId);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'new' => 'primary',
            'assigned' => 'info',
            'in_progress' => 'warning',
            'pending' => 'secondary',
            'resolved' => 'success',
            'closed' => 'dark',
            'reopened' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return strtoupper(str_replace('_', ' ', $this->status));
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute()
    {
        return strtoupper($this->priority);
    }

    /**
     * Check if SLA is breached
     */
    public function isSlaBreached()
    {
        if (in_array($this->status, ['resolved', 'closed'])) {
            return $this->sla_met === false;
        }

        return $this->sla_deadline && $this->sla_deadline < now();
    }

    /**
     * Get remaining SLA time in minutes
     */
    public function getRemainingSlaMinutesAttribute()
    {
        if (!$this->sla_deadline) {
            return null;
        }

        return now()->diffInMinutes($this->sla_deadline, false);
    }

    /**
     * Get SLA status display
     */
    public function getSlaStatusAttribute()
    {
        if (in_array($this->status, ['resolved', 'closed'])) {
            return $this->sla_met ? 'Met' : 'Breached';
        }

        $remaining = $this->remaining_sla_minutes;

        if ($remaining === null) {
            return 'N/A';
        }

        if ($remaining < 0) {
            return 'Breached';
        }

        if ($remaining < ($this->sla_minutes * 0.25)) {
            return 'Critical';
        }

        return 'On Track';
    }

    /**
     * Get formatted SLA deadline
     */
    public function getSlaDeadlineFormatted()
    {
        if (!$this->sla_deadline) {
            return 'N/A';
        }

        return $this->sla_deadline->format('d M Y, H:i');
    }

    /**
     * Calculate and update SLA met status
     */
    public function calculateSlaStatus()
    {
        if ($this->resolved_at) {
            $this->actual_minutes_taken = $this->created_at->diffInMinutes($this->resolved_at);
            $this->sla_met = $this->actual_minutes_taken <= $this->sla_minutes;
            $this->save();
        }
    }
}