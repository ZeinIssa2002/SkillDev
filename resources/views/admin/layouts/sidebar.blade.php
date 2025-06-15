<!-- Admin Sidebar -->
<div class="sidebar">
    <div class="sidebar-header p-3">
        <h4>Admin Panel</h4>
        <p class="mb-0 text-primary">{{ Auth::user()->username }}</p>
    </div>
    <ul class="nav flex-column p-2">
        <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.courses') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.courses') }}">
                <i class="fas fa-book me-2"></i> Courses Management
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.instructors') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.instructors') }}">
                <i class="fas fa-chalkboard-teacher me-2"></i> Instructors
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.users') }}">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('categories.index') }}">
                <i class="fas fa-list me-2"></i> Categories
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.reports') }}">
                <i class="fas fa-flag me-2"></i> Course Reports
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.chats.index') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.chats.index') }}">
                <i class="fas fa-comments me-2"></i> Chat System <span id="sidebarChatNotification" class="notification-dot" style="display: none;"></span>
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}">
            <a class="nav-link text-white" href="{{ route('admin.feedbacks') }}">
                <i class="fas fa-comment me-2"></i> Feedbacks
            </a>
        </li>
        <li class="nav-item {{ request()->routeIs('admin.terms') ? 'active' : '' }}">
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
