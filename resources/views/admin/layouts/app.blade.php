<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="{{ asset('js/libs/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}">
    <!-- Font Awesome all.min.css includes all icon styles and references webfonts. Make sure webfonts directory is present. -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .sidebar {
            background-color: #2c3e50;
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px 20px;
            transition: all 0.3s;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar ul li.active {
            background-color: #4361ee;
        }
        .sidebar ul li:hover:not(.active) {
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #4361ee;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .logout-btn:hover {
            background-color: rgba(220, 53, 69, 0.8) !important;
        }
        
        /* Red dot notification */
        .notification-dot {
            width: 10px;
            height: 10px;
            background-color: #ff3b30;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }
        
        @yield('styles')
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // تحقق من وجود رسائل غير مقروءة
        document.addEventListener('DOMContentLoaded', function() {
            // التحقق فقط إذا كان المستخدم مسجل الدخول
            @auth
                checkUnreadMessages();
                
                // إعداد Pusher للاستماع إلى الرسائل الجديدة
                const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                    cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                });
                
                // الاشتراك في قناة الدردشة
                const channel = pusher.subscribe('chat-channel');
                
                // الاستماع إلى الأحداث الجديدة
                channel.bind('new-message', function(data) {
                    // إذا كانت الرسالة موجهة للمستخدم الحالي
                    if (data.message.receiver_id === {{ auth()->id() }}) {
                        // إظهار النقطة الحمراء في الشريط الجانبي
                        document.getElementById('sidebarChatNotification').style.display = 'inline-block';
                    }
                });
            @endauth
        });
        
        // دالة للتحقق من وجود رسائل غير مقروءة
        function checkUnreadMessages() {
            fetch("{{ route('chat.all.unread.counts') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const counts = data.unread_counts;
                        let totalUnread = 0;
                        
                        // حساب إجمالي الرسائل غير المقروءة
                        for (const userId in counts) {
                            totalUnread += counts[userId];
                        }
                        
                        // إظهار أو إخفاء النقطة الحمراء بناءً على وجود رسائل غير مقروءة
                        if (totalUnread > 0) {
                            document.getElementById('sidebarChatNotification').style.display = 'inline-block';
                        } else {
                            document.getElementById('sidebarChatNotification').style.display = 'none';
                        }
                    }
                })
                .catch(error => console.error('Error checking unread messages:', error));
        }
        
        // التحقق من الرسائل غير المقروءة كل دقيقة
        setInterval(checkUnreadMessages, 60000);
    </script>
    @yield('scripts')
</body>
</html>
