<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Hours | Instructor Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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


        /* Main Content Styles */
        .main-content {
            margin-left: 270px;
            padding: 40px 20px;
        }

        /* Work Hours Specific Styles */
        .workhours-form {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .workhours-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .workhours-header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .workhours-header p {
            font-size: 1rem;
            color: var(--gray-color);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            transition: var(--transition);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .current-hours {
            background-color: var(--light-color);
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-color);
        }

        .current-hours h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .current-hours p {
            font-size: 1.1rem;
            margin-bottom: 0;
            color: var(--dark-color);
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

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .empty-state p {
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        @media (max-width: 576px) {
            .container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .user-info {
                justify-content: flex-end;
            }
            
            .workhours-form {
                padding: 15px;
            }
            
            .current-hours {
                padding: 1rem;
            }
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
        ">
            {{ session('error') }}
        </div>
    @endif

    @include('layouts.navbar')

    @include('layouts.profile-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="workhours-form">
            <div class="workhours-header">
                <h1>Manage Your Work Hours</h1>
                <p>Set or update your available working hours for students</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($instructor->workhours)
                <div class="current-hours">
                    <h3>Current Work Hours:</h3>
                    <p>{{ $instructor->workhours }}</p>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="far fa-clock"></i>
                    </div>
                    <h3>No work hours set</h3>
                    <p>You haven't set your work hours yet. Please add them below.</p>
                </div>
            @endif

            <form action="{{ route('instructor.workhours.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="workhours" class="form-label">Your Work Hours</label>
                    <input type="text" class="form-control" id="workhours" name="workhours" 
                           value="{{ old('workhours', $instructor->workhours ?? '') }}" 
                           placeholder="Example: Monday-Friday 9am-5pm, Weekends by appointment" required>
                    <small class="text-muted">Describe your typical availability for students</small>
                </div>
                <button type="submit" class="btn btn-primary">Update Work Hours</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/profile/profile.js') }}"></script>
</body>

</html>