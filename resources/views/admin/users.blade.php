@extends('admin.layouts.app')

@section('title', 'Admin Panel - Users Management')

@section('styles')
<style>
    .action-btns {
        display: flex;
        gap: 10px;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }
    .alert-dismissible {
        border-radius: 8px;
    }
    .search-box {
        max-width: 300px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Users Management</h2>
    <div class="search-box">
        <input type="text" class="form-control" placeholder="Search users..." id="searchInput">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="usersTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Profile ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->profile_id }}</td>
                <td>{{ $user->account->username }}</td>
                <td>{{ $user->account->email }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('profileshowdisplay', $user->profile_id) }}" 
                           class="btn btn-sm btn-primary" 
                           title="View Full Profile">
                            <i class="fas fa-eye"></i> View
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
            $("#usersTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endsection