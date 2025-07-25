@extends('admin.layouts.app')

@section('title', 'Admin Panel - Instructors Management')

@section('styles')
<style>
    .badge {
        color: white !important;
    }
    .badge-confirmed {
        background-color: #28a745;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 0.5em 0.8em;
    }
    .badge-inactive {
        background-color: #dc3545;
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 0.5em 0.8em;
    }
    .toggle-btn {
        cursor: pointer;
        transition: all 0.3s;
    }
    .toggle-btn:hover {
        opacity: 0.8;
    }
    .action-btns {
        display: flex;
        gap: 10px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }
    .search-box {
        max-width: 300px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Instructors Management</h2>
    <div class="search-box">
        <input type="text" class="form-control" placeholder="Search instructors..." id="searchInput">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="instructorsTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Profile ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($instructors as $instructor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $instructor->profile_id }}</td>
                <td>{{ $instructor->account->username }}</td>
                <td>{{ $instructor->account->email }}</td>
                <td>
                    @if($instructor->confirmation)
                        <span class="badge badge-confirmed">
                            <i class="fas fa-check-circle"></i>
                            <span>Active</span>
                        </span>
                    @else
                        <span class="badge badge-inactive">
                            <i class="fas fa-times-circle"></i>
                            <span>Inactive</span>
                        </span>
                    @endif
                </td>
                <td>
                    @if($instructor->ratings_count > 0)
                        <div class="d-flex align-items-center">
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($instructor->average_rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i == ceil($instructor->average_rating) && $instructor->average_rating - floor($instructor->average_rating) >= 0.5)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <small class="ms-2 text-muted">({{ number_format($instructor->average_rating, 1) }})</small>
                            <small class="ms-2 text-muted">{{ $instructor->ratings_count }} {{ Str::plural('rating', $instructor->ratings_count) }}</small>
                        </div>
                    @else
                        <span class="text-muted">No ratings yet</span>
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <form action="{{ route('admin.instructors.toggle', $instructor->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $instructor->confirmation ? 'btn-warning' : 'btn-success' }} toggle-btn">
                                @if($instructor->confirmation)
                                    <i class="fas fa-times-circle me-1"></i> Deactivate
                                @else
                                    <i class="fas fa-check-circle me-1"></i> Activate
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('profileshowdisplay', $instructor->profile_id) }}" 
                           class="btn btn-sm btn-primary" 
                           title="View Full Profile">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    // Search functionality
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#instructorsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection