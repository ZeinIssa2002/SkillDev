<!-- Navbar Styles and Scripts -->
<link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
<link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --secondary-color: #f59e0b;
        --secondary-hover: #d97706;
        --dark-color: #1e293b;
        --light-color: #f8fafc;
        --gray-color: #94a3b8;
        --gray-light: #e2e8f0;
        --danger-color: #ef4444;
        --danger-hover: #dc2626;
        --success-color: #10b981;
        --success-hover: #059669;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --transition: all 0.2s ease-in-out;
    }
    .header-content {
        background-color: white;
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    .navbar-homepage-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }
    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .user-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--gray-light);
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        color: var(--dark-color);
        font-size: 0.875rem;
        transition: var(--transition);
        border: 1px solid transparent;
    }
    .user-display:hover {
        background-color: white;
        border-color: var(--gray-color);
        box-shadow: var(--shadow-sm);
    }
    .verified-icon {
        color: var(--primary-color);
        font-size: 0.9rem;
        transition: var(--transition);
    }
    .user-display:hover .verified-icon {
        transform: scale(1.1);
    }
    .dropdown-menu {
        border: none;
        box-shadow: var(--shadow-lg);
        border-radius: 0.5rem;
        padding: 0.5rem 0;
    }
    .dropdown-item {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        margin: 0 0.25rem;
        transition: var(--transition);
    }
    .dropdown-item:hover {
        background-color: var(--gray-light);
        color: var(--primary-color);
    }
    .dropdown-divider {
        border-color: var(--gray-light);
        margin: 0.5rem 0;
    }
    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transition: var(--transition);
    }
    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .logo img {
        transition: var(--transition);
    }
    .logo:hover img {
        transform: scale(1.05);
    }
    .menu-icon {
        transition: var(--transition);
    }
    .menu-icon:hover {
        color: var(--primary-color);
        transform: scale(1.1);
    }
</style>
<header class="header-content">

    <div class="navbar-homepage-container py-3 d-flex justify-content-between align-items-center">
        <a href="#" class="logo">
            <img src="{{ asset('images/3f31217731844ac3a55de7917adbe348.png') }}" alt="Logo" height="40">
        </a>
        <form action="{{ route('search') }}" method="GET" class="d-flex" style="width: 40%;">
            <input class="form-control me-2" type="search" name="search" placeholder="Search Courses,Users,Instructor,etc..." required>
            <button class="btn btn-outline-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="user-info">
            @auth
                <span class="user-display">
                    <i class="fas fa-user-circle"></i>
                    {{ Auth::user()->username }}
                    @if(Auth::user()->instructor_id)
                        @php
                            $instructor = \App\Models\Instructor::find(Auth::user()->instructor_id);
                        @endphp
                        @if($instructor && $instructor->confirmation)
                            <i class="fas fa-check-circle verified-icon" title="Verified Instructor"></i>
                        @endif
                    @endif
                </span>
            @endauth
        </div>
        <div class="dropdown">
            <button class="btn btn-link text-dark menu-icon" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v fa-lg"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="{{ url('/homepage') }}"><i class="fas fa-home me-2"></i>Homepage</a></li>
                @if (auth()->check() && auth()->user()->account_type != 'admin')
                <li><a class="dropdown-item" href="{{ url('/profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="{{ url('/friends') }}"><i class="fas fa-user-friends me-2"></i>Friends</a></li>
                <li><a class="dropdown-item" href="{{ url('/friend-requests') }}"><i class="fas fa-user-plus me-2"></i>Friend Requests</a></li>
                @endif
                @if (auth()->check() && auth()->user()->account_type == 'admin')
                <li><a class="dropdown-item" href="{{ url('/admin/dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                @endif
                <li><a class="dropdown-item" href="{{ url('/course') }}"><i class="fas fa-book me-2"></i>Courses</a></li>
                <li><hr class="dropdown-divider"></li>
            
                @if (auth()->check() && auth()->user()->account_type == 'guest')
                <li><a class="dropdown-item text-primary" href="{{ url('/register') }}"><i class="fas fa-user-edit me-2"></i>Register</a></li>
                <li><a class="dropdown-item text-success" href="{{ url('/login') }}"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                @else
                <li><a class="dropdown-item text-danger" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            @endif
            </ul>
        </div>
    </div>
</header>
