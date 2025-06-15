@extends('admin.layouts.app')

@section('title', 'Categories Management')

@section('styles')
<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .empty-state {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .empty-state i {
        opacity: 0.6;
    }
    .empty-state h3 {
        margin-bottom: 15px;
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Categories Management</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Add New Category
    </a>
</div>


<div class="card shadow-sm">
    <div class="card-body">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                        data-bs-target="#deleteCategoryModal" 
                                        data-category-id="{{ $category->id }}"
                                        data-category-name="{{ $category->name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-list-alt fa-4x text-muted mb-4"></i>
                    <h3>No Categories Found</h3>
                    <p class="text-muted">You haven't created any categories yet. Get started by creating a new one.</p>
                    <a href="{{ route('categories.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i> Create First Category
                    </a>
                </div>
            </div>
        @endif
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
                <h4 class="text-center" id="categoryName"></h4>
                <p class="text-muted text-center mt-3">This action cannot be undone</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCategoryForm" method="POST">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete modal handler
        const deleteModal = document.getElementById('deleteCategoryModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const categoryId = button.getAttribute('data-category-id');
                const categoryName = button.getAttribute('data-category-name');
                
                this.querySelector('#categoryName').textContent = categoryName;
                this.querySelector('#deleteCategoryForm').action = `/admin/categories/${categoryId}`;
            });
        }
    });
</script>
@endsection