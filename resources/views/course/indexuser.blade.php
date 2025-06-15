@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Course Cards</title>
    <link rel="stylesheet" href="{{ asset('css/course/userindex.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <style>


        /* Main Content */
        .main-content {
            padding: 40px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .course-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            overflow: hidden;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .course-image {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .course-card:hover .course-image img {
            transform: scale(1.05);
        }

        .course-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .course-title {
            font-weight: 600;
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-preview {
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.5;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .course-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding: 0 15px 15px;
        }

        .course-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            padding: 5px;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .course-actions button.favorite-button {
            color: #e74c3c;
            background-color: rgba(231, 76, 60, 0.1);
        }

        .course-actions button.favorite-button:hover {
            background-color: rgba(231, 76, 60, 0.2);
        }

        .course-actions button.favorite-button.active {
            background-color: rgba(231, 76, 60, 0.1);
        }
        
        .course-actions button.favorite-button .fas {
            color: #e74c3c;
        }

        .course-actions button.apply-button {
            color: #2ecc71;
            background-color: rgba(46, 204, 113, 0.1);
        }

        .course-actions button.apply-button:hover {
            background-color: rgba(46, 204, 113, 0.2);
        }

        .course-actions button.apply-button.active {
            color: white;
            background-color: #2ecc71;
        }

        .current-level-badge {
            background: #f59e0b;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 15px;
            margin: 0 10px;
            display: inline-block;
        }
        
        .completed-badge {
            background: #10b981;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 15px;
            margin: 0 10px;
            display: inline-block;
        }

        .no-courses-message {
            text-align: center;
            width: 100%;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            grid-column: 1 / -1;
        }

        .no-courses-message .message-icon {
            font-size: 60px;
            color: #3498db;
            margin-bottom: 20px;
        }

        .no-courses-message h2 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .no-courses-message p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .no-courses-message .explore-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .no-courses-message .explore-button:hover {
            background-color: #2980b9;
        }

        /* Responsive breakpoints for course grid */
        @media (min-width: 1400px) {
            .main-content {
                grid-template-columns: repeat(4, 1fr);
                max-width: 1400px;
            }
        }
        
        @media (min-width: 1200px) and (max-width: 1399px) {
            .main-content {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (min-width: 768px) and (max-width: 1199px) {
            .main-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 25px;
                padding: 30px 15px;
            }
        }
        
        @media (min-width: 576px) and (max-width: 767px) {
            .main-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 20px 10px;
            }
            
            .course-image {
                height: 160px;
            }
            
            .course-title {
                font-size: 16px;
            }
            
            .course-preview {
                font-size: 13px;
                -webkit-line-clamp: 2;
            }
        }

        @media (max-width: 575px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px 15px;
            }
            
            .course-image {
                height: 180px;
            }
        }
        
        /* Animation for notifications */
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
    </script>
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
    
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    </script>
    @endif
    


    <!-- Main Content -->
    <div class="main-content">
        @if ($courses->count() > 0)
            @foreach ($courses as $course)
                @php
                    $progress = $course->userProgress->where('user_id', Auth::id())->first();
                @endphp
                <div class="course-card">
                    <a href="{{ route('course.show', $course->id) }}" class="course-image">
                        <img src="{{ asset($course->photo ? 'storage/' . $course->photo : 'images/noimage.jpg') }}" alt="Course Image">
                    </a>
                    <div class="course-info">
                        <div class="course-title">{{ $course->title }}</div>
                        <div class="course-preview">{{ $course->coursepreview }}</div>
                    </div>
                    @if (Auth::check() && Auth::user()->account_type == 'user')
                        <div class="course-actions">
                            <button class="favorite-button" onclick="toggleFavorite(event, this)" data-course-id="{{ $course->id }}">
                                <i class="far fa-heart"></i>
                            </button>
                            @if($progress)
                                @if($progress->completed)
                                    <span class="completed-badge">Completed</span>
                                @else
                                    <span class="current-level-badge">Level {{ $progress->current_level }}</span>
                                @endif
                            @endif
                            <button class="apply-button" onclick="applyCourse(event, this)" data-course-id="{{ $course->id }}">
                                <i class="far fa-square"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="no-courses-message">
                <div class="message-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h2>No Courses Found</h2>
                <p>There are currently no courses available. Please check back later.</p>
                <a href="{{ route('course.index') }}" class="explore-button">Explore Courses</a>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/profile/course-statuses.js') }}"></script>
 
</body>
</html>