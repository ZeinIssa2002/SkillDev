<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $course->title }} | Course Details</title>
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
/* Placement Test Modal Styles */
.modal-fullscreen {
    padding: 0 !important;
}

.modal-fullscreen .modal-content {
    min-height: 100vh;
    border: 0;
    border-radius: 0;
}

.modal-backdrop.show {
    opacity: 0.8;
}

.placement-test-btn {
    background: linear-gradient(45deg, var(--primary), var(--accent));
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
    text-decoration: none;
    display: inline-block;
    box-shadow: var(--shadow-md);
}

.placement-test-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
}

.placement-test-btn i {
    margin-left: 8px;
}
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background-color: #f5f7ff;
            line-height: 1.6;
        }
        
        /* Header Styles */
        .header {
            background: white;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo img {
            height: 40px;
            transition: var(--transition);
        }
        
        .logo img:hover {
            transform: scale(1.05);
        }
        
        .search-form {
            width: 40%;
            min-width: 250px;
        }
        
        .user-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--light-gray);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            color: var(--dark);
            font-size: 0.875rem;
            transition: var(--transition);
        }
        
        .user-display:hover {
            background-color: white;
            box-shadow: var(--shadow-sm);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0 0.25rem;
            transition: var(--transition);
        }
        
        .dropdown-item:hover {
            background-color: var(--light-gray);
            color: var(--primary);
        }
        
        /* Hero Section */
        .course-hero {
            min-height: 300px;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }
        
        .course-hero::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
            z-index: 0;
        }
        
        .course-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .difficulty-badge .badge {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .difficulty-badge .badge.bg-success {
            background: linear-gradient(45deg, #28a745, #20c997) !important;
        }

        .difficulty-badge .badge.bg-warning {
            background: linear-gradient(45deg, #ffc107, #fd7e14) !important;
            color: #000;
        }

        .difficulty-badge .badge.bg-danger {
            background: linear-gradient(45deg, #dc3545, #c71f37) !important;
        }
        
        .course-preview {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 800px;
            margin: 0 auto 2rem;
        }
        
        /* Main Content Container */
        /* Full width container */
        .course-container {
            width: 100%;
            margin: -4rem 0 0;
            position: relative;
            z-index: 10;
            padding: 0; /* Remove padding as we'll handle it in inner elements */
        }
        
        /* Ensure all direct children of body use full width */
        body > *:not(.modal):not(.modal-backdrop) {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        
        .course-card {
            background: white;
            border-radius: 0;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: var(--transition);
            margin-bottom: 2rem;
            width: 100%;
            max-width: 100%;
            padding: 0 15px; /* Add horizontal padding */
        }
        
        .course-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        /* Meta Section */
        .course-meta-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
            gap: 2rem;
            padding: 2rem 5%;
            background: rgba(255, 255, 255, 0.9);
            margin-bottom: -5rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
            box-sizing: border-box;
        }
        
        /* Ensure content sections use full width */
        .course-content {
            width: 100%;
            max-width: 100%;
            padding: 0 5%;
            box-sizing: border-box;
        }
        
        /* Make sure hero section is full width */
        .course-hero {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
        
        .rating-container {
            min-width: 180px;
            padding: 1rem;
            border-radius: var(--border-radius);
            background: white;
            box-shadow: var(--shadow-sm);
        }
        
        .actions-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-left: auto;
        }
        
        .action-card {
            flex: 0 0 auto;
            padding: 0;
            text-align: center;
        }
        
        .completed-btn {
            background-color: #e8f5e9;
            border-radius: 8px;
            border: 1px solid #c8e6c9;
            transition: all 0.3s ease;
            height: 40px;
        }
        
        .completed-btn:hover {
            background-color: #d4edda;
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin: 2rem 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .action-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
        }
        
        .favorite-btn {
            background: white;
            color: var(--danger);
            border: 1px solid var(--danger);
        }
        
        .favorite-btn:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(247, 37, 133, 0.2);
        }
        
        .favorite-btn.active {
            background: var(--danger);
            color: white;
        }
        
        .apply-btn {
            background: white;
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        .apply-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }
        
        .apply-btn.active {
            background: var(--primary);
            color: white;
        }
        
        /* Course Content */
        .course-content {
            padding: 3rem;
        }
        
        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            display: inline-block;
        }
        
        .section-title::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100px;
            height: 3px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .content-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--dark);
        }
        
        .content-text p {
            margin-bottom: 1.5rem;
        }
        
        /* Media Gallery */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .media-item {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            cursor: pointer;
            position: relative;
        }
        
        .media-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .media-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .media-item:hover img {
            transform: scale(1.05);
        }
        
        .media-item::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.2);
            opacity: 0;
            transition: var(--transition);
        }
        
        .media-item:hover::after {
            opacity: 1;
        }
        
        /* Video Container */
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }
        
        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Levels Section */
        .levels-container {
            padding: 6rem 5% 2rem;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .level-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition);
            border-left: 5px solid var(--gray);
        }
        
        .level-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        
        .level-card.completed {
            border-left: 5px solid var(--success);
        }
        
        .level-card.current {
            border-left: 5px solid var(--primary);
        }
        
        .level-card.locked {
            opacity: 0.7;
            background: var(--light-gray);
        }
        
        .level-header {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }
        
        .level-header:hover {
            background-color: rgba(0,0,0,0.02);
        }
        
        .level-title {
            font-weight: 600;
            font-size: 1.2rem;
            margin: 0;
        }
        
        .level-status {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.9rem;
        }
        
        .success-rate {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.85rem;
        }
        
        .success-rate i {
            font-size: 0.8em;
        }
        
        .level-content {
            padding: 0 1.5rem 1.5rem;
            display: none;
        }
        
        .level-content.show {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Test Container */
        .test-container {
            background: var(--light);
            padding: 2rem;
            border-radius: var(--border-radius);
            margin-top: 2rem;
            box-shadow: var(--shadow-sm);
        }
        
        .question {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }
        
        /* Comments Section */
        .comments-section {
            margin: 4rem 0;
        }
        
        .comment-form {
            background: white;
            padding: 2rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 3rem;
        }
        
        .comment {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            transition: var(--transition);
        }
        
        .comment:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .comment-author {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .comment-text {
            margin-bottom: 1rem;
        }
        
        .comment-actions {
            display: flex;
            gap: 1rem;
        }
        
        .action-link {
            color: var(--gray);
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .action-link:hover {
            color: var(--primary);
        }
        
        .replies {
            margin-left: 3rem;
            margin-top: 1rem;
            border-left: 3px solid var(--light-gray);
            padding-left: 1.5rem;
        }
        
        .reply-box {
            margin-top: 1rem;
            padding: 1rem;
            background: var(--light);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }
        
        /* Locked Content */
        .locked-content {
            text-align: center;
            padding: 4rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
        }
        
        .locked-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 80px; /* زيادة المسافة من الأعلى لتكون أسفل شريط التنقل */
            right: 20px;
            z-index: 1100;
        }
        
        .toast {
            border: none;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 1rem;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.4s ease;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .toast-success {
            background: var(--success);
            color: white;
        }
        
        .toast-error {
            background: var(--danger);
            color: white;
        }
        
        /* Image Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            max-width: 90%;
            max-height: 90%;
            position: relative;
        }
        
        .modal-content img {
            width: 100%;
            height: auto;
            max-height: 80vh;
            object-fit: contain;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 35px;
            cursor: pointer;
            transition: var(--transition);
            z-index: 2001;
        }
        
        .close-modal:hover {
            transform: scale(1.2);
        }
        
        /* Share Button Styles */
        .share-floating-btn {
            position: absolute;
            top: 22px;
            left: 22px;
            z-index: 101;
        }
        
        .action-btn.share-btn {
            background: #f7f7f7;
            color: #007bff;
            border: 1px solid #007bff;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            font-size: 1.15rem;
            padding: 0.65rem 1.05rem;
        }
        
        .action-btn.share-btn:hover {
            background: #007bff;
            color: #fff;
        }
        
        #shareDropdownMenu {
            display: none;
            position: absolute;
            left: 0;
            top: 110%;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            min-width: 180px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.12);
            z-index: 200;
            padding: 0.5rem;
        }
        
        #shareDropdownMenu a.btn {
            text-align: left;
            font-size: 0.97rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .course-title {
                font-size: 2.2rem;
            }
            
            .course-meta-section {
                flex-direction: column;
            }
        }
        
        @media (max-width: 768px) {
            .course-title {
                font-size: 1.8rem;
            }
            
            .replies {
                margin-left: 1.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .share-floating-btn {
                top: 10px;
                left: 8px;
            }
        }
        
        @media (max-width: 576px) {
            .course-hero {
                padding: 3rem 0;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
            
            .course-content {
                padding: 2rem;
            }
        }
        
        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .fa-check-circle {
            animation: pulse 1.5s infinite;
        }
        
        /* Button Hover Effects */
        .btn-outline-danger:hover {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Course Files Section Styles */
        .course-files-section {
            margin-top: 2.5rem;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 12px;
        }
        
        .file-card {
            height: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e9ecef;
        }
        
        .file-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
        }
        
        .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e9f0ff;
            border-radius: 8px;
        }
        
        .file-name {
            font-weight: 500;
            color: #333;
        }
        
        .file-meta {
            margin-top: 0.25rem;
        }
        
        .file-actions .btn {
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }
        
        .fa-file-pdf { color: #e74c3c; }
        .fa-file-word { color: #2c5282; }
        .fa-file-archive { color: #9f7aea; }
        .fa-file { color: #6b7280; }
        
        /* Instructor Link Styles */
        .instructor-info {
            display: inline-block;
        }
        
        .instructor-link {
            display: inline-flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .instructor-link:hover {
            background-color: white;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
        }
        
        #report-button, #feedback-button {
            padding: 8px 0;
            width: 100%;
            border-radius: 8px;
        }
        
        /* Share Toggle Buttons */
        .share-toggle-btn {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .share-toggle-btn.active {
            background-color: #0d6efd !important;
            border-color: #0d6efd !important;
        }
        
        .btn-success.active {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')
    
    <!-- Fixed position notifications container that overlays content -->
    <div id="notificationsOverlay" style="
        position: fixed;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1050;
        pointer-events: none;
    ">
        <div class="container" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
            <!-- Toast container for dynamic notifications -->
            <div id="toastContainer" style="pointer-events: auto;">
                <!-- Dynamic toast notifications will be added here -->
            </div>
            
            <!-- Alert messages -->
            @if(session('success'))
                <div class="alert alert-success" style="
                    padding: 15px;
                    margin-bottom: 10px;
                    border: 1px solid #c3e6cb;
                    border-radius: 8px;
                    color: #155724;
                    background-color: #d4edda;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    text-align: center;
                    width: 100%;
                    pointer-events: auto;
                    animation: fadeInDown 0.3s ease-out;
                ">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" style="
                    padding: 15px;
                    margin-bottom: 10px;
                    border: 1px solid #f5c6cb;
                    border-radius: 8px;
                    color: #721c24;
                    background-color: #f8d7da;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    text-align: center;
                    width: 100%;
                    pointer-events: auto;
                    animation: fadeInDown 0.3s ease-out;
                ">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif
        </div>
    </div>
    
    <style>
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    
    @php
        $isCourseOwner = Auth::check() && Auth::user()->account_type == 'instructor' && Auth::user()->instructor_id == $course->instructor_id;
        $isAdmin = Auth::check() && Auth::user()->account_type == 'admin';
        $isUser = Auth::check() && Auth::user()->account_type == 'user';
        $isGuest = Auth::check() && Auth::user()->account_type == 'guest';
        $prerequisiteCompleted = $prerequisiteCompleted ?? false;
        $placementPassed = $placementPassed ?? false;
        
        // Determine what content to show based on user type
        $showFullContent = $isAdmin || $isCourseOwner;
        $showBasicContent = $isUser || $isGuest;
        $showCurriculum = $isUser && $userProgress && $userProgress->applied;
    @endphp

    <!-- Hero Section with Background Image -->
    <section class="course-hero text-center position-relative" 
             @if($course->photo) style="background: linear-gradient(135deg, rgba(67, 97, 238, 0.9), rgba(63, 55, 201, 0.9));" @endif>
        
        @if($course->photo)
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
            <img src="{{ asset('storage/' . $course->photo) }}" 
                 alt="Course Background" 
                 class="w-100 h-100 object-fit-cover"
                 style="filter: blur(4px); opacity: 0.4;">
        </div>
        @endif
        
        <div class="container position-relative z-index-1 py-5">
            <h1 class="course-title text-white">{{ $course->title }}</h1>
            <div class="difficulty-badge mb-3">
                @php
                    $badgeClass = [
                        'beginner' => 'bg-success',
                        'intermediate' => 'bg-warning',
                        'advanced' => 'bg-danger'
                    ][$course->difficulty_level] ?? 'bg-secondary';
                    
                    $difficultyText = [
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced'
                    ][$course->difficulty_level] ?? 'Not specified';
                @endphp
                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                    <i class="fas {{ $course->difficulty_level === 'beginner' ? 'fa-star-half-alt' : ($course->difficulty_level === 'intermediate' ? 'fa-star' : 'fa-stars') }} me-1"></i>
                    {{ $difficultyText }}
                </span>
            </div>
            <p class="course-preview text-white-80">{{ $course->coursepreview }}</p>
            
            <!-- Instructor Link -->
            <div class="instructor-info mt-3 mb-4">
                <a href="{{ route('profileshowdisplay', $course->instructor->profile_id) }}" class="instructor-link">
                    <i class="fas fa-user-circle me-2"></i>
                    <span>{{ $course->instructor->profile->profilename ?? 'Instructor' }}</span>
                </a>
            </div>
            
            @if ($isUser)
            <div class="action-buttons">
                <button class="action-btn favorite-btn" id="favoriteButton" data-course-id="{{ $course->id }}">
                    <i class="far fa-heart" id="favoriteIcon"></i>
                    <span id="favoriteText">Add to Favorites</span>
                </button>
                
                <button class="action-btn apply-btn" id="applyButton" data-course-id="{{ $course->id }}">
                    <i class="far fa-square" id="applyIcon"></i>
                    <span id="applyText">Apply to Course</span>
                </button>
                
                <div class="share-dropdown share-floating-btn">
                    <button class="action-btn share-btn" id="quickShareBtn" title="Share this course">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                    <div id="shareDropdownMenu">
                        <div class="d-flex flex-column gap-2">
                            <a class="btn btn-success btn-sm w-100 text-start" target="_blank" id="shareWhatsapp"><i class="fab fa-whatsapp me-1"></i> WhatsApp</a>
                            <a class="btn btn-info btn-sm w-100 text-start" target="_blank" id="shareTwitter"><i class="fab fa-twitter me-1"></i> Twitter</a>
                            <a class="btn btn-primary btn-sm w-100 text-start" target="_blank" id="shareFacebook"><i class="fab fa-facebook me-1"></i> Facebook</a>
                            <button type="button" class="btn btn-secondary btn-sm w-100 text-start" id="copyShareTextBtn"><i class="fas fa-copy me-1"></i> <span id="copyShareTextLabel">Copy Text</span></button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Main Content -->
    <div class="container course-container">

        <div class="course-card">
            <!-- Course Meta Section -->
            <div class="course-meta-section">
                <!-- Rating Section -->
                <div class="rating-container" style="width: 180px; padding: 1rem; border-radius: var(--border-radius); background: white; box-shadow: var(--shadow-sm);">
                    @if($isUser && $userProgress)
                        <div class="stars" data-user-rating="0" style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: nowrap; font-size: 1.8rem; line-height: 1; margin: 0 auto;">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="star" data-value="{{ $i }}" style="color: var(--light-gray); cursor: pointer; transition: var(--transition); min-width: 1.8rem; text-align: center;">&#9733;</span>
                            @endfor
                        </div>
                    @elseif($isAdmin || ($isCourseOwner && !$isUser))
                        <div class="text-muted" style="font-size: 0.85rem; text-align: center;">You cannot rate courses with this account.</div>
                    @elseif($isUser && !$userProgress)
                        <div class="text-muted" style="font-size: 0.85rem; text-align: center;">Please apply to the course to rate it.</div>
                    @endif
                    
                    <div class="d-flex flex-column align-items-center mt-2" style="gap: 0.25rem;">
                        <span id="average-rating-value" style="font-size: 0.9rem; font-weight: 600;">Loading...</span>
                    </div>
                </div>

                <!-- Actions Container -->
                <div class="actions-container">
                    @if ($isUser)
                    <div class="d-flex flex-column align-items-end gap-2">
                        <!-- Placement Test Logic -->
                        @if(isset($course->prerequisite_id) && $course->prerequisite_id && !$prerequisiteCompleted && !$placementPassed)
                            <div class="alert alert-warning">
                                You must complete the prerequisite course first: 
                                <a href="{{ route('course.show', $course->prerequisite_id) }}">
                                {{ optional($course->prerequisite)->title ?? 'No prerequisite course' }}
                                </a>
                            </div>
                        @endif
                        
                        @if(isset($placementTest) && $placementTest && $course->prerequisite_id)
                            @if($placementPassed)
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i> You have successfully passed the placement test!
                                </div>
                            @elseif(!$prerequisiteCompleted)
                                <div class="mb-3">
                                    <button class="placement-test-btn" id="startPlacementTest">
                                        <i class="fas fa-clipboard-check"></i> Start Placement Test
                                    </button>
                                </div>
                            @endif

                            <!-- The form will be loaded dynamically in the modal -->
                            <template id="placement-test-form-template">
                                <form id="placement-test-form" method="POST" action="{{ route('course.submitPlacementTest', $course->id) }}" class="p-4">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    @foreach($placementTest as $idx => $question)
                                        <div class="mb-5 question-card p-4 bg-white rounded-3 shadow-sm">
                                            <div class="question-header text-center mb-4">
                                                <span class="question-number bg-primary text-white px-3 py-2 rounded-pill mb-3 d-inline-block">Question {{ $idx+1 }}</span>
                                                <h3 class="fw-bold fs-5 mt-3">{{ $question['text'] }}</h3>
                                            </div>
                                            <div class="options-grid mx-auto" style="max-width: 600px;">
                                                @foreach($question['options'] as $oidx => $option)
                                                    <div class="form-check custom-radio p-3 border rounded mb-2 hover-effect">
                                                        <input class="form-check-input" type="radio" name="answers[{{ $idx }}]" id="placement_q{{ $idx }}_opt{{ $oidx }}" value="{{ $oidx }}" required>
                                                        <label class="form-check-label w-100 cursor-pointer" for="placement_q{{ $idx }}_opt{{ $oidx }}">{{ $option }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-center mt-5">
                                        <button type="submit" class="submit-btn">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Answers
                                        </button>
                                    </div>
                                </form>
                            </template>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <div class="action-card" style="width: 50px;">
                                <button class="btn btn-outline-danger w-100 p-2" id="report-button">
                                    <i class="fas fa-flag"></i>
                                </button>
                            </div>
                            
                            <div class="action-card" style="width: 50px;">
                                <button class="btn btn-outline-primary w-100 p-2" id="feedback-button">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Completion Status -->
                        @if($userProgress && $userProgress->completed)
                        <div class="action-card completed-btn" style="width: 110px;">
                            <div class="d-flex align-items-center justify-content-center p-2 w-100 h-100">
                                <i class="fas fa-check-circle text-success me-2" style="font-size: 1.1rem;"></i>
                                <span class="text-success" style="font-size: 0.8rem; font-weight: 600;">Completed</span>
                            </div>
                        </div>
                        <!-- Share Result Button (Completed) -->
                        <div class="mt-2 d-flex flex-column align-items-center">
                            <button class="btn btn-success btn-sm share-toggle-btn" id="shareResultBtn-completed"
                                data-completed="true"
                                data-course="{{ $course->title }}"
                                data-level="{{ $userProgress->current_level ?? '' }}">
                                <i class="fas fa-share-alt me-1"></i> <span class="share-btn-text">Share Result</span>
                            </button>
                            <div id="shareLinks-completed" class="mt-1 d-flex justify-content-center" style="display:none;"></div>
                        </div>
                        @elseif($userProgress && !$userProgress->completed && $userProgress->current_level)
                        <div class="action-card completed-btn bg-light" style="width: 160px;">
                            <div class="d-flex align-items-center justify-content-center p-2 w-100 h-100">
                                <i class="fas fa-trophy text-warning me-2" style="font-size: 1.1rem;"></i>
                                <span class="text-warning" style="font-size: 0.8rem; font-weight: 600;">Level {{ $userProgress->current_level }}</span>
                            </div>
                        </div>
                        <!-- Share Result Button (Level) -->
                        <div class="mt-2 d-flex flex-column align-items-center">
                            <div class="d-flex align-items-center" style="gap: 0.5rem;">
                                <button class="btn btn-primary btn-sm share-toggle-btn" id="shareResultBtn-progress"
                                    data-completed="false"
                                    data-course="{{ $course->title }}"
                                    data-level="{{ $userProgress->current_level ?? '' }}"
                                    style="order: -1; margin-left: 0; margin-right: 8px;">
                                    <i class="fas fa-share-alt me-1"></i> <span class="share-btn-text">Share Progress</span>
                                </button>
                                <div id="shareLinks-progress" class="mt-1 d-flex justify-content-center" style="display:none;"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Report Form -->
            <div id="report-form-container" class="card mb-4" style="display: none;">
                <div class="card-body">
                    <h4 class="card-title mb-4">Report This Course</h4>
                    <form id="report-form" method="POST" action="{{ route('course.report') }}">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <select name="reason" class="form-select" required>
                                <option value="">Select a reason</option>
                                <option value="deceptive">Deceptive Content</option>
                                <option value="inappropriate">Inappropriate Content</option>
                                <option value="spam">Spam Content</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <textarea name="details" class="form-control" rows="4" required placeholder="Please provide details about your report..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">Submit Report</button>
                            <button type="button" class="btn btn-outline-secondary" id="cancel-report">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Feedback Form -->
            <div id="feedback-form-container" class="card mb-4" style="display: none;">
                <div class="card-body">
                    <h4 class="card-title mb-4">Instructor Feedback</h4>
                    <form id="feedback-form" method="POST" action="{{ route('course.feedback') }}">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="instructor_id" value="{{ $course->instructor_id }}">

                        <div class="mb-3">
                            <label class="form-label">Feedback Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select type</option>
                                <option value="suggestion">Suggestion</option>
                                <option value="bug_report">Technical Issue</option>
                                <option value="general_comment">General Comment</option>
                                <option value="praise">Praise</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Your Feedback</label>
                            <textarea name="details" class="form-control" rows="4" required placeholder="Please provide your feedback in detail..."></textarea>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Submit Feedback</button>
                            <button type="button" class="btn btn-outline-secondary" id="cancel-feedback">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Course Content Section -->
            <div class="course-content">
                @if ($course->video)
                <h2 class="section-title">Course Video</h2>
                <div class="video-container mt-4">
                    <video controls>
                        <source src="{{ asset('storage/' . $course->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                @endif

                @if ($course->images->isNotEmpty())
                <h2 class="section-title mt-5">Course Gallery</h2>
                <div class="media-grid">
                    @foreach ($course->images as $image)
                    <div class="media-item" onclick="openModal('{{ asset('storage/' . $image->image_path) }}')">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Course Image">
                    </div>
                    @endforeach
                </div>
                @endif

                <h2 class="section-title mt-5">Course Content</h2>
                <div class="content-text">{!! nl2br(e($course->content)) !!}</div>
            </div>

            <!-- Course Levels Section - Only shown to users who have applied or admin/course owner -->
            @php
    $authId = Auth::id();
    $user = App\Models\User::where('account_id', $authId)->first();
    $userId = $user ? $user->id : null;
    $courseApplied = $userId ? App\Models\CourseUser::where('user_id', $userId)
        ->where('course_id', $course->id)
        ->where('apply', 1)
        ->exists() : false;
@endphp

@if($showFullContent || ($isUser && $courseApplied))
        <div class="course-card">
            <div class="course-content">
                <div class="levels-container">
                    <h2 class="section-title">Course Curriculum</h2>
                    
                    @foreach($course->levels->sortBy('order') as $level)
                    @php
                    $isInstructorOrAdmin = $showFullContent;
                    $isCompleted = ($userProgress && $userProgress->completed) || 
                                  ($userProgress && $userProgress->current_level > $level->order);
                    $isCurrent = $userProgress && $userProgress->current_level == $level->order && !$userProgress->completed;
                    $isLocked = !$isInstructorOrAdmin && (!$userProgress || ($userProgress && $userProgress->current_level < $level->order && !$userProgress->completed));
                @endphp                 
                <div class="level-card 
                    {{ $isCompleted ? 'completed' : '' }} 
                    {{ $isCurrent ? 'current' : '' }} 
                    {{ $isLocked ? 'locked' : '' }}">
                    
                    <div class="level-header" onclick="toggleLevelContent({{ $level->order }})">
                        <h4 class="level-title">Level {{ $level->order }}: {{ $level->title }}</h4>
                        <div class="level-status">
                            @if($level->passing_score)
                                <span class="success-rate" title="Passing score for this level">
                                    <i class="fas fa-trophy"></i>
                                    Pass: {{ $level->passing_score }}%
                                </span>
                            @endif
                            
                            @if($isCompleted)
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    <span>Completed</span>
                                </span>
                            @elseif($isCurrent)
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-play-circle text-primary me-1"></i>
                                    <span>In Progress</span>
                                </span>
                            @elseif($isLocked)
                                <span class="d-flex align-items-center">
                                    <i class="fas fa-lock text-secondary me-1"></i>
                                    <span>Locked</span>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$isLocked || $isCompleted)
                        <div class="level-content" id="level-content-{{ $level->order }}">
                            @if($level->text_contents)
                                @foreach($level->text_contents as $text)
                                <div class="content-text mb-4">
                                    {!! nl2br(e($text)) !!}
                                </div>
                                @endforeach
                            @endif

                            @if($level->images)
                            <div class="media-grid mb-4">
                                @foreach($level->images as $image)
                                <div class="media-item" onclick="openModal('{{ asset('storage/' . $image) }}')">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Level Image">
                                </div>
                                @endforeach
                            </div>
                            @endif

                            @if($level->videos)
                            <div class="mb-4">
                                @foreach($level->videos as $video)
                                <div class="video-container mb-3">
                                    <video controls>
                                        <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            
                            @if($isCurrent && $isUser)
                            <div class="text-center mt-4">
                                <button class="btn btn-primary start-test-btn" data-level-id="{{ $level->id }}">
                                    <i class="fas fa-play-circle me-2"></i> Start Level Test
                                </button>
                            </div>
                            <div class="test-container" id="test-container-{{ $level->id }}" style="display: none;">
                                <h4 class="mb-4">Test Questions</h4>
                                <form class="test-form" action="{{ route('course.submitTest', $course->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="level_id" value="{{ $level->id }}">
                                    @foreach($level->questions as $index => $question)
                                        <div class="question">
                                            <p class="fw-bold">Question {{ $index + 1 }}: {{ $question->question_text }}</p>
                                            @foreach($question->options as $key => $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="answers[{{ $question->id }}]" 
                                                           id="q{{ $question->id }}_option{{ $key }}" 
                                                           value="{{ $key }}">
                                                    <label class="form-check-label" for="q{{ $question->id }}_option{{ $key }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Answers
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if($level->questions->count() > 0 && $showFullContent)
                                <div class="test-container">
                                    <h4 class="mb-4">Test Questions (Answers Visible)</h4>
                                    @foreach($level->questions as $index => $question)
                                        <div class="question">
                                            <p class="fw-bold">Question {{ $index + 1 }}: {{ $question->question_text }}</p>
                                            @foreach($question->options as $key => $option)
                                                @if($key == $question->correct_answer)
                                                    <div class="form-check">
                                                        <span class="text-success fw-bold">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            {{ $option }} (Correct Answer)
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="form-check">
                                                        <span>{{ $option }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            @if($isCompleted && $level->questions->count() > 0)
                                <div class="test-container">
                                    <h4 class="mb-4">Test Results</h4>
                                    
                                    @foreach($level->questions as $index => $question)
                                        <div class="question">
                                            <p class="fw-bold">Question {{ $index + 1 }}: {{ $question->question_text }}</p>
                                            
                                            @foreach($question->options as $key => $option)
                                                @if($key == $question->correct_answer)
                                                    <div class="form-check">
                                                        <span class="text-success fw-bold">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            {{ $option }} (Correct Answer)
                                                        </span>
                                                    </div>
                                                @else
                                                    <div class="form-check">
                                                        <span>{{ $option }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                    @endforeach
                </div>
                
                <!-- Course Files Section -->
                @php
                    $courseFiles = is_array($course->files) ? $course->files : [];
                @endphp
                @if(!empty($courseFiles))
                <div class="course-files-section mt-5">
                    <h3 class="section-title mb-4">Course Files</h3>
                    <div class="row">
                        @foreach($courseFiles as $index => $file)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="file-card p-3 rounded-3 shadow-sm bg-white d-flex align-items-center">
                                @php
                                    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                    $fileType = in_array(strtolower($fileExtension), ['pdf']) ? 'pdf' : 
                                              (in_array(strtolower($fileExtension), ['doc', 'docx']) ? 'word' : 
                                              (in_array(strtolower($fileExtension), ['zip', 'rar']) ? 'archive' : 'file'));
                                @endphp
                                <div class="file-icon me-3">
                                    <i class="fas fa-file-{{ $fileType }} fa-2x text-primary"></i>
                                </div>
                                <div class="file-details flex-grow-1">
                                    <div class="file-name text-truncate" title="{{ $file['name'] }}">
                                        {{ $file['name'] }}
                                    </div>
                                    <div class="file-meta text-muted small">
                                        {{ number_format($file['size'] / 1024, 1) }} KB
                                    </div>
                                </div>
                                <div class="file-actions ms-2">
                                    <a href="{{ asset('storage/' . $file['path']) }}" class="btn btn-sm btn-outline-primary" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
            </div>
        </div>
        @elseif($isGuest)
        <!-- Locked Content Message for Guests -->
        <div class="course-card">
            <div class="locked-content">
                <div class="locked-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Course Curriculum Locked</h3>
                <p class="mb-4">Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to view the course curriculum.</p>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-success ms-2">
                    <i class="fas fa-user-plus me-2"></i> Register
                </a>
            </div>
        </div>
        @elseif($isUser && !$courseApplied)
        <!-- Locked Content Message for Users who haven't applied -->
        <div class="course-card">
            <div class="locked-content">
                <div class="locked-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Course Curriculum Locked</h3>
                <div class="alert alert-info p-4 mb-4" style="font-size: 1.1rem; border-left: 5px solid var(--primary);">
                    <div class="mb-2" dir="ltr">
                        <strong><i class="fas fa-info-circle me-2"></i> Important:</strong> You need to apply to this course to access the Course Curriculum. Click the "Apply to Course" button above to unlock all course materials.
                    </div>
                    <div class="mt-2" dir="rtl" style="text-align: right;">
                        <strong><i class="fas fa-info-circle me-2"></i> هام:</strong> يجب عليك التسجيل في هذا الكورس للوصول إلى محتوى الكورس. انقر على زر "Apply to Course" أعلاه لفتح جميع مواد الكورس.
                    </div>
                </div>
                <button class="btn btn-primary btn-lg" onclick="document.getElementById('applyButton').click()">
                    <i class="fas fa-unlock me-2"></i> Apply Now
                </button>
            </div>
        </div>
        @endif

        <!-- Comments Section -->
        <div class="comments-section">
            <h2 class="section-title">Course Discussion</h2>

            @if (Auth::check())
            <form method="POST" action="{{ route('comments.store') }}" class="comment-form">
                @csrf
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your thoughts about this course..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Please <a href="{{ route('login') }}" class="alert-link">login</a> to participate in discussions.
            </div>
            @endif

            <div class="comments-list">
                @foreach ($course->comments as $comment)
                    @if ($comment->parent_id == null)
                        <div class="comment" id="comment-{{ $comment->id }}">
                            <div class="comment-author">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ $comment->account->username ?? 'Unknown User' }}
                            </div>
                            <div class="comment-content">
                                <p class="comment-text" id="comment-text-{{ $comment->id }}">{{ $comment->comment }}</p>
                                <input type="text" class="form-control mb-2" id="comment-edit-{{ $comment->id }}" value="{{ $comment->comment }}" style="display: none;">
                            </div>
                            
                            @if (Auth::check())
                                <div class="comment-actions">
                                    @if (Auth::id() === $comment->account_id)
                                        <span class="action-link" onclick="editComment({{ $comment->id }})" id="edit-btn-{{ $comment->id }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </span>
                                        <span class="action-link" onclick="applyEdit({{ $comment->id }})" id="apply-edit-btn-{{ $comment->id }}" style="display: none;">
                                            <i class="fas fa-check"></i> Save
                                        </span>
                                        <form method="POST" action="{{ route('comments.delete', $comment->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-link text-danger border-0 bg-transparent">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @endif
                                    <span class="action-link" onclick="toggleReplyBox({{ $comment->id }})">
                                        <i class="fas fa-reply"></i> Reply
                                    </span>
                                </div>
                            @endif

                            <div id="reply-box-{{ $comment->id }}" class="reply-box" style="display: none;">
                                <form method="POST" action="{{ route('comments.reply', $comment->id) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="reply" class="form-control" rows="3" placeholder="Write your reply..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary">Post Reply</button>
                                </form>
                            </div>

                            @if ($comment->replies->count() > 0)
                                <div class="replies">
                                    @foreach ($comment->replies as $reply)
                                        <div class="comment" id="reply-{{ $reply->id }}">
                                            <div class="comment-author">
                                                <i class="fas fa-user-circle me-2"></i>
                                                {{ $reply->account->username ?? 'Unknown User' }}
                                            </div>
                                            <div class="comment-content">
                                                <p class="comment-text" id="reply-text-{{ $reply->id }}">{{ $reply->comment }}</p>
                                                <input type="text" class="form-control mb-2" id="reply-edit-{{ $reply->id }}" value="{{ $reply->comment }}" style="display: none;">
                                            </div>
                                            
                                            @if (Auth::check() && Auth::id() === $reply->account_id)
                                                <div class="comment-actions">
                                                    <span class="action-link" onclick="editReply({{ $reply->id }})" id="edit-btn-reply-{{ $reply->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </span>
                                                    <span class="action-link" onclick="applyEditReply({{ $reply->id }})" id="apply-edit-btn-reply-{{ $reply->id }}" style="display: none;">
                                                        <i class="fas fa-check"></i> Save
                                                    </span>
                                                    <form method="POST" action="{{ route('comments.delete', $reply->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-link text-danger border-0 bg-transparent">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal-overlay" id="imageModal" onclick="closeModal()">
        <span class="close-modal">&times;</span>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Enlarged Image">
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div class="toast-container">
        <!-- Toast notifications will be added here dynamically -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    
    <!-- Main JavaScript -->
    <script>
        // Initialize share functionality
        document.addEventListener('DOMContentLoaded', function() {
            var quickShareBtn = document.getElementById('quickShareBtn');
            var dropdown = document.getElementById('shareDropdownMenu');
            var courseBaseUrl = window.location.origin + '/course/{{ $course->id }}';
            var plainShareText = 'Discover amazing courses and level up your skills on SkillDev! Check out this course: ' + courseBaseUrl;
            
            // Set share links
            document.getElementById('shareWhatsapp').href = 'https://wa.me/?text=' + encodeURIComponent(plainShareText);
            document.getElementById('shareTwitter').href = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(plainShareText);
            document.getElementById('shareFacebook').href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(courseBaseUrl) + '&quote=' + encodeURIComponent(plainShareText);

            // Toggle dropdown
            quickShareBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });
            
            // Hide dropdown when clicking elsewhere
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target) && e.target !== quickShareBtn) {
                    dropdown.style.display = 'none';
                }
            });
            
            dropdown.addEventListener('click', function(e) { e.stopPropagation(); });

            // Copy Text button functionality
            var copyShareTextBtn = document.getElementById('copyShareTextBtn');
            var copyShareTextLabel = document.getElementById('copyShareTextLabel');
            if (copyShareTextBtn) {
                copyShareTextBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(plainShareText).then(function() {
                        copyShareTextLabel.textContent = 'Copied!';
                        setTimeout(function() {
                            copyShareTextLabel.textContent = 'Copy Text';
                        }, 1200);
                    });
                });
            }
        });

        // Enhanced toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            let icon;
            if (type === 'success') icon = 'fa-check-circle';
            else if (type === 'error') icon = 'fa-exclamation-circle';
            else icon = 'fa-info-circle';
            
            toast.innerHTML = `
                <div class="toast-body d-flex align-items-center">
                    <i class="fas ${icon} me-3"></i>
                    <div>${message}</div>
                    <button type="button" class="btn-close btn-close-white ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            toastContainer.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 400);
            }, 5000);
        }

        // Image Modal Functions
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modalImg.src = imageSrc;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking the X button
        document.querySelector('.close-modal').addEventListener('click', function(e) {
            e.stopPropagation();
            closeModal();
        });

        // Check favorite and apply status when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const courseId = {{ $course->id }};
            const favoriteButton = document.getElementById('favoriteButton');
            const applyButton = document.getElementById('applyButton');
            
            if (favoriteButton || applyButton) {
                // Make a single API call to get all course statuses
                fetch('/courses/all-statuses')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            console.error("Error fetching course statuses:", data.message);
                            return;
                        }
                        
                        // Get the status for this specific course
                        const status = data.statuses[courseId] || { apply: false, favorite: false };
                        
                        // Update favorite button if it exists
                        if (favoriteButton && status.favorite) {
                            favoriteButton.classList.add('active');
                            document.getElementById('favoriteIcon').classList.replace('far', 'fas');
                            document.getElementById('favoriteText').textContent = 'Favorited';
                        }
                        
                        // Update apply button if it exists
                        if (applyButton && status.apply) {
                            applyButton.classList.add('active');
                            document.getElementById('applyIcon').classList.replace('far', 'fas');
                            document.getElementById('applyIcon').classList.replace('fa-square', 'fa-check-square');
                            document.getElementById('applyText').textContent = 'Applied';
                            applyButton.style.pointerEvents = 'none';
                        }
                    })
                    .catch(error => console.error("Error fetching course statuses:", error));
            }

            // Rating functionality
            const stars = $('.stars .star');

            const updateStars = (rating) => {
                stars.each(function() {
                    const star = $(this);
                    star.toggleClass('selected', star.data('value') <= rating);
                    
                    if (star.data('value') <= rating) {
                        star.css('transform', 'scale(1.2)');
                        setTimeout(() => {
                            star.css('transform', 'scale(1)');
                        }, 200);
                    }
                });
            };

            // Get user's rating if exists
            $.get('/ratings/{{ $course->id }}', function(response) {
                if (response.rating) {
                    updateStars(response.rating);
                    $('.stars').data('user-rating', response.rating);
                }
            });


            // Get average rating
            $.get('/ratings/average/{{ $course->id }}', function(response) {
                if (response.success) {
                    $('#average-rating-value').text(response.average_rating.toFixed(1));
                } else {
                    $('#average-rating-value').text('N/A');
                }
            });

            // Report functionality
            $('#report-button').click(function() {
                $('#report-form-container').slideDown('fast');
                $(this).hide();
            });
            
            $('#cancel-report').click(function() {
                $('#report-form-container').slideUp('fast');
                $('#report-button').show();
            });

            // Feedback functionality
            $('#feedback-button').click(function() {
                $('#feedback-form-container').slideDown('fast');
                $(this).hide();
            });
            
            $('#cancel-feedback').click(function() {
                $('#feedback-form-container').slideUp('fast');
                $('#feedback-button').show();
            });
            
            // Handle feedback form submission
            $('#feedback-form').submit(function(e) {
                e.preventDefault();
                
                const formData = $(this).serialize();
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#feedback-form-container').slideUp('fast');
                        $('#feedback-button').show();
                        $('#feedback-form')[0].reset();
                        showToast('Thank you for your feedback!', 'success');
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while submitting your feedback';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showToast(errorMessage, 'error');
                    }
                });
            });

            // Start test button functionality
            document.querySelectorAll('.start-test-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const levelId = this.getAttribute('data-level-id');
                    const testContainer = document.getElementById(`test-container-${levelId}`);
                    
                    this.style.display = 'none';
                    testContainer.style.display = 'block';
                    
                    testContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });

            // Open current level automatically (only if not completed)
            @if(Auth::check() && (Auth::user()->account_type == 'instructor' || Auth::user()->account_type == 'admin'))
            // Open all levels for instructors and admins
            document.querySelectorAll('.level-content').forEach(content => {
                content.classList.add('show');
            });
            @endif
            @if($userProgress && !$userProgress->completed)
                const currentLevel = {{ $userProgress->current_level ?? 0 }};
                if (currentLevel > 0) {
                    const currentLevelContent = document.getElementById(`level-content-${currentLevel}`);
                    if (currentLevelContent) {
                        currentLevelContent.classList.add('show');
                    }
                }
            @endif

            // Toggle share links visibility
            function toggleShareLinks(btn, linksId) {
                const links = document.getElementById(linksId);
                const btnText = btn.querySelector('.share-btn-text');
                
                // Hide all other share links first
                document.querySelectorAll('[id^="shareLinks-"]').forEach(el => {
                    if (el.id !== linksId) {
                        el.style.display = 'none';
                        const otherBtn = document.querySelector(`[id^="shareResultBtn-"]:not(#${btn.id})`);
                        if (otherBtn) {
                            otherBtn.classList.remove('active');
                            otherBtn.querySelector('.share-btn-text').textContent = 
                                otherBtn.id.includes('completed') ? 'Share Result' : 'Share Progress';
                        }
                    }
                });

                // Toggle current share links
                if (links.style.display === 'none' || links.style.display === '') {
                    btn.classList.add('active');
                    btnText.textContent = btn.id.includes('completed') ? 'Share Result' : 'Share Progress';
                    links.style.display = 'flex';
                    // Hide the share button itself
                    btn.style.display = 'none';
                } else {
                    btn.classList.remove('active');
                    btnText.textContent = btn.id.includes('completed') ? 'Share Result' : 'Share Progress';
                    links.style.display = 'none';
                }
            }

            // Share Result Logic
            var completedBtn = document.getElementById('shareResultBtn-completed');
            if (completedBtn) {
                completedBtn.addEventListener('click', function() {
                    toggleShareLinks(this, 'shareLinks-completed');
                    const completed = true;
                    const course = this.getAttribute('data-course');
                    let shareText = `🎉 I just completed the amazing course \"${course}\" on SkillDev! 🚀\n\nSkillDev is the platform where you can learn, grow, and connect with top instructors. Explore interactive lessons, real projects, and a supportive community.\n\nCheck it out and start your learning journey today!`;
                    const encoded = encodeURIComponent(shareText);
                    const url = encodeURIComponent(window.location.href);
                    const linksHtml = `
                        <div class='d-flex gap-2'>
                            <a class='btn btn-success btn-sm' target='_blank' href='https://wa.me/?text=${encoded}%20${url}'><i class='fab fa-whatsapp'></i> WhatsApp</a>
                            <a class='btn btn-info btn-sm' target='_blank' href='https://twitter.com/intent/tweet?text=${encoded}&url=${url}'><i class='fab fa-twitter'></i> Twitter</a>
                            <a class='btn btn-primary btn-sm' target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${encoded}'><i class='fab fa-facebook'></i> Facebook</a>
                            <button type="button" class='btn btn-secondary btn-sm copy-share-text'>Copy Text</button>
                        </div>
                    `;
                    document.getElementById('shareLinks-completed').innerHTML = linksHtml;
                    document.getElementById('shareLinks-completed').style.display = 'flex';
                    document.getElementById('shareLinks-completed').setAttribute('data-share-text', shareText + ' ' + window.location.href);
                });
            }
            
            // Progress share button
            var progressBtn = document.getElementById('shareResultBtn-progress');
            if (progressBtn) {
                progressBtn.addEventListener('click', function() {
                    toggleShareLinks(this, 'shareLinks-progress');
                    const completed = false;
                    const course = this.getAttribute('data-course');
                    const level = this.getAttribute('data-level');
                    // Always use the main course URL for sharing (completely plain text approach)
                    const courseBaseUrl = window.location.origin + '/course/{{ $course->id }}';
                    const plainShareText = 'I reached level ' + level + ' in the course "' + course + '" on SkillDev! Check it out: ' + courseBaseUrl;
                    const encoded = encodeURIComponent(plainShareText);
                    const url = encodeURIComponent(courseBaseUrl);
                    const linksHtml = `
                        <div class='d-flex gap-2'>
                            <a class='btn btn-success btn-sm' target='_blank' href='https://wa.me/?text=${encoded}'><i class='fab fa-whatsapp'></i> WhatsApp</a>
                            <a class='btn btn-info btn-sm' target='_blank' href='https://twitter.com/intent/tweet?text=${encoded}'><i class='fab fa-twitter'></i> Twitter</a>
                            <a class='btn btn-primary btn-sm' target='_blank' href='https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${encoded}'><i class='fab fa-facebook'></i> Facebook</a>
                            <button type="button" class='btn btn-secondary btn-sm copy-share-text'>Copy Text</button>
                        </div>
                    `;
                    document.getElementById('shareLinks-progress').innerHTML = linksHtml;
                    document.getElementById('shareLinks-progress').style.display = 'flex';
                    document.getElementById('shareLinks-progress').setAttribute('data-share-text', plainShareText);
                });
            }
            
            // Event delegation for Copy Text button
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('copy-share-text')) {
                    e.preventDefault();
                    var parent = e.target.closest('[id^=shareLinks]');
                    var text = parent ? parent.getAttribute('data-share-text') : '';
                    if (text) {
                        navigator.clipboard.writeText(text).then(function() {
                            e.target.textContent = 'Copied!';
                            setTimeout(function() { e.target.textContent = 'Copy Text'; }, 1500);
                        });
                    }
                }
            });
        });

        // Toggle level content
        function toggleLevelContent(levelOrder) {
            const levelContent = document.getElementById(`level-content-${levelOrder}`);
            if (levelContent) {
                levelContent.classList.toggle('show');
                
                if (levelContent.classList.contains('show')) {
                    levelContent.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
        }

        // Favorite function with animation
        document.getElementById('favoriteButton')?.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const icon = document.getElementById('favoriteIcon');
            const text = document.getElementById('favoriteText');
            const isFavorite = icon.classList.contains('fas');
            
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
            
            fetch(`/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.favorite) {
                        icon.classList.replace('far', 'fas');
                        text.textContent = 'Favorited';
                        this.classList.add('active');
                        
                        icon.style.transform = 'scale(1.3)';
                        setTimeout(() => {
                            icon.style.transform = 'scale(1)';
                        }, 300);
                        
                        showToast('Course added to favorites!', 'success');
                    } else {
                        icon.classList.replace('fas', 'far');
                        text.textContent = 'Add to Favorites';
                        this.classList.remove('active');
                        showToast('Course removed from favorites!', 'info');
                    }
                } else {
                    showToast(data.message || "An error occurred.", 'error');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("An error occurred.", 'error');
            });
        });

        // Apply function with animation
        document.getElementById('applyButton')?.addEventListener('click', function() {
            const courseId = this.getAttribute('data-course-id');
            const icon = document.getElementById('applyIcon');
            const text = document.getElementById('applyText');
            
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 200);
            
            fetch(`/apply-course`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    course_id: courseId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    icon.classList.replace('far', 'fas');
                    icon.classList.replace('fa-square', 'fa-check-square');
                    text.textContent = 'Applied';
                    this.classList.add('active');
                    this.style.pointerEvents = 'none';
                    
                    icon.style.transform = 'scale(1.3)';
                    setTimeout(() => {
                        icon.style.transform = 'scale(1)';
                    }, 300);
                    
                    showToast('Application submitted successfully!', 'success');
                    // Reload the page to show the curriculum
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast(data.message || "An error occurred.", 'error');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("An error occurred.", 'error');
            });
        });

        // Comment functions
        function editComment(commentId) {
            document.getElementById('comment-text-' + commentId).style.display = 'none';
            document.getElementById('comment-edit-' + commentId).style.display = 'block';
            document.getElementById('edit-btn-' + commentId).style.display = 'none';
            document.getElementById('apply-edit-btn-' + commentId).style.display = 'inline-block';
            document.getElementById('comment-edit-' + commentId).focus();
        }

        function applyEdit(commentId) {
            const newComment = document.getElementById('comment-edit-' + commentId).value;

            fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: newComment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('comment-text-' + commentId).textContent = newComment;
                    document.getElementById('comment-text-' + commentId).style.display = 'block';
                    document.getElementById('comment-edit-' + commentId).style.display = 'none';
                    document.getElementById('apply-edit-btn-' + commentId).style.display = 'none';
                    document.getElementById('edit-btn-' + commentId).style.display = 'inline-block';
                    
                    showToast('Comment updated successfully!', 'success');
                }
            })
            .catch(error => {
                showToast('Error updating comment', 'error');
            });
        }

        function editReply(replyId) {
            document.getElementById('reply-text-' + replyId).style.display = 'none';
            document.getElementById('reply-edit-' + replyId).style.display = 'block';
            document.getElementById('edit-btn-reply-' + replyId).style.display = 'none';
            document.getElementById('apply-edit-btn-reply-' + replyId).style.display = 'inline-block';
            document.getElementById('reply-edit-' + replyId).focus();
        }

        function applyEditReply(replyId) {
            const newReply = document.getElementById('reply-edit-' + replyId).value;

            fetch(`/comments/${replyId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: newReply })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('reply-text-' + replyId).textContent = newReply;
                    document.getElementById('reply-text-' + replyId).style.display = 'block';
                    document.getElementById('reply-edit-' + replyId).style.display = 'none';
                    document.getElementById('apply-edit-btn-reply-' + replyId).style.display = 'none';
                    document.getElementById('edit-btn-reply-' + replyId).style.display = 'inline-block';
                    
                    showToast('Reply updated successfully!', 'success');
                }
            })
            .catch(error => {
                showToast('Error updating reply', 'error');
            });
        }

        function toggleReplyBox(commentId) {
            const replyBox = document.getElementById(`reply-box-${commentId}`);
            if (replyBox.style.display === 'none') {
                replyBox.style.display = 'block';
                replyBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                replyBox.style.display = 'none';
            }
        }
        
        // Auto-hide alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Handle comment form submission
            const commentForm = document.querySelector('.comment-form');
            if (commentForm) {
                commentForm.addEventListener('submit', function() {
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            setTimeout(() => {
                                alert.style.transition = 'opacity 0.5s';
                                alert.style.opacity = '0';
                                setTimeout(() => alert.remove(), 500);
                            }, 3000);
                        });
                    }, 100);
                });
            }
            
            // Handle reply form submissions
            const replyForms = document.querySelectorAll('form[action*="/comments/reply/"]');
            replyForms.forEach(form => {
                form.addEventListener('submit', function() {
                    setTimeout(function() {
                        const alerts = document.querySelectorAll('.alert');
                        alerts.forEach(alert => {
                            setTimeout(() => {
                                alert.style.transition = 'opacity 0.5s';
                                alert.style.opacity = '0';
                                setTimeout(() => alert.remove(), 500);
                            }, 3000);
                        });
                    }, 100);
                });
            });
            
            // Auto-hide any existing alerts after 3 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.permanent)');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }, 3000);
                });
            }, 100);
        });

        // Rating functionality
const stars = $('.stars .star');

const updateStars = (rating) => {
    stars.each(function() {
        const star = $(this);
        star.toggleClass('selected', star.data('value') <= rating);
        
        if (star.data('value') <= rating) {
            star.css('color', 'var(--warning)'); // لون النجوم المختارة
            star.css('transform', 'scale(1.2)');
            setTimeout(() => {
                star.css('transform', 'scale(1)');
            }, 200);
        } else {
            star.css('color', 'var(--light-gray)'); // لون النجوم غير المختارة
        }
    });
};

// Get user's rating if exists
$.get('/ratings/{{ $course->id }}', function(response) {
    if (response.rating) {
        updateStars(response.rating);
        $('.stars').data('user-rating', response.rating);
    } else {
        stars.css('color', 'var(--light-gray)'); // اللون الافتراضي
    }
});

// Handle star clicking with animation
stars.on('click', function() {
    const rating = $(this).data('value');
    $.post('/rate/{{ $course->id }}', {
        rating: rating,
        _token: $('meta[name="csrf-token"]').attr('content')
    }, function(response) {
        if (response.success) {
            updateStars(rating);
            $('.stars').data('user-rating', rating);
            showToast('Rating submitted successfully!', 'success');
        } else {
            showToast('Failed to submit rating. Please try again.', 'error');
        }
    });
});
    </script>
<!-- Placement Test Modal -->
<div class="modal fade" id="placementTestModal" tabindex="-1" aria-labelledby="placementTestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="placementTestModalLabel">اختبار تحديد المستوى</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body" id="placementTestContent">
                                    <!-- Questions will be loaded here dynamically -->
                                    <div class="text-center">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add this to your existing JavaScript
$(document).ready(function() {
    // Handle the placement test button click
    $('#startPlacementTest').on('click', function() {
        // Get the template content
        const template = document.getElementById('placement-test-form-template');
        const content = template.content.cloneNode(true);
        
        // Clear any previous content and add the new content
        $('#customPlacementTestContent').html(content);
        
        // Show the custom overlay
        $('#customPlacementTestOverlay').fadeIn(300);
        
        // Prevent scrolling on the body
        $('body').css('overflow', 'hidden');
    });
    
    // Handle the close button click
    $('#closeCustomPlacementTest').on('click', function() {
        $('#customPlacementTestOverlay').fadeOut(300);
        $('body').css('overflow', '');
    });
    
    // Also close when clicking outside the content area (on the overlay)
    $('#customPlacementTestOverlay').on('click', function(e) {
        if (e.target.id === 'customPlacementTestOverlay') {
            $('#customPlacementTestOverlay').fadeOut(300);
            $('body').css('overflow', '');
        }
    });
});
</script>
<!-- Custom Placement Test Overlay (Hidden by default) -->
<div id="customPlacementTestOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; overflow-y: auto; padding-top: 70px; box-sizing: border-box;">
    <div style="background-color: #f8f9fa; margin: 0 auto 30px; width: 90%; max-width: 900px; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; max-height: calc(100vh - 100px); display: flex; flex-direction: column;">
        <div style="background-color: var(--primary); color: white; padding: 15px 20px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1;">
            <h3 style="margin: 0; font-size: 1.5rem;">
                <i class="fas fa-clipboard-check me-2"></i>
                اختبار تحديد المستوى
            </h3>
            <button id="closeCustomPlacementTest" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div style="padding: 20px; overflow-y: auto; flex-grow: 1;">
            <div id="customPlacementTestContent" style="background-color: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); min-height: 400px;">
                <!-- Questions will be loaded here dynamically -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-3 text-muted">جاري تحميل الاختبار...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Placement Test Styles */
.modal-fullscreen {
    padding: 0 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.modal-fullscreen .modal-content {
    min-height: 100vh;
    border: 0;
    border-radius: 0;
    margin: 0 auto;
    max-width: 100%;
}

.modal-backdrop.show {
    opacity: 0.85;
}

.placement-test-btn {
    background: linear-gradient(45deg, var(--primary), var(--accent));
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: var(--border-radius);
    font-weight: 600;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--shadow-md);
}

.placement-test-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    color: white;
    background: linear-gradient(45deg, var(--accent), var(--primary));
}

.question-card {
    background: white;
    border-radius: 1rem;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.question-number {
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.question-card:hover {
    transform: translateY(-2px);
}

.options-grid {
    display: grid;
    gap: 1rem;
    margin-top: 1.5rem;
}

.custom-radio {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    padding: 1rem !important;
    transition: all 0.3s ease;
    cursor: pointer;
}

.custom-radio:hover {
    border-color: var(--primary);
    background: #f8f9ff;
    transform: translateY(-2px);
}

.custom-radio input[type="radio"]:checked + label {
    color: var(--primary);
    font-weight: 600;
}

.custom-radio input[type="radio"]:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

.submit-btn {
    background: linear-gradient(45deg, var(--primary), var(--accent));
    color: white;
    border: none;
    padding: 1rem 3rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-md);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: linear-gradient(45deg, var(--accent), var(--primary));
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}
</style>

<script>
    // Toast notification function
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        
        // Set toast styles based on type
        const bgColor = type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : type === 'warning' ? '#fff3cd' : '#d1ecf1';
        const textColor = type === 'success' ? '#155724' : type === 'error' ? '#721c24' : type === 'warning' ? '#856404' : '#0c5460';
        const borderColor = type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : type === 'warning' ? '#ffeeba' : '#bee5eb';
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle';
        
        toast.innerHTML = `
            <div style="
                padding: 15px;
                margin-bottom: 10px;
                border: 1px solid ${borderColor};
                border-radius: 8px;
                color: ${textColor};
                background-color: ${bgColor};
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                justify-content: space-between;
                animation: fadeInDown 0.3s ease-out;
                width: 100%;
            ">
                <div>
                    <i class="fas fa-${icon} me-2"></i> ${message}
                </div>
                <button type="button" style="
                    background: none;
                    border: none;
                    font-size: 16px;
                    cursor: pointer;
                    color: ${textColor};
                    opacity: 0.7;
                " onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }
    
    // Example of how to use the toast notification
    // You can call this function from anywhere in your JavaScript code
    // showToast('Your action was successful!', 'success');
    // showToast('Something went wrong!', 'error');
    // showToast('Please be careful!', 'warning');
    // showToast('Just for your information.', 'info');
</script>
</body>

</html>