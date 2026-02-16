<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'dealer_branch_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * User belongs to a DealerBranch (for role: dealer)
     */
    public function dealerBranch()
    {
        return $this->belongsTo(DealerBranch::class, 'dealer_branch_id');
    }

    /**
     * Tickets created by this user
     */
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by');
    }

    /**
     * Tickets assigned to this user (for role: helpdesk)
     */
    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Sub-categories where this user is default specialist
     */
    public function specialistSubCategories()
    {
        return $this->hasMany(SubCategory::class, 'default_specialist_id');
    }

    /**
     * Comments made by this user
     */
    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class, 'user_id');
    }

    /**
     * Logs created by this user
     */
    public function ticketLogs()
    {
        return $this->hasMany(TicketLog::class, 'user_id');
    }

    /**
     * Attachments uploaded by this user
     */
    public function ticketAttachments()
    {
        return $this->hasMany(TicketAttachment::class, 'uploaded_by');
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Check if user is dealer
     */
    public function isDealer()
    {
        return $this->role === 'dealer';
    }

    /**
     * Check if user is admin IT
     */
    public function isAdminIT()
    {
        return $this->role === 'admin_it';
    }

    /**
     * Check if user is helpdesk
     */
    public function isHelpdesk()
    {
        return $this->role === 'helpdesk';
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is IT staff (admin_it or helpdesk or super_admin)
     */
    public function isITStaff()
    {
        return in_array($this->role, ['admin_it', 'helpdesk', 'super_admin']);
    }

    /**
     * Get user's full role name
     */
    public function getRoleNameAttribute()
    {
        $roles = [
            'dealer' => 'Dealer',
            'admin_it' => 'Admin IT',
            'helpdesk' => 'Helpdesk',
            'super_admin' => 'Super Admin',
        ];

        return $roles[$this->role] ?? 'Unknown';
    }
}