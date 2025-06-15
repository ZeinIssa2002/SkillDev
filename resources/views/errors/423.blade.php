<!DOCTYPE html>
<html>
<head>
    <title>Guest Account Restricted</title>
    <style>
        body { 
            text-align: center; 
            padding: 50px; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            line-height: 1.6;
        }
        .error-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #ffc107; 
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .error-message {
            font-size: 1.2rem;
            color: #495057;
            margin-bottom: 30px;
            padding: 15px;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
            font-weight: bold;
            margin: 10px;
        }
        .btn-outline {
            background-color: transparent;
            border: 2px solid #007bff;
            color: #007bff;
        }
        .btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            color: white;
        }
        .btn-outline:hover {
            background-color: #007bff;
        }
        .action-buttons {
            margin-top: 25px;
        }
    </style>
    <link href="{{ asset('css/fontawesome-free-6.0.0-web/css/all.min.css') }}" rel="stylesheet">
</head>
<body>
    <div class="error-container">
        <h1><i class="fas fa-user-lock me-2"></i>Guest Account Restricted</h1>
        <div class="error-message">
            <i class="fas fa-info-circle me-2"></i>
            {{ $exception->getMessage() ?: 'Your guest account has limited access. Please register for full features.' }}
        </div>
        
        <div class="action-buttons">
            <a href="{{ route('register') }}" class="btn">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="{{ url('/homepage') }}" class="text-muted" style="color: #6c757d;">
                <i class="fas fa-arrow-left me-1"></i>Return to Homepage
            </a>
        </div>
    </div>
</body>
</html>