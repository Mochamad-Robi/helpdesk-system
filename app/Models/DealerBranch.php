<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'branch_code',
        'address',
        'phone',
        'pic_name',
        'pic_email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Users (dealers) belong to this branch
     */
    public function users()
    {
        return $this->hasMany(User::class, 'dealer_branch_id');
    }

    /**
     * Tickets submitted by this branch
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'dealer_branch_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope to get only active branches
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get branch display name with code
     */
    public function getFullNameAttribute()
    {
        return "{$this->branch_code} - {$this->branch_name}";
    }
}