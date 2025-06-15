@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .danger-zone {
        border-left: 4px solid #dc3545;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Category</h2>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Back to Categories
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ $category->name }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="3">{{ $category->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</div>

<!-- Danger Zone -->
<div class="card shadow-sm danger-zone">
    <div class="card-header bg-white text-danger">
        <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
    </div>
    <div class="card-body">
        <h5 class="card-title">Delete This Category</h5>
        <p class="card-text">Once you delete a category, there is no going back. Please be certain.</p>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal">
            <i class="fas fa-trash me-1"></i> Delete Category
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                </div>
                <p class="text-center">Are you sure you want to delete the category:</p>
                <h4 class="text-center">{{ $category->name }}</h4>
                <p class="text-muted text-center mt-3">This action cannot be undone</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection