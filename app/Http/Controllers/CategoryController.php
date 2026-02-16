<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::withCount('subCategories', 'tickets')
                             ->ordered()
                             ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order_index' => 'nullable|integer',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required|string|max:100|unique:categories,category_name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order_index' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Check if category has tickets
        if ($category->tickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with existing tickets. Deactivate instead.']);
        }

        // Delete all sub-categories first
        $category->subCategories()->delete();
        
        $category->delete();

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category active status
     */
    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $status = $category->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Category {$status} successfully!");
    }

    /**
     * Show sub-categories for a category
     */
    public function subCategories($id)
    {
        $category = Category::with(['subCategories.defaultSpecialist'])->findOrFail($id);
        $helpdeskUsers = User::where('role', 'helpdesk')
                            ->where('is_active', true)
                            ->get();

        return view('admin.categories.subcategories', compact('category', 'helpdeskUsers'));
    }

    /**
     * Store new sub-category
     */
    public function storeSubCategory(Request $request, $categoryId)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'sla_minutes' => 'required|integer|min:1',
            'default_specialist_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        $category = Category::findOrFail($categoryId);

        SubCategory::create([
            'category_id' => $category->id,
            'sub_category_name' => $request->sub_category_name,
            'priority' => $request->priority,
            'sla_minutes' => $request->sla_minutes,
            'default_specialist_id' => $request->default_specialist_id,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Sub-category created successfully!');
    }

    /**
     * Update sub-category
     */
    public function updateSubCategory(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        $request->validate([
            'sub_category_name' => 'required|string|max:100',
            'priority' => 'required|in:high,medium,low',
            'sla_minutes' => 'required|integer|min:1',
            'default_specialist_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $subCategory->update($request->all());

        return redirect()->back()->with('success', 'Sub-category updated successfully!');
    }

    /**
     * Delete sub-category
     */
    public function destroySubCategory($id)
    {
        $subCategory = SubCategory::findOrFail($id);

        // Check if sub-category has tickets
        if ($subCategory->tickets()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete sub-category with existing tickets. Deactivate instead.']);
        }

        $subCategory->delete();

        return redirect()->back()->with('success', 'Sub-category deleted successfully!');
    }
}