<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Requests</title>
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

        /* Friend Requests Page Specific Styles */
        .requests-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .requests-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .requests-header h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin: 0;
        }
        
        .requests-count {
            background-color: #3498db;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .requests-list {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .request-item {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }
        
        .request-item:hover {
            background-color: #f8f9fa;
        }
        
        .request-item:last-child {
            border-bottom: none;
        }
        
        .request-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 1.5rem;
            border: 2px solid #eaeaea;
        }
        
        .request-info {
            flex-grow: 1;
        }
        
        .request-name {
            font-size: 1.1rem;
            margin: 0 0 0.3rem 0;
            color: #2c3e50;
        }
        
        .request-username {
            color: #7f8c8d;
            margin: 0;
            font-size: 0.9rem;
        }
        
        .request-actions {
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
        }
        
        .accept-button {
            background-color: #2ecc71;
            color: white;
        }
        
        .accept-button:hover {
            background-color: #27ae60;
        }
        
        .reject-button {
            background-color: #e74c3c;
            color: white;
        }
        
        .reject-button:hover {
            background-color: #c0392b;
        }
        
        .no-requests {
            text-align: center;
            padding: 3rem;
        }
        
        .no-requests i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }
        
        .no-requests p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }
        
        .request-time {
            color: #95a5a6;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .request-item {
                flex-direction: column;
                text-align: center;
            }
            
            .request-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }
            
            .request-actions {
                margin-top: 1rem;
                width: 100%;
                justify-content: center;
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

    <div class="requests-container">
        <div class="requests-header">
            <h1>Friend Requests</h1>
            <span class="requests-count">{{ $pendingRequests->count() }} requests</span>
        </div>
        
        <div class="requests-list">
            @if($pendingRequests->count() > 0)
                @foreach($pendingRequests as $request)
                    <div class="request-item">
                        <img src="{{ $request->sender->profile->photo ? asset('storage/' . $request->sender->profile->photo) : asset('images/noimageprofile.jpg') }}" 
                             alt="{{ $request->sender->username }}" class="request-avatar">
                        
                        <div class="request-info">
                            <h3 class="request-name">{{ $request->sender->profile->profilename ?? $request->sender->username }}</h3>
                            <p class="request-time">Sent {{ $request->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div class="request-actions">
                            <form action="{{ route('friend.accept', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-button accept-button">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            </form>
                            
                            <form action="{{ route('friend.reject', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="action-button reject-button">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-requests">
                    <i class="fas fa-user-friends"></i>
                    <p>You don't have any pending friend requests</p>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('js/libs/jquery.min.js') }}"></script>
    <script src="{{ asset('js/libs/bootstrap.bundle.min.js') }}"></script>
</body>
</html>