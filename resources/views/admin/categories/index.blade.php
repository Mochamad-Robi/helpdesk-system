@extends('layouts.app')

@section('title', 'Categories Management')
@section('page-title', 'Categories Management')
@section('page-description', 'Manage ticket categories and sub-categories')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Category
        </a>
    </div>
</div>

<div class="row">
    @foreach($categories as $category)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            {{ $category->icon }} {{ $category->category_name }}
                            @if(!$category->is_active)
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </h6>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.categories.subcategories', $category->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-list-ul"></i> Sub-Categories ({{ $category->sub_categories_count }})
                            </a>
                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.categories.toggle', $category->id) }}" 
                                  method="POST" 
                                  class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-{{ $category->is_active ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                    {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">{{ $category->description ?? 'No description' }}</p>
                    <div class="d-flex gap-3">
                        <small>
                            <i class="bi bi-ticket"></i> 
                            <strong>{{ $category->tickets_count }}</strong> tickets
                        </small>
                        <small>
                            <i class="bi bi-list"></i> 
                            <strong>{{ $category->sub_categories_count }}</strong> sub-categories
                        </small>
                        <small>
                            <i class="bi bi-sort-numeric-down"></i> 
                            Order: <strong>{{ $category->order_index }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if($categories->count() == 0)
    <div class="text-center py-5">
        <i class="bi bi-folder-x" style="font-size: 4rem; color: #ddd;"></i>
        <p class="text-muted mt-3">No categories found</p>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create First Category
        </a>
    </div>
@endif
@endsection