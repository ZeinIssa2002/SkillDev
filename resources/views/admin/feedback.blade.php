{{-- resources/views/admin/feedback.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Feedbacks</title>
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}">
<!-- Font Awesome all.min.css includes all icon styles and references webfonts. Make sure webfonts directory is present. -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar ul li.active {
            background-color: #4361ee;
        }
        .sidebar ul li:hover:not(.active) {
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #4361ee;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .action-btns .btn {
            margin-right: 5px;
        }
        .logout-btn:hover {
            background-color: rgba(220, 53, 69, 0.8) !important;
        }
        .search-box {
            max-width: 300px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header p-3">
                <h4>Admin Panel</h4>
                <p class="mb-0 text-primary">{{ Auth::user()->username }}</p>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.courses') }}">
                        <i class="fas fa-book me-2"></i> Courses Management
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.instructors') }}">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Instructors
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('categories.index') }}">
                        <i class="fas fa-list me-2"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.reports') }}">
                        <i class="fas fa-flag me-2"></i> Course Reports
                    </a>
                </li>
                <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('admin.chats.index') }}">
                        <i class="fas fa-comments me-2"></i> Chat System
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link text-white" href="{{ route('admin.feedbacks') }}">
                        <i class="fas fa-comment me-2"></i> Feedbacks
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('admin.terms') }}">
                        <i class="fas fa-file-contract me-2"></i> Terms & Conditions
                    </a>
                </li>
                <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('homepage') }}">
    <i class="fas fa-compass me-2"></i> Homepage
    </a>
</li>
                <li class="nav-item mt-auto logout-btn">
                    <a class="nav-link text-white" href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Feedbacks Management</h2>
                <div class="search-box">
                    <form action="{{ route('admin.feedbacks') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search feedbacks..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Feedbacks</h5>
                </div>
                <div class="card-body">
                    @if($feedbacks->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-comment fa-3x text-muted mb-3"></i>
                            <h5>No feedbacks found</h5>
                            <p class="text-muted">There are currently no feedbacks to display.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Feedback</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                        <tr>
                                            <td>{{ $feedback->id }}</td>
                                            <td>{{ $feedback->feedbackinfo }}</td>
                                            <td>{{ $feedback->account->username ?? 'Deleted User' }}</td>
                                            <td class="action-btns">
                                                <form action="{{ route('admin.feedbacks.destroy', $feedback->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this feedback?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $feedbacks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
</body>
</html>