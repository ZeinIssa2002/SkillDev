<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friends</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
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

        /* Friends Page Specific Styles */
        .friends-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .friends-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .friends-header h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin: 0;
        }
        
        .friends-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
        
        .friend-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .friend-card:hover {
            transform: translateY(-5px);
        }
        
        .friend-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .friend-info {
            padding: 1.5rem;
        }
        
        .friend-name {
            font-size: 1.2rem;
            margin: 0 0 0.5rem 0;
            color: #2c3e50;
        }
        
        .friend-username {
            color: #7f8c8d;
            margin: 0 0 1rem 0;
            font-size: 0.9rem;
        }
        
        .friend-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-button {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: none;
            text-decoration: none;
            flex-grow: 1;
            justify-content: center;
        }
        
        .chat-button {
            background-color: #4CAF50;
            color: white;
        }
        
        .chat-button:hover {
            background-color: #3e8e41;
        }
        
        .remove-button {
            background-color: #e74c3c;
            color: white;
        }
        
        .remove-button:hover {
            background-color: #c0392b;
        }
        
        .no-friends {
            text-align: center;
            padding: 2rem;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .no-friends i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-friends p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        /* New styles for profile links */
        .friend-name-link {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .friend-name-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .friend-avatar-link {
            display: block;
            width: 100%;
            height: 200px;
        }
        
        @media (max-width: 768px) {
            .friends-grid {
                grid-template-columns: 1fr;
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
                    <li><a class="dropdown-item text-danger" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                @endif
                </ul>
            </div>
        </div>
    </header>

    <div class="friends-container">
        <div class="friends-header">
            <h1>My Friends</h1>
            <span>{{ $friends->count() }} friends</span>
        </div>
        
        @if($friends->count() > 0)
            <div class="friends-grid">
                @foreach($friends as $friend)
                    <div class="friend-card">
                        <a href="{{ route('profileshowdisplay', ['profile_id' => $friend->profile->profile_id]) }}" class="person-card">
                            <img src="{{ $friend->profile->photo ? asset('storage/' . $friend->profile->photo) : asset('images/noimageprofile.jpg') }}" 
                                 alt="{{ $friend->username }}" class="friend-avatar">
                        </a>
                        <div class="friend-info">
                            <a href="{{ route('profileshowdisplay', ['profile_id' => $friend->id]) }}" class="friend-name-link">
                                <h3 class="friend-name">{{ $friend->profile->profilename ?? $friend->username }}</h3>
                            </a>


                            <div class="friend-actions">
                                <a href="{{ route('chat.index', ['id' => $friend->id]) }}" class="action-button chat-button">
                                    <i class="fas fa-comments"></i> Chat
                                </a>
                                <form action="{{ route('friend.remove', $friend->id) }}" method="POST" style="flex-grow: 1;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-button remove-button">
                                        <i class="fas fa-user-times"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-friends">
                <i class="fas fa-user-friends"></i>
                <p>You don't have any friends yet. Start adding friends to see them here!</p>
            </div>
        @endif
    </div>

    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
</body>
</html>