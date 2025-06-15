@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>In Progress Courses</title>
    <link rel="stylesheet" href="{{ asset('css/profile/apply.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <style>
 

        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .course-card {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            cursor: pointer;
            margin-left:20px;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .course-image {
            flex-shrink: 0;
            margin-bottom: 15px;
            width: 100%;
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f0f0f0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .course-card:hover .course-image img {
            transform: scale(1.05);
        }

        .course-info {
            flex-grow: 1;
            color: #333;
        }

        .course-title {
            font-weight: 600;
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .course-preview {
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.5;
        }

        .course-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .course-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s;
        }

        .course-actions button.favorite-button {
            color: #e74c3c;
        }

        .course-actions button.apply-button {
            color: #2ecc71;
        }

        .course-actions button:hover {
            color: #2980b9;
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
        
        .no-courses-message {
            text-align: center;
            width: 100%;
            max-width: 600px;
            padding: 50px 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            margin: 40px auto;
            position: relative;
            left: 135px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .no-courses-message:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .no-courses-message .message-icon {
            font-size: 70px;
            color: var(--primary-color);
            margin-bottom: 25px;
            background-color: rgba(79, 70, 229, 0.1);
            width: 120px;
            height: 120px;
            line-height: 120px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(79, 70, 229, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
            }
        }

        .no-courses-message h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }

        .no-courses-message p {
            font-size: 17px;
            color: var(--gray-color);
            margin-bottom: 30px;
            max-width: 450px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .no-courses-message .explore-button {
            display: inline-block;
            padding: 12px 28px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-size: 17px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }

        .no-courses-message .explore-button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.profile-sidebar-styles')
    
    @if(session('error'))
        <div class="alert alert-danger" style="padding: 15px; margin: 20px 0; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24; background-color: #f8d7da;">
            {{ session('error') }}
        </div>
    @endif
    @include('layouts.profile-sidebar')
    <!-- Main Content -->
    <div class="main-content">
        @if ($courses->count() > 0)
            @foreach ($courses as $progress)
                @php $course = $progress->course; @endphp
                <div class="course-card">
                    <a href="{{ route('course.show', $course->id) }}" class="course-image">
                        <img src="{{ asset($course->photo ? 'storage/' . $course->photo : 'images/noimage.jpg') }}" alt="Course Image">
                    </a>
                    <div class="course-info d-flex flex-column" style="padding: 15px;">
                        <div class="course-title" style="font-weight: 600; font-size: 18px; color: #2c3e50; margin-bottom: 8px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $course->title }}</div>
                        <div class="course-preview" style="font-size: 14px; color: #7f8c8d; line-height: 1.5; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">{{ $course->coursepreview }}</div>
                        @if (Auth::check() && Auth::user()->account_type == 'user')
                        <div class="course-actions d-flex justify-content-between align-items-center mt-auto" style="padding: 0 15px 15px;">
                            <button class="favorite-button" onclick="toggleFavorite(event, this)" data-course-id="{{ $course->id }}" style="background: none; border: none; cursor: pointer; font-size: 18px; transition: all 0.3s; padding: 5px; border-radius: 50%; width: 36px; height: 36px; color: #e74c3c; background-color: rgba(231, 76, 60, 0.1);">
                                <i class="far fa-heart"></i>
                            </button>
                            <span class="current-level-badge">Level {{ $progress->current_level }}</span>
                            <button class="apply-button" onclick="applyCourse(event, this)" data-course-id="{{ $course->id }}" style="background: none; border: none; cursor: pointer; font-size: 18px; transition: all 0.3s; padding: 5px; border-radius: 50%; width: 36px; height: 36px; color: #2ecc71; background-color: rgba(46, 204, 113, 0.1);">
                                <i class="far fa-square"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-courses-message">
                <div class="message-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h2>No In Progress Courses Found</h2>
                <p>You haven't started any courses yet. Start learning and track your progress!</p>
                <a href="{{ route('course.index') }}" class="explore-button">
                    <i class="fas fa-search mr-2"></i> Explore Courses
                </a>
            </div>
        @endif
    </div>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/profile/course-statuses.js') }}"></script>
</body>
</html>