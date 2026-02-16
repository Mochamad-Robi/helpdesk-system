<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_name',
        'priority',
        'sla_minutes',
        'default_specialist_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sla_minutes' => 'integer',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Sub-category belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Default specialist (Helpdesk user)
     */
    public function defaultSpecialist()
    {
        return $this->belongsTo(User::class, 'default_specialist_id');
    }

    /**
     * Tickets under this sub-category
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'sub_category_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope to get only active sub-categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // =====================================================
    // HELPER METHODS
    // =====================================================

    /**
     * Get formatted SLA display
     */
    public function getSlaDisplayAttribute()
    {
        $minutes = $this->sla_minutes;

        if ($minutes < 60) {
            return $minutes . ' menit';
        } elseif ($minutes < 1440) {
            $hours = round($minutes / 60, 1);
            return $hours . ' jam';
        } else {
            $days = round($minutes / 1440, 1);
            return $days . ' hari';
        }
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
     * Get priority label
     */
    public function getPriorityLabelAttribute()
    {
        return match($this->priority) {
            'high' => 'HIGH',
            'medium' => 'MEDIUM',
            'low' => 'LOW',
            default => 'UNKNOWN',
        };
    }

    /**
     * Get full name with category
     */
    public function getFullNameAttribute()
    {
        return "{$this->category->category_name} > {$this->sub_category_name}";
    }
}