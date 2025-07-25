<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        .guest-option {
            margin: 1.5rem 0;
            text-align: center;
        }
        .divider {
            display: flex;
            align-items: center;
            margin: 1rem 0;
        }
        .divider-line {
            flex: 1;
            border: none;
            height: 1px;
            background-color: #e0e0e0;
        }
        .divider-text {
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.875rem;
        }
        .guest-btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .guest-btn:hover {
            background-color: #e5e7eb;
        }
        .guest-notice {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <form class="form" id="loginForm" action="{{ route('login') }}" method="POST">
        @csrf
        <p class="title">Login</p>
        <p class="message">Welcome back! Please login to your account.</p>

        @if($errors->any())
            <div id="errorMessages" class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <label>
            <span>Email</span>
            <input class="input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </label>

        <label>
            <span>Password</span>
            <input class="input" type="password" name="password" placeholder="Password" maxlength="40" required>
        </label>
        <div class="guest-option">
            <div class="divider">
                <hr class="divider-line">
                <span class="divider-text">OR</span>
                <hr class="divider-line">
            </div>
            
            <button type="button" onclick="window.location.href='{{ route('login.guest') }}'" class="guest-btn">
                Continue as Guest
            </button>
            
            <p class="guest-notice">
                Guest account has limited features and is temporary
            </p>
        </div>
        <button type="submit" class="submit">Login</button>

        <p class="signup">Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
    </form>
</body>
</html>