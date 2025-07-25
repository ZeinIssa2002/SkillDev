<!-- Navbar Styles and Scripts -->
<link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
<link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
<style>
    :root {
        --primary-color: #4361ee;
        --primary-hover: #3a56d4;
        --secondary-color: #6c757d;
        --dark-color: #2b2d42;
        --light-color: #f8f9fa;
        --gray-light: #f1f3f5;
        --gray-medium: #e9ecef;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.08);
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 8px;
        --nav-height: 70px;
    }

    /* Base Header Styles */
    body {
        padding-top: var(--nav-height); /* Add padding to body to prevent content from being hidden behind fixed navbar */
        margin: 0;
    }
    
    .header-content {
        background-color: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: var(--shadow-md);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        height: var(--nav-height);
        display: flex;
        align-items: center;
        border-bottom: 1px solid var(--gray-medium);
    }

    /* Container Layout */
    .navbar-homepage-container {
        width: 100%;
        margin: 0;
        padding: 0 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Logo Styles */
    .logo {
        display: flex;
        align-items: center;
        text-decoration: none;
        margin-right: 2rem;
    }
    
    .logo img {
        height: 36px;
        width: auto;
        transition: var(--transition);
    }
    
    .logo:hover img {
        transform: translateY(-1px);
    }

    /* Navigation Links */
    .nav-links {
        display: flex;
        gap: 2rem;
        align-items: center;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    .nav-item {
        position: relative;
    }
    
    .nav-link {
        color: var(--dark-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.75rem 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
        border-radius: var(--border-radius);
    }
    
    .nav-link i {
        font-size: 1.1em;
        width: 20px;
        text-align: center;
    }
    
    .nav-link:hover {
        color: var(--primary-color);
    }
    
    .nav-link:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 3px;
        transition: var(--transition);
    }
    
    .nav-link:hover:after {
        width: 80%;
    }

    /* User Info Section */
    .user-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .user-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--dark-color);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        padding: 0.5rem;
        border-radius: 50px;
    }
    
    .user-display:hover {
        background-color: var(--gray-light);
        color: var(--primary-color);
    }
    
    .avatar-container {
        position: relative;
        display: inline-block;
        margin-right: 10px;
    }
    
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--gray-medium);
        transition: var(--transition);
        display: block;
    }
    
    .verified-icon {
        position: absolute;
        bottom: -2px;
        right: -2px;
        background: white;
        border-radius: 50%;
        color: #4361ee;
        font-size: 14px;
        width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }
    
    .user-display:hover .user-avatar {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }
    
    .verified-icon {
        color: #28a745;
        font-size: 0.9rem;
        margin-left: -4px;
        transition: var(--transition);
    }

    /* Search Bar */
    .search-container {
        position: relative;
        margin: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        height: 40px;
        transition: all 0.3s ease;
        flex: 0 0 auto;
    }
    
    .search-button {
        background: none;
        border: none;
        color: var(--dark-color);
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        flex-shrink: 0;
    }
    
    .search-button:hover {
        background-color: var(--gray-light);
        color: var(--primary-color);
    }
    
    .search-form {
        display: flex;
        align-items: center;
        width: 300px; /* Fixed width for better control */
        max-width: 0;
        overflow: hidden;
        transition: max-width 0.3s ease, opacity 0.3s ease, visibility 0.3s ease;
        opacity: 0;
        visibility: hidden;
        position: absolute;
        right: 40px; /* Position next to the search button */
        top: 50%;
        transform: translateY(-50%);
        background: white;
        border-radius: 50px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        z-index: 1001;
        height: 40px;
    }
    
    .search-form.active {
        max-width: 300px;
        opacity: 1;
        visibility: visible;
        padding: 0 15px;
    }
    
    .search-input-container {
        position: relative;
        width: 100%;
    }
    
    .search-input {
        width: 100%;
        padding: 0.5rem 1rem;
        padding-right: 2.5rem;
        border: none;
        border-radius: 50px;
        font-size: 0.9rem;
        transition: var(--transition);
        background-color: transparent;
        height: 40px;
    }
    
    .search-input:focus {
        background-color: white;
        border: 1px solid var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        outline: none;
    }
    
    .search-submit {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        color: var(--dark-color);
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
    }
    
    .search-submit:hover {
        opacity: 1;
        color: var(--primary-color);
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .navbar-homepage-container {
            padding: 0 1.5rem;
        }
        
        .nav-links {
            gap: 1.25rem;
        }
    }
    
    @media (max-width: 992px) {
        .nav-links {
            display: none; /* Will be toggled by mobile menu */
        }
        
        .mobile-menu-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            margin-left: 1rem;
        }
    }
    
    /* Auth Buttons */
    .auth-buttons {
        display: flex;
        gap: 0.75rem;
        margin-left: 1rem;
    }
    
    .btn {
        padding: 0.5rem 1.25rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.9rem;
        transition: var(--transition);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: var(--primary-color);
        color: white;
        border: 2px solid var(--primary-color);
    }
    
    .btn-primary:hover {
        background: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
    }
    
    .btn-outline {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }
    
    .btn-outline:hover {
        background: rgba(67, 97, 238, 0.05);
        transform: translateY(-1px);
    }
</style>

<header class="header-content">
    <div class="navbar-homepage-container">
        <!-- Logo -->
        <a href="{{ route('homepage') }}" class="logo">
            <img src="{{ asset('images/3f31217731844ac3a55de7917adbe348.png') }}" alt="SkillDev Logo" height="36">
            <span class="logo-text" style="margin-left: 0.75rem; font-weight: 700; font-size: 1.25rem; color: var(--dark-color);">SkillDev</span>
        </a>

        <!-- Navigation Links -->
        <nav class="desktop-nav" style="margin-left: 2rem;">
            <ul class="nav-links">
                <li class="nav-item">
                    <a href="{{ url('/homepage') }}" class="nav-link {{ request()->is('homepage') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                
                @if (auth()->check() && auth()->user()->account_type != 'admin')
                <li class="nav-item">
                    <a href="{{ url('/profile') }}" class="nav-link {{ request()->is('profile*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/friends') }}" class="nav-link {{ request()->is('friends*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends"></i>
                        <span>Friends</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/friend-requests') }}" class="nav-link {{ request()->is('friend-requests*') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <span>Friend Requests</span>
                        @php
                            $unreadRequests = auth()->check() ? auth()->user()->receivedFriendRequests()->where('status', 'pending')->count() : 0;
                        @endphp
                        @if($unreadRequests > 0)
                            <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">{{ $unreadRequests }}</span>
                        @endif
                    </a>
                </li>
                @endif
                
                @if (auth()->check() && auth()->user()->account_type == 'admin')
                <li class="nav-item">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif
                
                <li class="nav-item">
                    <a href="{{ url('/course') }}" class="nav-link {{ request()->is('course*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Courses</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right Section -->
        <div class="user-section" style="margin-left: auto;">
            <!-- Search -->
            <div class="search-container">
                <button class="search-button" id="searchToggle" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
                <form action="{{ route('search') }}" method="GET" class="search-form" id="searchForm">
                    <div class="search-input-container">
                        <input class="search-input" type="search" name="search" placeholder="Search for courses, instructors, users..." required>
                        <button class="search-submit" type="submit" aria-label="Submit search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- User Info or Auth Buttons -->
            @if (auth()->check() && auth()->user()->account_type == 'guest')
                <div class="auth-buttons">
                    <a href="{{ url('/register') }}" class="btn btn-outline">
                        <i class="fas fa-user-edit"></i>
                        <span>Register</span>
                    </a>
                    <a href="{{ url('/login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </div>
            @elseif(auth()->check())
                <div class="user-info">
                    @php
                        $user = auth()->user();
                        $isAdmin = $user->account_type === 'admin';
                        $defaultAvatar = asset('images/default-avatar.png');
                        $isInstructor = false;
                        
                        if ($isAdmin) {
                            // For admin, show only text
                            $profileName = 'Admin';
                        } else {
                            // For other users, try to get profile info
                            $profile = $user->profile ?? null;
                            $profileName = $profile->profilename ?? 'User';
                            
                            // Check if profile has a photo
                            if ($profile && $profile->photo) {
                                $profilePictureUrl = asset('storage/' . $profile->photo);
                            } else {
                                $profilePictureUrl = $defaultAvatar;
                            }
                            
                            // Check if user is a verified instructor
                            if ($user->instructor_id) {
                                $instructor = \App\Models\Instructor::find($user->instructor_id);
                                $isInstructor = $instructor && $instructor->confirmation;
                            }
                        }
                    @endphp
                    <a href="{{ url('/profile') }}" class="user-display">
                        @if(!$isAdmin)
                            <div class="avatar-container">
                                <img src="{{ $profilePictureUrl }}" 
                                     alt="{{ $profileName }}" 
                                     class="user-avatar"
                                     onerror="this.onerror=null; this.src='{{ $defaultAvatar }}'"
                                >
                                @if($isInstructor)
                                    <i class="fas fa-check-circle verified-icon" title="Verified Instructor"></i>
                                @endif
                            </div>
                        @endif
                        <span class="d-none d-md-inline">{{ $profileName }}</span>
                        @if(auth()->user()->account_type == 'instructor')
                            @php
                                $instructor = App\Models\Instructor::where('account_id', auth()->user()->account_id)->first();
                            @endphp
                            @if($instructor && $instructor->confirmation)
                                <i class="fas fa-check-circle verified-icon" title="Verified Instructor"></i>
                            @endif
                        @endif
                    </a>
                    <a href="{{ url('/logout') }}" class="btn btn-outline ms-2" style="padding: 0.4rem 0.8rem;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="d-none d-lg-inline">Logout</span>
                    </a>
                </div>
            @else
                <div class="auth-buttons">
                    <a href="{{ url('/register') }}" class="btn btn-outline">
                        <i class="fas fa-user-edit"></i>
                        <span>Register</span>
                    </a>
                    <a href="{{ url('/login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-button d-lg-none" id="mobileMenuToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</header>

<script>
    // Search Toggle Functionality
    const searchToggle = document.getElementById('searchToggle');
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.querySelector('.search-input');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            searchForm.classList.toggle('active');
            if (searchForm.classList.contains('active')) {
                searchInput.focus();
            } else {
                searchInput.blur();
            }
        });
        
        // Close search when clicking outside
        document.addEventListener('click', function(event) {
            const searchContainer = document.querySelector('.search-container');
            if (searchContainer && !searchContainer.contains(event.target)) {
                searchForm.classList.remove('active');
            }
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchForm.classList.remove('active');
                searchInput.blur();
            }
        });
    }
    
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuToggle && navLinks) {
        mobileMenuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            this.classList.toggle('active');
            this.innerHTML = this.classList.contains('active') ? 
                '<i class="fas fa-times"></i>' : 
                '<i class="fas fa-bars"></i>';
        });
    }
    
    // Add active class to current page link
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>