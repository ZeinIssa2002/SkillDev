
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
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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


        /* Main Content styles only */
        .main-content {
            margin-left: 270px;
            padding: 40px 20px;
        }

        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .profile-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--dark-color);
            font-weight: 700;
            text-decoration: none;
        }

        .profile-header p {
            font-size: 1.1rem;
            color: var(--gray-color);
            text-decoration: none;
        }

        .profile-header::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            margin: 20px auto 0;
            border-radius: 2px;
        }

        .profile-content {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .profile-info {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-picture {
            position: relative;
            margin-bottom: 25px;
            text-align: center;
        }

        .profile-picture img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            transition: var(--transition);
        }

        .profile-picture:hover img {
            transform: scale(1.03);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .profile-actions {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .profile-actions button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .profile-actions button.upload {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .profile-actions button.upload:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
        }

        .profile-actions button.delete {
            background: var(--danger-color);
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .profile-actions button.delete:hover {
            background: var(--danger-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        .profile-details {
            flex: 2;
            min-width: 300px;
        }

        .profile-details h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--dark-color);
            font-weight: 700;
            position: relative;
            display: inline-block;
            text-decoration: none;
        }

        .profile-details h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }

        .profile-details p {
            font-size: 1.1rem;
            color: var(--dark-color);
            margin-bottom: 15px;
            line-height: 1.7;
            text-decoration: none;
        }

        .profile-details strong {
            color: var(--dark-color);
            font-weight: 600;
            text-decoration: none;
        }

        .about-section {
            margin-top: 40px;
            background: #f9f9f9;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--gray-light);
        }

        .about-section h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .about-section h3 i {
            color: var(--primary-color);
            text-decoration: none;
        }

        .about-section .bio-content {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .about-section .bio-content p {
            font-size: 1.1rem;
            color: var(--dark-color);
            line-height: 1.8;
            margin: 0;
            flex-grow: 1;
            text-decoration: none;
        }

        .about-section .bio-content form {
            flex-shrink: 0;
        }

        .about-section .bio-content button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            transition: var(--transition);
            color: var(--danger-color);
            font-size: 1.2rem;
            text-decoration: none;
        }

        .about-section .bio-content button:hover {
            transform: scale(1.1);
        }

        .about-section .bio-content button:active {
            transform: scale(0.9);
        }

        .rating-section {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            margin-top: 30px;
            background: rgba(79, 70, 229, 0.05);
            padding: 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
            flex-wrap: wrap;
        }

        .rating-section h3 {
            font-size: 1.2rem;
            margin: 0;
            color: var(--dark-color);
            text-decoration: none;
            flex-basis: 100%;
            margin-bottom: 15px;
        }

        .rating-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: var(--primary-color);
            background-image: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: bold;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .rating-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
        }
        
        .rating-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .rating-count {
            font-size: 0.95rem;
            color: var(--gray-color);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .rating-count i {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .profile-content {
                flex-direction: column;
                align-items: center;
            }
            
            .profile-details {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            
            .profile-section {
                padding: 25px;
            }
        }

        @media (max-width: 576px) {
            .profile-section {
                padding: 20px;
            }
            
            .profile-header h1 {
                font-size: 2rem;
            }
            
            .profile-details h2 {
                font-size: 1.5rem;
            }
            
            .profile-picture img {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>

<body>

    @include('layouts.navbar')
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

    @include('layouts.profile-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="profile-section">
            <div class="profile-header">
                <h1>My Profile</h1>
                <p>Manage your personal information and preferences</p>
            </div>
            <div class="profile-content">
                <div class="profile-info">
                    <div class="profile-picture">
                        <img src="{{ $profile->photo ? asset('storage/' . $profile->photo) : asset('images/noimageprofile.jpg') }}" alt="Profile Picture">
                    </div>
                    <div class="profile-actions">
                        <form action="{{ route('profile.update', $profile->profile_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="file" name="photo" id="photo" style="display: none;" onchange="this.form.submit();">
                            <button type="button" class="upload" onclick="document.getElementById('photo').click();">
                                <i class="fas fa-upload"></i> Upload Photo
                            </button>
                        </form>
                        @if ($profile->photo)
                            <form id="deletePhotoForm" action="{{ route('profile.deletePhoto', $profile->profile_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete" onclick="confirmDeletePhoto()">
                                    <i class="fas fa-trash"></i> Delete Photo
                                </button>
                            </form>
                            
                            <script>
                                function confirmDeletePhoto() {
                                    Swal.fire({
                                        title: 'Delete Profile Picture',
                                        text: 'Are you sure you want to delete your profile picture? This action cannot be undone.',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#ef4444',
                                        cancelButtonColor: '#94a3b8',
                                        confirmButtonText: 'Yes, delete it!',
                                        cancelButtonText: 'Cancel',
                                        reverseButtons: true,
                                        customClass: {
                                            confirmButton: 'swal2-confirm',
                                            cancelButton: 'swal2-cancel'
                                        }
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('deletePhotoForm').submit();
                                        }
                                    });
                                }
                            </script>
                            
                            <style>
                                .swal2-popup {
                                    font-family: 'Roboto', sans-serif;
                                    border-radius: 12px;
                                }
                                .swal2-title {
                                    font-weight: 600;
                                    color: var(--dark-color);
                                }
                                .swal2-html-container {
                                    color: #64748b;
                                }
                                .swal2-confirm {
                                    background-color: var(--danger-color) !important;
                                    transition: var(--transition);
                                }
                                .swal2-confirm:hover {
                                    background-color: var(--danger-hover) !important;
                                }
                                .swal2-cancel {
                                    background-color: var(--gray-light) !important;
                                    color: var(--dark-color) !important;
                                    transition: var(--transition);
                                }
                                .swal2-cancel:hover {
                                    background-color: var(--gray-color) !important;
                                }
                            </style>
                        @endif
                        <form id="toggleHideForm" action="{{ route('profile.toggleHide', $profile->profile_id) }}" method="POST">
                            @csrf
                            <button type="button" class="{{ $profile->hide ? 'btn-success' : 'btn-warning' }}" onclick="confirmToggleHide({{ $profile->hide ? 'true' : 'false' }})">
                                <i class="fas fa-eye{{ $profile->hide ? '-slash' : '' }}"></i>
                                {{ $profile->hide ? 'Show Private Info' : 'Hide Private Info' }}
                            </button>
                        </form>
                        
                        <script>
                            function confirmToggleHide(isHidden) {
                                const action = isHidden ? 'show' : 'hide';
                                const title = isHidden ? 'Show Private Information' : 'Hide Private Information';
                                const text = isHidden 
                                    ? 'Are you sure you want to make your private information visible to others?'
                                    : 'Are you sure you want to hide your private information from others?';
                                const confirmText = isHidden ? 'Yes, show it' : 'Yes, hide it';
                                
                                Swal.fire({
                                    title: title,
                                    text: text,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: isHidden ? 'var(--success-color)' : 'var(--warning-color)',
                                    cancelButtonColor: 'var(--gray-color)',
                                    confirmButtonText: confirmText,
                                    cancelButtonText: 'Cancel',
                                    reverseButtons: true,
                                    customClass: {
                                        confirmButton: 'swal2-confirm',
                                        cancelButton: 'swal2-cancel'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('toggleHideForm').submit();
                                    }
                                });
                            }
                            
                            // Style the confirmation button based on action
                            document.addEventListener('DOMContentLoaded', function() {
                                const style = document.createElement('style');
                                style.textContent = `
                                    .swal2-warning .swal2-confirm {
                                        background-color: var(--warning-color) !important;
                                    }
                                    .swal2-warning .swal2-confirm:hover {
                                        background-color: var(--warning-hover) !important;
                                    }
                                    .swal2-question .swal2-confirm {
                                        background-color: var(--success-color) !important;
                                    }
                                    .swal2-question .swal2-confirm:hover {
                                        background-color: var(--success-hover) !important;
                                    }
                                `;
                                document.head.appendChild(style);
                            });
                        </script>
                    </div>
                </div>
                <div class="profile-details">
                    <h2>{{ $profile->profilename ?? ($account->username ?? 'No username available') }}</h2>
                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> {{ $account->email }}</p>
                    <p><strong><i class="fas fa-user-tag"></i> Account Type:</strong> {{ ucfirst($account->account_type) }}</p>
                    
                    <div class="about-section">
                        <h3><i class="fas fa-info-circle"></i> About Me</h3>
                        <div class="bio-content">
                            <p>{{ $profile->profileinfo ?? 'No information available. You can add information about yourself in the profile settings.' }}</p>
                            @if (!empty($profile->profileinfo))
                                <form id="deleteBioForm" method="POST" action="{{ route('profileinfo.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-bio" title="Delete Bio" onclick="confirmDeleteBio()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                
                                <script>
                                    function confirmDeleteBio() {
                                        Swal.fire({
                                            title: 'Delete Bio',
                                            text: 'Are you sure you want to delete your bio? This action cannot be undone.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#94a3b8',
                                            confirmButtonText: 'Yes, delete it!',
                                            cancelButtonText: 'Cancel',
                                            reverseButtons: true,
                                            customClass: {
                                                confirmButton: 'swal2-confirm',
                                                cancelButton: 'swal2-cancel'
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('deleteBioForm').submit();
                                            }
                                        });
                                    }
                                </script>
                                
                                <style>
                                    .delete-bio {
                                        background: none;
                                        border: none;
                                        color: var(--danger-color);
                                        cursor: pointer;
                                        padding: 5px 10px;
                                        border-radius: 4px;
                                        transition: var(--transition);
                                        font-size: 14px;
                                        margin-left: 10px;
                                    }
                                    .delete-bio:hover {
                                        background-color: #fee2e2;
                                        color: var(--danger-hover);
                                    }
                                </style>
                            @endif
                        </div>
                    </div>
                    
                    @if (auth()->check() && auth()->user()->account_type === 'instructor')
                        <div class="rating-section">
                            <h3><i class="fas fa-star"></i> Instructor Rating</h3>
                            <div class="rating-circle" title="{{ isset($instructor) && $instructor->rating > 0 ? 'Based on student reviews' : 'No ratings yet' }}">
                                @if(isset($instructor))
                                    {{ number_format($instructor->rating, 1) }}
                                @else
                                    0.0
                                @endif
                            </div>
                            <div class="rating-info">
                                @if(isset($instructor) && $instructor->rating > 0)
                                    <span class="rating-count"><i class="fas fa-users"></i> Rated by students</span>
                                @else
                                    <span class="rating-count">No ratings yet</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/profile/profile.js') }}"></script>
</body>

</html>