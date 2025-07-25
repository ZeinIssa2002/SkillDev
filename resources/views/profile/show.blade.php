<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $profile->profilename ?? ($account->username ?? 'Profile') }} | Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        
        .container {
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
        }
        
        .form-control {
            border: 1px solid var(--gray-color);
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
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

        /* Rest of your existing styles... */
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .profile-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            font-size: 2rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .account-type-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .account-type-badge.instructor {
            background-color: #3498db;
            color: white;
        }

        .account-type-badge.user {
            background-color: #2ecc71;
            color: white;
        }

        .verification-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .verified {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .not-verified {
            background-color: #ffebee;
            color: #c62828;
        }

        /* Profile Card */
        .profile-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            padding: 2rem;
        }

        .profile-main-info {
            display: flex;
            gap: 2rem;
            align-items: center;
            margin-bottom: 2rem;
        }

        .profile-picture-container {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eaeaea;
        }

        .verification-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: #3498db;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .profile-details {
            flex-grow: 1;
        }

        .profile-details h2 {
            font-size: 1.8rem;
            margin: 0 0 0.5rem 0;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-title {
            color: #7f8c8d;
            margin: 0 0 1rem 0;
            font-size: 1rem;
        }

        .chat-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s;
        }

        .chat-button:hover {
            background-color: #3e8e41;
        }

        /* Profile Sections */
        .profile-sections {
            display: grid;
            gap: 2rem;
        }

        .about-section, .contact-section, .instructor-stats, .rating-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
        }

        .about-section h3, .contact-section h3, .instructor-stats h3, .rating-section h3 {
            margin-top: 0;
            color: #2c3e50;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .contact-details p {
            margin: 0.5rem 0;
        }

        /* Instructor Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .stat-item {
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-item i {
            font-size: 1.5rem;
            color: #3498db;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-item span {
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        /* Rating Section */
        .rating-section {
            margin-top: 2rem;
        }

        .rating {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .star {
            font-size: 1.8rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star.selected, .star:hover {
            color: #f39c12;
        }

        /* Profile Actions */
        .profile-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .action-button {
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s;
            border: none;
        }

        .action-button.chat {
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
        }

        .action-button.chat:hover {
            background-color: #3e8e41;
        }

        .action-button.add-friend {
            background-color: #3498db;
            color: white;
        }

        .action-button.add-friend:hover {
            background-color: #2980b9;
        }

        .action-button.pending {
            background-color: #f39c12;
            color: white;
        }

        .action-button.friends {
            background-color: #2ecc71;
            color: white;
        }

        .action-button.rejected {
            background-color: #e74c3c;
            color: white;
        }

        .friend-request-actions {
            display: flex;
            gap: 5px;
        }

        .action-button.accept {
            background-color: #2ecc71;
            color: white;
        }

        .action-button.accept:hover {
            background-color: #27ae60;
        }

        .action-button.reject {
            background-color: #e74c3c;
            color: white;
        }

        .action-button.reject:hover {
            background-color: #c0392b;
        }

        /* Work Hours */
        .work-hours-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1rem;
            border-left: 3px solid #3498db;
        }

        .work-hours-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .work-hours-header i {
            color: #3498db;
        }

        .work-hours-content {
            padding: 0.5rem;
            background-color: white;
            border-radius: 5px;
            font-size: 0.95rem;
        }

        /* Privacy Button */
        .btn-privacy {
            padding: 10px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-privacy i {
            font-size: 1rem;
        }

        .btn-privacy:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .text-muted {
            color: #6c757d !important;
            font-style: italic;
        }

        /* Courses Section */
        .courses-section {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 2rem;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            border-radius: 8px 8px 0 0;
            height: 180px;
            object-fit: cover;
        }

        .card-footer {
            border-top: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-wrap: wrap;
                gap: 1rem;
            }

            .search-form {
                order: 3;
                width: 100%;
            }

            .menu-icon {
                display: block;
            }

            .profile-main-info {
                flex-direction: column;
                text-align: center;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .profile-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .action-button {
                width: 100%;
                justify-content: center;
            }
            
            .friend-request-actions {
                width: 100%;
            }
            
            .friend-request-actions .action-button {
                flex-grow: 1;
            }
        }
    </style>
</head>

<body>
    @include('layouts.navbar')
    
    @if(session('error'))
        <div class="alert alert-danger" style="
            padding: 15px;
            margin: 20px 0;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
            background-color: #f8d7da;
        ">
            {{ session('error') }}
        </div>
    @endif

    <div class="profile-container">
        <div class="profile-header">
            <h1>Profile <span class="account-type-badge {{ $account->account_type }}">{{ ucfirst($account->account_type) }}</span></h1>
            @if($account->account_type === 'instructor')
                <div class="verification-status {{ $instructor->confirmation == 1 ? 'verified' : 'not-verified' }}">
                    @if($instructor->confirmation == 1)
                        <i class="fas fa-check-circle"></i>
                        <span>Verified Instructor</span>
                    @else
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Not Verified</span>
                    @endif
                </div>
            @endif
        </div>

        <div class="profile-card">
            <div class="profile-main-info">
                <div class="profile-picture-container">
                    <img src="{{ $profile->photo ? asset('storage/' . $profile->photo) : asset('images/noimageprofile.jpg') }}"
                        alt="Profile Picture" class="profile-picture">
                    @if($account->account_type === 'instructor' && $instructor->confirmation == 1)
                        <div class="verification-badge">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    @endif
                </div>
                
                <div class="profile-details">
                    <div class="profile-header-row">
                        <h2>
                            {{ $profile->profilename ?? ($account->username ?? 'No username available') }}
                            @if($account->account_type === 'instructor' && $instructor->confirmation == 1)
                                <i class="fas fa-check-circle verified-icon"></i>
                            @endif
                        </h2>
                        
                        <div class="profile-actions">
                            @if(auth()->check() && auth()->user()->id != $account->id)
                                <!-- زر المحادثة -->
                                @if(!in_array(auth()->user()->account_type, ['guest']))
                                <a href="{{ route('chat.index', ['id' => $account->id]) }}" class="action-button chat">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                                @endif
                                
                                <!-- زر الصداقة الديناميكي -->
                                @php
                                    $friendshipStatus = app('App\Http\Controllers\FriendController')->checkFriendshipStatus($account->id);
                                @endphp
                                
                                @if($friendshipStatus == 'none')
                                    @if(!in_array(auth()->user()->account_type, ['guest','admin']))
                                    <form action="{{ route('friend.request', $account->id) }}" method="POST" class="friend-form">
                                        @csrf
                                        <button type="submit" class="action-button add-friend">
                                            <i class="fas fa-user-plus"></i> Add Friend
                                        </button>
                                    </form>
                                    @endif
                                @elseif($friendshipStatus == 'pending')
                                    @if($friendRequest = \App\Models\FriendRequest::where('sender_id', auth()->id())
                                                                          ->where('receiver_id', $account->id)
                                                                          ->where('status', 'pending')
                                                                          ->first())
                                        <button class="action-button pending" disabled>
                                            <i class="fas fa-clock"></i> Request Sent
                                        </button>
                                    @else
                                        <div class="friend-request-actions">
                                            <form action="{{ route('friend.accept', $friendRequest->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="action-button accept">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('friend.reject', $friendRequest->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="action-button reject">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @elseif($friendshipStatus == 'accepted')
                                    <button class="action-button friends" disabled>
                                        <i class="fas fa-user-friends"></i> Friends
                                    </button>
                                @elseif($friendshipStatus == 'rejected')
                                    <button class="action-button rejected" disabled>
                                        <i class="fas fa-times-circle"></i> Request Rejected
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    <p class="profile-title">
                        @if($account->account_type === 'instructor')
                            <i class="fas fa-chalkboard-teacher"></i> Instructor
                            @if($instructor->confirmation == 1)
                                <span class="verification-text">(Verified)</span>
                            @else
                                <span class="verification-text">(Not Verified)</span>
                            @endif
                        @else
                            <i class="fas fa-user-graduate"></i> Learner
                        @endif
                    </p>
                </div>
            </div>

            <div class="profile-sections">
                <div class="about-section">
                    <h3><i class="fas fa-info-circle"></i> About Me</h3>
                    @if(!$profile->hide || auth()->check() && auth()->user()->id == $account->id)
                        <p>{{ $profile->profileinfo ?? 'No information available.' }}</p>
                    @else
                        <p class="text-muted">This information is hidden by the user</p>
                    @endif
                </div>

                <div class="contact-section">
                    <h3><i class="fas fa-envelope"></i> Contact Information</h3>
                    <div class="contact-details">
                        @if(!$profile->hide || auth()->check() && auth()->user()->id == $account->id)
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $account->email }}</p>
                        @else
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong> <span class="text-muted">Hidden</span></p>
                        @endif
                        
                        @if($account->account_type === 'instructor' && $instructor->workhours)
                            <p><strong>Work Hours:</strong> {{ $instructor->workhours }}</p>
                        @endif
                    </div>
                </div>

                @if($account->account_type === 'instructor')

                @endif
            </div>

            <!-- Rating Section -->
            @if (auth()->check() && !in_array(auth()->user()->account_type, ['guest', 'admin']) && auth()->user()->account_type !== 'instructor' && $account->account_type === 'instructor' && $instructor->confirmation == 1)
                <div class="rating-section mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-star text-warning me-2"></i>
                            <span>Rate This Instructor</span>
                        </h3>
                        <div class="average-rating">
                            <span id="average-rating-value" class="display-5 fw-bold text-primary">0.0</span>
                            <span class="text-muted">/ 5.0</span>
                        </div>
                    </div>
                    <div class="rating" data-user-rating="0">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}">&#9733;</span>
                        @endfor
                    </div>
                </div>
                <style>
                    .average-rating {
                        background: rgba(13, 110, 253, 0.1);
                        padding: 0.5rem 1rem;
                        border-radius: 20px;
                        display: inline-flex;
                        align-items: center;
                    }
                    .average-rating span:first-child {
                        margin-right: 0.25rem;
                    }
                    .rating-section .star {
                        cursor: pointer;
                        font-size: 1.8rem;
                        margin-right: 0.5rem;
                        color: #dee2e6;
                        transition: color 0.2s;
                    }
                    .rating-section .star.selected,
                    .rating-section .star.hover {
                        color: #ffc107;
                    }
                </style>
            @endif

            <!-- Courses Section -->
            @if($account->account_type === 'instructor' && $instructor->confirmation == 1)
                <div class="courses-section mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="fas fa-book-open"></i> Instructor Courses <span class="badge bg-primary">{{ $instructor->courses->count() ?? '0' }}</span></h3>
                        <a href="{{ route('instructor.courses', $account->id) }}" class="btn btn-primary">
                            View All Courses <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    
                    <div class="row">
                        @php
                        $courses = \App\Models\Course::where('instructor_id', $account->instructor_id)
                                    ->orderBy('id', 'desc')
                                    ->take(3)
                                    ->get();
                    @endphp
                        
                        @if($courses->count() > 0)
                            @foreach($courses as $course)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <img src="{{ $course->photo ? asset('storage/' . $course->photo) : asset('images/noimage.jpg') }}" 
                                        class="card-img-top" alt="{{ $course->title }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $course->title }}</h5>
                                            <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <a href="{{ route('course.show', $course->id) }}" class="btn btn-outline-primary btn-sm w-100">
                                                View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    This instructor hasn't published any courses yet.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Rating functionality
            const stars = $('.rating .star');
            const profileId = {{ $profile_id }};

            const updateStars = (rating) => {
                stars.each(function() {
                    $(this).toggleClass('selected', $(this).data('value') <= rating);
                });
            };

            // Get user's rating
            $.get(`/profile/${profileId}/rating`, function(response) {
                if (response.rating) {
                    updateStars(response.rating);
                    $('.rating').data('user-rating', response.rating);
                }
            });

            // Submit rating
            stars.on('click', function() {
                const rating = $(this).data('value');
                $.post(`/profile/${profileId}/rate`, {
                    rating: rating,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, function(response) {
                    if (response.success) {
                        updateStars(rating);
                        $('.rating').data('user-rating', rating);
                        // Update average rating display
                        $('#average-rating-value').text(response.average_rating);
                    } else {
                        alert('Failed to submit rating. Please try again.');
                    }
                });
            });

            // Get average rating
            $.get(`/profile/${profileId}/average-rating`, function(response) {
                if (response.success) {
                    $('#average-rating-value').text(response.average_rating);
                } else {
                    $('#average-rating-value').text('N/A');
                }
            });
        });
    </script>
</body>

</html>