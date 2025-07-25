<!-- Profile Sidebar Component -->
<div class="sidebar">
    <ul class="menu">
        <li class="{{ request()->is('profile') ? 'active' : '' }}">
            <a href="{{ url('/profile') }}">
                <i class="fas fa-user"></i>
                My Profile
            </a>
        </li>
        @if (auth()->check() && auth()->user()->account_type !== 'instructor')
            <li class="{{ request()->is('favorite-courses') ? 'active' : '' }}">
                <a href="{{ route('favorite.courses') }}">
                    <i class="fas fa-heart"></i>
                    Favorite Courses
                </a>
            </li>
            <li class="{{ request()->is('applied-courses') ? 'active' : '' }}">
                <a href="{{ route('applied.courses') }}">
                    <i class="fas fa-check"></i>
                    Applied Courses
                </a>
            </li>
            <li class="{{ request()->is('in-progress-courses') ? 'active' : '' }}">
                <a href="{{ route('in-progress-courses') }}">
                    <i class="fas fa-spinner"></i>
                    In Progress Courses
                </a>
            </li>
            <li class="{{ request()->is('completed-courses') ? 'active' : '' }}">
                <a href="{{ route('completed-courses') }}">
                    <i class="fas fa-check-circle"></i>
                    Completed Courses
                </a>
            </li>
        @endif
        @if (auth()->check() && auth()->user()->account_type == 'instructor')
            <li class="{{ request()->is('profile/feedback*') ? 'active' : '' }}">
                <a href="{{ route('instructor.feedback') }}">
                    <i class="fas fa-comments"></i>
                    Course Feedback
                </a>
            </li>
            <li class="{{ request()->is('instructor/work-hours*') ? 'active' : '' }}">
                <a href="{{ route('instructor.workhours') }}">
                    <i class="fas fa-clock"></i>
                    Work Hours
                </a>
            </li>
        @endif
        <li class="{{ request()->is('profile/edit*') ? 'active' : '' }}">
            <a href="{{ route('profile.edit') }}">
                <i class="fas fa-cog"></i>
                Profile Settings
            </a>
        </li>
    </ul>
</div>
