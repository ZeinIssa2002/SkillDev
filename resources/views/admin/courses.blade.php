@extends('admin.layouts.app')

@section('title', 'Admin Panel - Courses Management')

@section('styles')
<style>
    .course-card {
        transition: transform 0.3s;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        height: 100%;
    }
    .course-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px rgba(0,0,0,0.1);
    }
    .course-img {
        height: 180px;
        object-fit: cover;
        width: 100%;
    }
    .card-footer {
        background-color: rgba(0,0,0,0.03);
        border-top: 1px solid rgba(0,0,0,0.125);
    }
    .search-box {
        max-width: 300px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Courses Management</h2>
    <div class="search-box">
        <input type="text" class="form-control" id="searchInput" placeholder="Search courses...">
    </div>
</div>

@if($courses->count() > 0)
<div class="row g-4" id="coursesContainer">
    @foreach($courses as $course)
    <div class="col-md-4 col-sm-6 course-item">
        <div class="course-card card">
            <a href="{{ route('courses.show', $course->id) }}" class="text-decoration-none">
                <img src="{{ asset($course->photo ? 'storage/' . $course->photo : 'images/noimage.jpg') }}" alt="Course Image" class="course-img card-img-top" alt="{{ $course->title }}">
                <div class="card-body">
                    <h5 class="card-title text-dark">{{ $course->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($course->coursepreview, 100) }}</p>
                    
                    @if($course->ratings_count > 0)
                        <div class="mt-2 d-flex align-items-center">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($course->average_rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i == ceil($course->average_rating) && $course->average_rating - floor($course->average_rating) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="ms-2 text-muted">({{ number_format($course->average_rating, 1) }})</small>
                            <small class="ms-2 text-muted">{{ $course->ratings_count }} {{ Str::plural('rating', $course->ratings_count) }}</small>
                        </div>
                    @else
                        <div class="mt-2">
                            <small class="text-muted">No ratings yet</small>
                        </div>
                    @endif
                </div>
            </a>
            <div class="card-footer">
                <button class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" 
                        data-bs-target="#deleteModal" 
                        data-course-id="{{ $course->id }}"
                        data-course-title="{{ $course->title }}">
                    <i class="fas fa-trash me-1"></i> Delete Course
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
    <div class="alert alert-info text-center py-4">
        <i class="fas fa-info-circle fa-2x mb-3"></i>
        <h4>No Courses Available</h4>
        <p class="mb-0">You can add new courses through the admin panel</p>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                </div>
                <p class="text-center">Are you sure you want to delete the following course?</p>
                <h4 class="text-center" id="courseTitle"></h4>
                <p class="text-muted text-center mt-3">This action cannot be undone</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
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
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var courseId = button.getAttribute('data-course-id');
            var courseTitle = button.getAttribute('data-course-title');
            
            var modal = this;
            modal.querySelector('#courseTitle').textContent = courseTitle;
            modal.querySelector('#deleteForm').action = '/admin/courses/' + courseId;
        });
    });

    // Search functionality for courses
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".course-item").each(function() {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });
        });
    });
</script>
@endsection