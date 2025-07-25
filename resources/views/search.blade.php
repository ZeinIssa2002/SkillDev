<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search Results</title>
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
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
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--dark-color);
            line-height: 1.5;
            background: #f4f4f9;
        }
        
        .header-content {
            background-color: white;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
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

        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }
        
        .toast {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .toast-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .toast-error {
            background-color: var(--danger-color);
            color: white;
        }
        
        .toast-info {
            background-color: var(--primary-color);
            color: white;
        }

        /* Search Page Styles */
        .search-section {
            max-width: 1200px;
            margin: 40px auto 30px;
            padding: 0 20px;
        }

        .search-section h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .results-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .results-section h3 {
            font-size: 22px;
            margin: 30px 0 20px;
            color: #2c3e50;
        }

        /* Course Cards Styles */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
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



        /* People Cards Styles - Improved */
        .people-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .person-card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 20px;
            background-color: #fff;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            text-decoration: none !important;
            color: inherit;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .person-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .person-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            flex-shrink: 0;
            border: 3px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .person-card:hover .person-image {
            border-color: var(--primary-color);
        }

        .person-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .person-info {
            flex-grow: 1;
        }

        .person-name {
            font-weight: 600;
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .person-bio {
            font-size: 14px;
            color: #7f8c8d;
            line-height: 1.4;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 8px;
            font-weight: 500;
            background-color: #4CAF50;
            color: white;
        }

        .badge[data-type="instructor"] {
            background-color: #2196F3;
        }

        .badge[data-type="user"] {
            background-color: #FF9800;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            grid-column: 1 / -1;
        }

        .no-results i {
            font-size: 50px;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .no-results p {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .courses-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
            
            .course-image {
                height: 160px;
            }

            .people-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .courses-grid,
            .people-grid {
                grid-template-columns: 1fr;
            }

            .person-card {
                padding: 15px;
            }

            .person-image {
                width: 60px;
                height: 60px;
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

    <div class="toast-container">
        <!-- Toast notifications will be added here dynamically -->
    </div>

    <div class="main-content">
        <section class="search-section">
            <h2>Search Results</h2>
        </section>

        <section class="results-section">
            @if (isset($courses) && $courses->count() > 0)
                <h3>Courses for "{{ request('search') }}"</h3>
                <div class="courses-grid">
                    @foreach ($courses as $course)
                        <div class="course-card">
                            <a href="{{ route('course.show', $course->id) }}" class="course-image">
                                <img src="{{ asset($course->photo ? 'storage/' . $course->photo : 'images/noimage.jpg') }}" alt="Course Image">
                            </a>
                            <div class="course-info">
                                <div class="course-title">{{ $course->title }}</div>
                                <div class="course-preview">{{ $course->coursepreview }}</div>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

            @if (isset($accounts) && $accounts->count() > 0)
                <h3>People for "{{ request('search') }}"</h3>
                <div class="people-grid">
                    @foreach ($accounts as $account)
                        <a href="{{ route('profileshowdisplay', ['profile_id' => $account->profile->profile_id]) }}" class="person-card" style="text-decoration: none;">
                            <div class="person-image">
                                @if ($account->profile->photo)
                                    <img src="{{ asset('storage/' . $account->profile->photo) }}" alt="Profile Photo">
                                @else
                                    <img src="{{ asset('images/noimageprofile.jpg') }}" alt="Profile Photo">
                                @endif
                            </div>
                            <div class="person-info">
                                <div class="person-name">
                                    {{ $account->profile->profilename }}
                                    @if($account->account_type === 'instructor')
                                        <span class="badge" data-type="instructor">Instructor</span>
                                    @else
                                        <span class="badge" data-type="user">User</span>
                                    @endif
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ((!isset($courses) || $courses->count() == 0) && (!isset($accounts) || $accounts->count() == 0))
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>No results found for "{{ request('search') }}"</p>
                </div>
            @endif
        </section>
    </div>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script>
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.querySelector('.toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type} show`;
            toast.innerHTML = `
                <div class="toast-body d-flex justify-content-between align-items-center">
                    <span>${message}</span>
                    <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;
            toastContainer.appendChild(toast);
            
            // Remove toast after 5 seconds
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }


            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    icon.classList.remove('far', 'fa-square');
                    icon.classList.add('fas', 'fa-check-square');
                    element.classList.add('active');
                    element.style.pointerEvents = "none";
                    showToast('Application submitted successfully!', 'success');
                } else {
                    showToast(data.message || "An error occurred.", 'error');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("An error occurred.", 'error');
        // Check initial favorite and apply status
        document.addEventListener('DOMContentLoaded', () => {
            // No favorite/apply functionality needed
        });
    </script>
</body>

</html>