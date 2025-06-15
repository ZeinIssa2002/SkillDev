<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Course Cards</title>
    <link rel="stylesheet" href="{{ asset('css/course/insindex.css') }}">
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <link id="u-theme-google-font" rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
    <style>
        
        /* Floating Add Course Button */
        .floating-add-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--success-color), var(--success-hover));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 100;
            transition: var(--transition);
        }

        .floating-add-btn:hover {
            background: linear-gradient(135deg, var(--success-hover), var(--success-color));
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .floating-add-btn i {
            font-size: 24px;
        }

        .floating-add-btn .btn-text {
            display: none;
        }

        @media (min-width: 768px) {
            .floating-add-btn {
                width: auto;
                height: auto;
                padding: 15px 25px;
                border-radius: 30px;
                bottom: 40px;
                left: 40px;
            }

            .floating-add-btn .btn-text {
                display: inline;
                margin-left: 10px;
                font-size: 16px;
                font-weight: 500;
            }
        }


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
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            cursor: pointer;
            overflow: hidden;
            position: relative;
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
            justify-content: flex-end;
            align-items: center;
            margin-top: auto;
            padding: 0 15px 15px;
            gap: 10px;
        }

        .course-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: var(--transition);
            padding: 8px;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .course-actions button.edit-button {
            color: var(--secondary-color);
            background-color: rgba(245, 158, 11, 0.1);
        }

        .course-actions button.edit-button:hover {
            background-color: rgba(245, 158, 11, 0.2);
        }

        .course-actions button.delete-button {
            color: var(--danger-color);
            background-color: rgba(239, 68, 68, 0.1);
        }

        .course-actions button.delete-button:hover {
            background-color: rgba(239, 68, 68, 0.2);
        }

        /* Confirmation Modal */
        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            justify-content: center;
            align-items: center;
        }

        .confirmation-content {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            max-width: 400px;
            width: 90%;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .confirmation-buttons button {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
        }

        .confirmation-buttons .confirm-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
        }

        .confirmation-buttons .confirm-btn:hover {
            background-color: var(--danger-hover);
        }

        .confirmation-buttons .cancel-btn {
            background-color: var(--gray-light);
            color: var(--dark-color);
            border: none;
        }

        .confirmation-buttons .cancel-btn:hover {
            background-color: var(--gray-color);
            color: white;
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
            
            .header-content {
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        @media (max-width: 575px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 25px;
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
                <div id="successAlert" class="alert alert-success" style="
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
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                    <div style="flex-grow: 1; text-align: center;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" style="
                        background: none;
                        border: none;
                        font-size: 16px;
                        cursor: pointer;
                        color: #155724;
                        opacity: 0.7;
                    " onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <script>
                    // Auto remove success alert after 5 seconds
                    setTimeout(() => {
                        const successAlert = document.getElementById('successAlert');
                        if (successAlert) {
                            successAlert.remove();
                        }
                    }, 5000);
                </script>
            @endif
            
            @if(session('error'))
                <div id="errorAlert" class="alert alert-danger" style="
                    padding: 15px;
                    margin-bottom: 10px;
                    border: 1px solid #f5c6cb;
                    border-radius: 8px;
                    color: #721c24;
                    background-color: #f8d7da;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    width: 100%;
                    pointer-events: auto;
                    animation: fadeInDown 0.3s ease-out;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                ">
                    <div style="flex-grow: 1; text-align: center;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" style="
                        background: none;
                        border: none;
                        font-size: 16px;
                        cursor: pointer;
                        color: #721c24;
                        opacity: 0.7;
                    " onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <script>
                    // Auto remove error alert after 5 seconds
                    setTimeout(() => {
                        const errorAlert = document.getElementById('errorAlert');
                        if (errorAlert) {
                            errorAlert.remove();
                        }
                    }, 5000);
                </script>
            @endif
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-content">
            <h5>Confirm Deletion</h5>
            <p>Are you sure you want to delete this course? This action cannot be undone.</p>
            <div class="confirmation-buttons">
                <button class="cancel-btn" onclick="closeConfirmationModal()">Cancel</button>
                <button class="confirm-btn" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if ($courses->count() > 0)
            @foreach ($courses as $course)
                <div class="course-card">
                    <a href="{{ route('course.show', $course->id) }}" class="course-image">
                        <img src="{{ asset($course->photo ? 'storage/' . $course->photo : 'images/noimage.jpg') }}" alt="Course Image">
                    </a>
                    <div class="course-info">
                        <div class="course-title">{{ $course->title }}</div>
                        <div class="course-preview">{{ $course->coursepreview }}</div>
                    </div>
                    @if (Auth::check() && Auth::user()->account_type == 'instructor')
                        <div class="course-actions">
                            <button class="edit-button" onclick="editCourse(event, {{ $course->id }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="delete-button" data-course-id="{{ $course->id }}" onclick="showDeleteConfirmation({{ $course->id }}, '{{ $course->title }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                <i class="fas fa-book-open" style="font-size: 60px; color: #bdc3c7; margin-bottom: 20px;"></i>
                <h3>No Courses Found</h3>
                <p>You haven't created any courses yet. Click the button below to add your first course.</p>
                @if (Auth::check() && Auth::user()->account_type == 'instructor')
                    <button class="btn btn-primary" onclick="window.location.href='{{ route('course.create') }}'">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                @endif
            </div>
        @endif
    </div>

    @if (Auth::check() && Auth::user()->account_type == 'instructor')
        <div class="floating-add-btn" onclick="window.location.href='{{ route('course.create') }}'">
            <i class="fas fa-plus"></i>
            <span class="btn-text">Add Course</span>
        </div>
    @endif

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
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

        // Course edit function
        function editCourse(event, courseId) {
            event.preventDefault();
            event.stopPropagation();
            window.location.href = `/course/${courseId}/edit`;
        }

        // Delete confirmation modal
        let currentCourseToDelete = null;

        function showDeleteConfirmation(courseId, courseTitle) {
            currentCourseToDelete = courseId;
            const modal = document.getElementById('confirmationModal');
            const modalContent = modal.querySelector('.confirmation-content');
            
            // Update modal content with course title
            modalContent.querySelector('p').textContent = 
                `Are you sure you want to delete the course "${courseTitle}"? This action cannot be undone.`;
            
            modal.style.display = 'flex';
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').style.display = 'none';
            currentCourseToDelete = null;
        }

        // Initialize delete confirmation button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (currentCourseToDelete) {
                deleteCourse(currentCourseToDelete);
            }
        });

        // Course delete function
        function deleteCourse(courseId) {
            // Ensure courseId is valid and build the correct URL
            if (!courseId) {
                showToast('Invalid course ID.', 'error');
                return;
            }
            fetch(`/course/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Defensive: handle non-JSON errors gracefully
                if (!response.ok) {
                    return response.json().catch(() => ({ success: false, message: 'Server error or invalid response.' }));
                }
                return response.json();
            })
            .then(data => {
                closeConfirmationModal();
                if (data.success) {
                    showToast('Course deleted successfully!', 'success');
                    // Remove the course card from DOM
                    const courseCard = document.querySelector(`.course-card .delete-button[data-course-id="${courseId}"]`)?.closest('.course-card');
                    if (courseCard) {
                        courseCard.style.opacity = '0';
                        setTimeout(() => {
                            courseCard.remove();
                            // If no courses left, show empty state
                            if (document.querySelectorAll('.course-card').length === 0) {
                                showEmptyState();
                            }
                        }, 300);
                    }
                } else {
                    showToast(data.message || "Failed to delete course.", 'error');
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showToast("An error occurred while deleting the course.", 'error');
                closeConfirmationModal();
            });
        }

        // Show empty state if no courses
        function showEmptyState() {
            const mainContent = document.querySelector('.main-content');
            mainContent.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px;">
                    <i class="fas fa-book-open" style="font-size: 60px; color: #bdc3c7; margin-bottom: 20px;"></i>
                    <h3>No Courses Found</h3>
                    <p>You haven't created any courses yet. Click the button below to add your first course.</p>
                    <button class="btn btn-primary" onclick="window.location.href='{{ route('course.create') }}'">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                </div>
            `;
        }

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmationModal();
            }
        });

        // Initialize course cards with hover effects
        document.querySelectorAll('.course-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't navigate if clicking on action buttons
                if (e.target.closest('.course-actions')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>

<!-- إخفاء التنبيهات تلقائياً -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toasts = document.querySelectorAll('.toast');
        toasts.forEach(function(toast) {
            setTimeout(function() {
                toast.classList.remove('show');
            }, 5000); // إخفاء بعد 5 ثواني
        });

        // إضافة وظيفة زر الإغلاق
        var closeButtons = document.querySelectorAll('.btn-close');
        closeButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var toast = this.closest('.toast');
                toast.classList.remove('show');
            });
        });
    });
</script>
</body>
</html>