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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="{{ asset('css/Homepg.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <style>
        /* Chat Widget Styles */
        .chat-widget {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 350px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            flex-direction: column;
            overflow: hidden;
        }
        
        .chat-header {
            background-color: #4f46e5;
            color: white;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }
        
        .chat-title {
            font-weight: 600;
        }
        
        .chat-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        .chat-body {
            flex: 1;
            height: 300px;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .chat-message {
            max-width: 80%;
            padding: 8px 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .message-sent {
            align-self: flex-end;
            background-color: #4f46e5;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .message-received {
            align-self: flex-start;
            background-color: #f1f1f1;
            color: #333;
            border-bottom-left-radius: 4px;
        }
        
        .message-time {
            font-size: 0.7rem;
            color: #777;
            margin-top: 3px;
            text-align: right;
        }
        
        .chat-input-container {
            padding: 10px;
            border-top: 1px solid #eee;
        }
        
        .chat-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            resize: none;
            outline: none;
        }
        
        .chat-send-btn {
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: auto;
            margin-top: 5px;
        }
        
        .chat-launcher {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background-color: #000000;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 10px rgb(0, 0, 0);
            z-index: 999;
        }
        
        .chat-launcher i {
            font-size: 1.5rem;
        }
        
        .admin-selector {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .admin-selector select {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
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
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            padding: 6rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center;
            background-size: cover;
            opacity: 0.15;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.05em;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-button {
            background-color: white;
            color: var(--primary-color);
            border: none;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: var(--shadow-md);
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background-color: #f8fafc;
        }

        /* Features Section */
        .features-section {
            padding: 5rem 1rem;
            background-color: white;
        }

        .features-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .features-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: var(--dark-color);
            position: relative;
        }

        .features-section h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            margin: 1rem auto 0;
            border-radius: 2px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            background: rgba(79, 70, 229, 0.1);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            display: inline-block;
            transition: var(--transition);
        }

        .feature:hover i {
            background: rgba(79, 70, 229, 0.2);
            transform: scale(1.1);
        }

        .feature h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--dark-color);
        }

        .feature p {
            color: var(--gray-color);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* Feedback Section */
        .feedback-section {
            background-color: #f8fafc;
            padding: 5rem 1rem;
        }

        .feedback-content {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .feedback-section h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            color: var(--dark-color);
        }

        .success-message {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .feedback-form textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: var(--transition);
        }

        .feedback-form textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }

        .feedback-form button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            width: 100%;
        }

        .feedback-form button:hover {
            background-color: var(--primary-hover);
        }

        .rating-container {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .rating, .average-rating {
            flex: 1;
            min-width: 300px;
        }

        .rating h3, .average-rating h2 {
            text-align: center;
            margin-bottom: 1rem;
        }

        .stars {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .star {
            font-size: 2rem;
            color: var(--gray-light);
            cursor: pointer;
            transition: var(--transition);
        }

        .star:hover, .star.active {
            color: var(--secondary-color);
        }

        .average-rating-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 auto;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.3);
        }

        /* Footer */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 1rem;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--secondary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .hero-section p {
                font-size: 1.1rem;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .rating-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <section class="hero-section">
        <div class="hero-content">
            <h1>Skill Dev</h1>
            <p>Enhance your skills with our comprehensive courses and resources designed by industry experts to help you succeed in your career.</p>
            <button class="cta-button" onclick="window.location.href='/course'">Get Started</button>
        </div>
    </section>

    <section class="features-section">
        <div class="features-content">
            <h2>Why Choose Skill Dev?</h2>
            <div class="features-grid">
                <div class="feature">
                    <i class="fas fa-book-open"></i>
                    <h3>Comprehensive Courses</h3>
                    <p>Access in-depth courses covering the latest technologies and industry best practices with hands-on projects.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Expert Instructors</h3>
                    <p>Learn from certified professionals with real-world experience in their respective fields.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-laptop-code"></i>
                    <h3>Interactive Learning</h3>
                    <p>Engage with interactive coding exercises, quizzes, and real-world projects to reinforce learning.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-certificate"></i>
                    <h3>Career Advancement</h3>
                    <p>Earn certificates to showcase your skills and boost your professional profile.</p>
                </div>
            </div>
        </div>
    </section>
    @if(auth()->check() && auth()->user()->account_type !== 'guest' && auth()->user()->account_type !== 'admin')  {
    <section class="feedback-section">
        <div class="feedback-content">
            <h2>We Value Your Feedback</h2>
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif
            <form action="{{ route('feedback.submit') }}" method="POST" class="feedback-form">
                @csrf
                <textarea id="feedbackinfo" name="feedbackinfo" rows="5" placeholder="Share your thoughts, suggestions, or any issues you've encountered..." required></textarea>
                <button type="submit">Submit Feedback</button>
            </form>
            <div class="rating-container">
                <div class="rating">
                    <h3>Rate Your Experience</h3>
                    <div class="stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}">&#9733;</span>
                        @endfor
                    </div>
                </div>
                <div class="average-rating">
                    <h2>Average Rating</h2>
                    <div class="average-rating-circle" id="average-rating-value">4.5</div>
                </div>
            </div>
        </div>
    </section>
    }
    @endif
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Us</a>
                <a href="#">About Us</a>
                <a href="#">Careers</a>
            </div>
            <p>&copy; 2025 Skill Dev. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/homepg.js') }}"></script>
    <script>
        // Star rating functionality
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const stars = document.querySelectorAll('.star');
                
                stars.forEach((s, index) => {
                    if (index < value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
                
                // Here you would typically send this rating to your backend
                // For now, we'll just update the average rating display
                const newAverage = (parseFloat(document.getElementById('average-rating-value').textContent) + parseInt(value)) / 2;
                document.getElementById('average-rating-value').textContent = newAverage.toFixed(1);
            });
        });
    </script>
                        @if (auth()->check() && auth()->user()->account_type != 'admin')
<div class="chat-launcher" id="chatLauncher">
    <i class="fas fa-comment-dots"></i>
</div>

<div class="chat-widget" id="chatWidget">
    <div class="chat-header" id="chatHeader">
        <div class="chat-title">Chat with Admin</div>
        <button class="chat-toggle" id="chatToggle">
            <i class="fas fa-minus"></i>
        </button>
    </div>
    
    <div class="admin-selector">
        <select id="adminSelector">
            <option value="">Select an Admin</option>
            @foreach(\App\Models\Account::where('account_type', 'admin')->get() as $admin)
                <option value="{{ $admin->id }}">{{ $admin->username }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="chat-body" id="chatBody">
        <!-- Messages will appear here -->
        <div class="chat-message message-received">
            Please select an admin to start chatting
        </div>
    </div>
    
    <div class="chat-input-container">
        <textarea class="chat-input" id="chatInput" placeholder="Type your message..." rows="1" disabled></textarea>
        <button class="chat-send-btn" id="chatSendBtn" disabled>
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>
@endif
<script>
    $(document).ready(function() {
        // Initialize Pusher
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });
        
        let selectedAdminId = null;
        
        // Toggle chat widget
        $('#chatLauncher').click(function() {
            $('#chatWidget').toggle();
            if ($('#chatWidget').is(':visible')) {
                scrollToBottom();
            }
        });
        
        $('#chatToggle').click(function() {
            $('#chatWidget').hide();
        });
        
        // Admin selection
        $('#adminSelector').change(function() {
            selectedAdminId = $(this).val();
            
            if (selectedAdminId) {
                // Enable input
                $('#chatInput').prop('disabled', false);
                $('#chatSendBtn').prop('disabled', false);
                
                // Clear previous messages
                $('#chatBody').html('<div class="chat-message message-received">Loading messages...</div>');
                
                // Load previous messages
                loadMessages(selectedAdminId);
                
                // Subscribe to channel
                subscribeToChannel();
            } else {
                $('#chatInput').prop('disabled', true);
                $('#chatSendBtn').prop('disabled', true);
                $('#chatBody').html('<div class="chat-message message-received">Please select an admin to start chatting</div>');
            }
        });
        
        // Send message
        $('#chatSendBtn').click(sendMessage);
        $('#chatInput').keypress(function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        function loadMessages(adminId) {
            $.ajax({
                url: '/chat/messages/' + adminId,
                type: 'GET',
                success: function(response) {
                    $('#chatBody').html('');
                    
                    response.messages.forEach(message => {
                        const isSent = message.sender_id == {{ auth()->id() }};
                        appendMessage(message, isSent);
                    });
                    
                    scrollToBottom();
                },
                error: function(xhr) {
                    console.error('Error loading messages:', xhr);
                    $('#chatBody').html('<div class="chat-message message-received">Error loading messages</div>');
                }
            });
        }
        
        function subscribeToChannel() {
            // Subscribe to the general chat channel
            const channel = pusher.subscribe('chat-channel');
            
            // Listen for new messages
            channel.bind('new-message', function(data) {
                if ((data.message.sender_id == selectedAdminId && data.message.receiver_id == {{ auth()->id() }}) || 
                    (data.message.sender_id == {{ auth()->id() }} && data.message.receiver_id == selectedAdminId)) {
                    const isSent = data.message.sender_id == {{ auth()->id() }};
                    appendMessage(data.message, isSent);
                }
            });
            
            // Listen for message updates
            channel.bind('message-updated', function(data) {
                if ((data.message.sender_id == selectedAdminId && data.message.receiver_id == {{ auth()->id() }}) || 
                    (data.message.sender_id == {{ auth()->id() }} && data.message.receiver_id == selectedAdminId)) {
                    updateMessage(data.message);
                }
            });
            
            // Listen for message deletion
            channel.bind('message-deleted', function(data) {
                $(`#message-${data.message_id}`).remove();
            });
        }
        
        function sendMessage() {
            const message = $('#chatInput').val().trim();
            
            if (!message || !selectedAdminId) return;
            
            $.ajax({
                url: '/chat/send',
                type: 'POST',
                data: {
                    receiver_id: selectedAdminId,
                    message: message,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#chatInput').val('');
                    adjustTextareaHeight();
                    // No need to append message here, Pusher will handle it
                },
                error: function(xhr) {
                    console.error('Error sending message:', xhr);
                    alert('Failed to send message');
                }
            });
        }
        
        function appendMessage(message, isSent) {
            const messageClass = isSent ? 'message-sent' : 'message-received';
            const timeString = new Date(message.created_at).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // تم إزالة مؤشر التعديل
            const editedIndicator = '';
            
            const messageHtml = `
                <div class="chat-message ${messageClass}" id="message-${message.id}">
                    ${message.message}${editedIndicator}
                    <div class="message-time">${timeString}</div>
                </div>
            `;
            
            $('#chatBody').append(messageHtml);
            scrollToBottom();
        }
        
        function updateMessage(message) {
            const messageElement = $(`#message-${message.id}`);
            if (messageElement.length) {
                // تم إزالة مؤشر التعديل
                const editedIndicator = '';
                
                messageElement.html(`
                    ${message.message}${editedIndicator}
                    <div class="message-time">${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</div>
                `);
            }
        }
        
        function scrollToBottom() {
            const chatBody = $('#chatBody')[0];
            chatBody.scrollTop = chatBody.scrollHeight;
        }
        
        function adjustTextareaHeight() {
            const textarea = $('#chatInput');
            textarea.height('auto');
            textarea.height(Math.min(textarea[0].scrollHeight, 100));
        }
        
        $('#chatInput').on('input', adjustTextareaHeight);
    });
</script>

<script type="module">
  import Chatbot from "https://cdn.jsdelivr.net/npm/@denserai/embed-chat@1/dist/web.min.js";
  Chatbot.init({
    chatbotId: "d3809798-8646-4556-8c88-56fb65eced9d",
  });
</script>  
  
</body>

</html>