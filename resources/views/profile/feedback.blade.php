<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>

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
            --info-color: #3b82f6;
            --info-hover: #2563eb;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.2s ease-in-out;
        }

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 40px 20px;
        }

        .profile-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .profile-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .profile-header p {
            font-size: 1.1rem;
            color: #666;
        }

        /* Feedback Styles */
        .feedback-container {
            width: 100%;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 2rem;
        }
        
        .feedback-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-light);
        }
        
        .feedback-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .feedback-card {
            border-radius: 8px;
            border: 1px solid var(--gray-light);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
        }
        
        .feedback-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .feedback-card-header {
            padding: 1rem 1.5rem;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .feedback-course {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .feedback-type {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .feedback-type-suggestion {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }
        
        .feedback-type-bug_report {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
        
        .feedback-type-general_comment {
            background-color: rgba(156, 163, 175, 0.1);
            color: var(--gray-color);
        }
        
        .feedback-type-praise {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .feedback-type-other {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--secondary-color);
        }
        
        .feedback-card-body {
            padding: 1.5rem;
        }
        
        .feedback-details {
            margin-bottom: 1rem;
            white-space: pre-line;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        
        .feedback-text {
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .full-text {
            white-space: pre-line;
            line-height: 1.6;
            margin-bottom: 0.5rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }
        
        .show-more-btn {
            background-color: transparent;
            border: none;
            color: var(--primary-color);
            padding: 0;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: var(--transition);
        }
        
        .show-more-btn:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        
        .feedback-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: var(--gray-color);
        }
        
        .feedback-reporter {
            font-weight: 500;
        }
        
        .feedback-date {
            font-size: 0.75rem;
        }
        
        .feedback-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-resolved {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }
        
        .status-in-progress {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }
        
        .status-pending {
            background-color: rgba(156, 163, 175, 0.1);
            color: var(--gray-color);
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray-color);
        }
        
        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--gray-light);
        }
        
        .status-selector {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .status-selector button {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid var(--gray-light);
            background-color: white;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .status-selector button:hover {
            background-color: var(--gray-light);
        }
        
        .status-selector button.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        @media (max-width: 768px) {
            .feedback-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .feedback-actions {
                flex-direction: column;
            }
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #f1c40f;
        }

        .footer-links {
            margin-top: 20px;
        }

        .footer-links a {
            color: white;
            margin: 0 10px;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: #f1c40f;
        }

        footer p {
            margin-top: 20px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    @include('layouts.profile-sidebar-styles')
    @if(session('error'))
        <div class="alert alert-danger" style="
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
        "
        >
            {{ session('error') }}
        </div>
    @endif

    @include('layouts.navbar')

    @include('layouts.profile-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="feedback-container">
            <div class="feedback-header">
                <h1 class="feedback-title">Course Feedback</h1>
                <p>Feedback from students about your courses</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Status selector always visible -->
            <div class="status-selector mb-4">
                <button class="{{ request('status') === null ? 'active' : '' }}" onclick="window.location.href='/profile/feedback'">All</button>
                <button class="{{ request('status') === 'pending' ? 'active' : '' }}" onclick="window.location.href='/profile/feedback?status=pending'">Pending</button>
                <button class="{{ request('status') === 'in_progress' ? 'active' : '' }}" onclick="window.location.href='/profile/feedback?status=in_progress'">In Progress</button>
                <button class="{{ request('status') === 'resolved' ? 'active' : '' }}" onclick="window.location.href='/profile/feedback?status=resolved'">Resolved</button>
            </div>

            @if($feedbacks->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="far fa-comment-dots"></i>
                    </div>
                    <h3>No feedback yet</h3>
                    <p>You haven't received any feedback on your courses yet.</p>
                </div>
            @else
                @foreach($feedbacks as $feedback)
                    <div class="feedback-card">
                        <div class="feedback-card-header">
                            <div>
                                @if($feedback->course)
    <a href="{{ route('course.show', $feedback->course->id) }}" class="feedback-course text-decoration-none">{{ $feedback->course->title }}</a>
@else
    <span class="feedback-course text-muted">[Course Deleted]</span>
@endif
                                <span class="feedback-type feedback-type-{{ $feedback->type }}">{{ str_replace('_', ' ', $feedback->type) }}</span>
                            </div>
                            <div>
                                @if($feedback->resolved)
                                    <span class="status-badge status-resolved">Resolved</span>
                                @elseif($feedback->in_progress)
                                    <span class="status-badge status-in-progress">In Progress</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </div>
                        </div>
                        <div class="feedback-card-body">
                            <div class="feedback-details">
                                <div class="feedback-text {{ strlen($feedback->details) > 150 ? 'truncated' : '' }}" id="feedback-text-{{ $feedback->id }}">
                                    {{ strlen($feedback->details) > 150 ? substr($feedback->details, 0, 150) . '...' : $feedback->details }}
                                </div>
                                @if(strlen($feedback->details) > 150)
                                <div class="full-text" id="full-text-{{ $feedback->id }}" style="display: none;">{{ $feedback->details }}</div>
                                <button class="show-more-btn" id="toggle-btn-{{ $feedback->id }}" onclick="toggleText({{ $feedback->id }})">Show More</button>
                                @endif
                            </div>
                            <div class="feedback-meta">
                                <div>
                                    @if($feedback->reporter)
    <span class="feedback-reporter">{{ $feedback->reporter->username }}</span>
@else
    <span class="feedback-reporter text-muted">[User Deleted]</span>
@endif
                                    <span class="feedback-date">{{ $feedback->created_at->format('M d, Y \a\t h:i A') }}</span>
                                </div>
                            </div>
                            <div class="feedback-actions">
                                <form action="{{ route('feedback.updateStatus', $feedback->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Mark as Pending</button>
                                </form>
                                <form action="{{ route('feedback.updateStatus', $feedback->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Mark as In Progress</button>
                                </form>
                                <form action="{{ route('feedback.updateStatus', $feedback->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" class="btn btn-sm btn-outline-success">Mark as Resolved</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $feedbacks->links() }}
            @endif
        </div>
    </div>



    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/profile/profile.js') }}"></script>
    
    <script>
        function toggleText(feedbackId) {
            const truncatedText = document.getElementById(`feedback-text-${feedbackId}`);
            const fullText = document.getElementById(`full-text-${feedbackId}`);
            const toggleBtn = document.getElementById(`toggle-btn-${feedbackId}`);
            
            if (fullText.style.display === 'none') {
                // Show full text
                truncatedText.style.display = 'none';
                fullText.style.display = 'block';
                toggleBtn.textContent = 'Show Less';
            } else {
                // Show truncated text
                truncatedText.style.display = 'block';
                fullText.style.display = 'none';
                toggleBtn.textContent = 'Show More';
            }
        }
    </script>
</body>

</html>