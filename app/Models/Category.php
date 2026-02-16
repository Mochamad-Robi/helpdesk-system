<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'description',
        'icon',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    // =====================================================
    // RELATIONSHIPS
    // =====================================================

    /**
     * Category has many sub-categories
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id');
    }

    /**
     * Get active sub-categories only
     */
    public function activeSubCategories()
    {
        return $this->hasMany(SubCategory::class, 'category_id')
                    ->where('is_active', true)
                    ->orderBy('sub_category_name');
    }

    /**
     * Tickets under this category
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'category_id');
    }

    // =====================================================
    // SCOPES
    // =====================================================

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by order_index
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('category_name');
    }
}