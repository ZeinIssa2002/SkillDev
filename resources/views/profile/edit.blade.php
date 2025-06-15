<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile and Privacy</title>
    <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <style>
 
        /* Main Content */
        .main-content {
            margin-left: 270px;
            padding: 40px 20px;
        }

        /* Edit Profile and Privacy Forms */
        .main {
            margin-top: -100px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f4f4f9;
        }

        .login, .register {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px;
        }

        .form {
            display: flex;
            flex-direction: column;
        }

        .form label {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .input {
            padding: 12px;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .input:focus {
            border-color: #3498db;
        }

        .input::placeholder {
            color: #999;
        }

        textarea.input {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #2980b9;
        }

        .error-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }

        .success-message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            text-align: center;
            font-size: 16px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .main {
                flex-direction: column;
            }

            .login, .register {
                width: 100%;
                margin: 10px 0;
            }
        }
                /* تنسيق رسالة النجاح */
                .success-message {
                    background-color: #4CAF50;
                    color: white;
                    padding: 15px;
                    margin-bottom: 20px;
                    text-align: center;
                    border-radius: 5px;
                    display: block;
                }
        
                /* تنسيق رسالة الفشل */
                .error-message {
                    background-color: #f44336;
                    color: white;
                    padding: 15px;
                    margin-bottom: 20px;
                    text-align: center;
                    border-radius: 5px;
                    display: block;
                }
        
                /* إخفاء الرسائل بعد 5 ثوانٍ */
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; }
                }
        
                .success-message, .error-message {
                    animation: fadeOut 5s forwards;
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
        "
        >
            {{ session('error') }}
        </div>
    @endif

    @include('layouts.navbar')

    @include('layouts.profile-sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="main">
            <!-- Edit Profile Form -->
            <div class="login">
                <form class="form" method="POST" action="{{ route('profile.updateProfile') }}">
                    @csrf
                    <label class="Edit" for="chk" aria-hidden="true">Edit Profile</label>
                    
                    <div class="input-group">
                        <label for="profilename">Username</label>
                        <input class="input login-input" type="text" name="profilename" id="profilename" placeholder="Enter your username" value="{{ old('profilename', $username) }}" required>
                    </div>
                    
                    @if(session('error'))
                        <div class="error-message">{{ session('error') }}</div>
                    @endif

                    <div class="input-group">
                        <label for="profileinfo">Bio</label>
                        <textarea class="input login-input" name="profileinfo" id="profileinfo" placeholder="Write something about yourself...">{{ old('profileinfo', $bio) }}</textarea>
                    </div>
                    
                    @error('profilename')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    @error('profileinfo')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <button type="submit">Confirm</button>
                </form>
            </div>

            <!-- Modify Privacy Settings Form -->
            <div class="register">
                <form class="form" method="POST" action="{{ route('profile.updateAccount') }}">
                    @csrf
                    <label for="chk" aria-hidden="true">Modify Privacy Settings</label>
                    
                    <div class="input-group">
                        <label for="new_email">New Email</label>
                        <input class="input register-input" type="email" name="new_email" id="new_email" placeholder="Enter new email" value="{{ old('new_email', $user->email) }}" required>
                    </div>

                    <div class="input-group">
                        <label for="old_password">Old Password</label>
                        <input class="input register-input" type="password" name="old_password" id="old_password" placeholder="Enter old password" required>
                    </div>

                    <div class="input-group">
                        <label for="new_password">New Password</label>
                        <input class="input register-input" type="password" name="new_password" id="new_password" placeholder="Enter new password">
                    </div>

                    <div class="input-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input class="input register-input" type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password">
                    </div>
                    
                    @error('new_email')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    @error('old_password')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                    @error('new_password')
                        <div style="color: red;">{{ $message }}</div>
                    @enderror
                
                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/profile/fav.js') }}"></script>
</body>

</html>