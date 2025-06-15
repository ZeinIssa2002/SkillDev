@extends('admin.layouts.app')

@section('title', 'Admin Panel - Chat System')

@section('styles')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<style>
        /* General styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        
        /* Admin Panel Sidebar */
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
        
        /* Chat System Styles */
        .chat-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            height: 100vh;
        }
        
        .user-list {
            width: 300px;
            border-left: 1px solid #eaeaea;
            overflow-y: auto;
        }
        
        .user-list-header {
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            font-weight: bold;
            background-color: #f8f9fa;
        }
        
        .user-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        
        .user-item:hover {
            background-color: #f8f9fa;
        }
        
        .user-item.active {
            background-color: #e9f5ff;
        }
        
        .user-item .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            overflow: hidden;
            background-color: #e5e5ea;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-item .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .user-item .profile-pic .initials {
            font-weight: 600;
            color: #666;
            font-size: 18px;
        }
        
        .user-item .user-info {
            flex-grow: 1;
        }
        
        .user-item .username {
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .user-item .last-message {
            font-size: 13px;
            color: #777;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Chat area */
        .chat-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #eaeaea;
        }
        
        .chat-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
            background-color: #f8f9fa;
            color: #999;
            font-size: 18px;
        }
        
        .chat-active {
            display: none;
            flex-direction: column;
            height: 100%;
        }
        
        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
        }
        
        .chat-header .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            overflow: hidden;
            background-color: #e5e5ea;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .chat-header .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .chat-header .profile-pic .initials {
            font-weight: 600;
            color: #666;
            font-size: 18px;
        }
        
        .chat-header .chat-title {
            font-weight: 600;
            flex-grow: 1;
        }
        
        .chat-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: #f5f5f5;
        }
        
        .message {
            display: flex;
            gap: 8px;
            max-width: 80%;
            position: relative;
        }
        
        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        
        .message-content {
            padding: 10px 15px;
            border-radius: 18px;
            line-height: 1.4;
            font-size: 15px;
            word-break: break-word;
        }
        
        .sent .message-content {
            background-color: #007aff;
            color: white;
            border-bottom-right-radius: 4px;
        }
        
        .received .message-content {
            background-color: #e5e5ea;
            color: black;
            border-bottom-left-radius: 4px;
        }
        
        .message-time {
            font-size: 11px;
            color: #8e8e93;
            margin-top: 4px;
            text-align: right;
        }
        
        .chat-input {
            padding: 10px;
            border-top: 1px solid #eaeaea;
            background-color: #f8f9fa;
        }
        
        .input-container {
            display: flex;
            gap: 8px;
        }
        
        .message-input {
            flex: 1;
            border: 1px solid #e5e5ea;
            border-radius: 18px;
            padding: 10px 15px;
            font-size: 15px;
            resize: none;
            outline: none;
            min-height: 40px;
            max-height: 120px;
            font-family: inherit;
        }
        
        .send-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #007aff;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .send-button:disabled {
            background-color: #8e8e93;
            cursor: not-allowed;
        }
        
        /* Message actions */
        .message-actions {
            position: absolute;
            top: -10px;
            left: 10px;
            display: none;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10;
        }
        
        .sent .message-actions {
            right: 10px;
            left: auto;
        }
        
        .message:hover .message-actions {
            display: flex;
        }
        
        .message-action {
            padding: 5px 10px;
            cursor: pointer;
            font-size: 12px;
            color: #007aff;
        }
        
        .message-action.delete {
            color: #ff3b30;
        }
        
        /* Edit message */
        .edit-message-container {
            display: none;
            width: 100%;
            margin-top: 5px;
        }
        
        .edit-message-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e5ea;
            border-radius: 15px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .edit-actions {
            display: flex;
            gap: 5px;
            margin-top: 5px;
            justify-content: flex-end;
        }
        
        .edit-button, .cancel-edit-button {
            padding: 5px 10px;
            border-radius: 10px;
            font-size: 12px;
            cursor: pointer;
        }
        
        .edit-button {
            background-color: #007aff;
            color: white;
            border: none;
        }
        
        .cancel-edit-button {
            background-color: #e5e5ea;
            border: none;
        }
        
        .edited-indicator {
            font-size: 10px;
            color: #8e8e93;
            margin-right: 5px;
            font-style: italic;
        }
        
        /* Unread messages indicator */
        .unread-badge {
            background-color: #ff3b30;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            margin-right: auto;
        }
        
        .no-messages {
            text-align: center;
            color: #999;
            margin-top: 20px;
        }
        
        .error-message {
            text-align: center;
            color: #ff3b30;
            margin-top: 20px;
        }
        
        /* Admin Panel Specific Styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-resolved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .report-reason {
            font-weight: 600;
            color: #6c757d;
        }
        
        .action-btns .btn {
            margin-right: 5px;
        }
        
        .search-box {
            max-width: 300px;
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
    </style>
@endsection

@section('content')
            <div class="chat-container">
                <!-- Chat area -->
                <div class="chat-area">
                    <div id="chatPlaceholder" class="chat-placeholder">
                        <p>Select a conversation to start chatting</p>
                    </div>
                    
                    <div id="chatActive" class="chat-active">
                        <div class="chat-header">
                            <div class="profile-pic" id="chatProfilePic">
                                <div class="initials">U</div>
                            </div>
                            <div class="chat-title" id="chatTitle">Username</div>
                        </div>
                        
                        <div class="chat-messages" id="chatMessages"></div>
                        
                        <div class="chat-input">
                            <div class="input-container">
                                <textarea id="messageInput" class="message-input" placeholder="Type a message..." rows="1"></textarea>
                                <button id="sendButton" class="send-button" disabled>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User list -->
                <div class="user-list">
                    <div class="user-list-header">Users</div>
                    @foreach($users as $user)
                        @if($user->id != auth()->id())
                            <div class="user-item" data-user-id="{{ $user->id }}" onclick="openChat({{ $user->id }}, '{{ $user->username }}', '{{ $user->profile && $user->profile->photo ? asset('storage/' . $user->profile->photo) : '' }}')">
                                <div class="profile-pic">
                                    @if(isset($user) && $user && $user->profile && $user->profile->photo)
                                        <img src="{{ asset('storage/' . $user->profile->photo) }}" alt="{{ $user->username }}" onerror="this.parentElement.innerHTML='<div class=\'initials\'>{{ substr($user->username, 0, 1) }}</div>'">
                                    @elseif(isset($user) && $user)
                                        <div class="initials">{{ substr($user->username, 0, 1) }}</div>
                                    @else
                                        <div class="initials bg-secondary">?</div>
                                    @endif
                                </div>
                                <div class="user-info">
                                    <div class="username">
                                        @if(isset($user) && $user)
                                            {{ $user->username }}
                                            <span class="notification-dot" id="notificationDot_{{ $user->id }}" style="display: none;"></span>
                                        @else
                                            <span class="text-muted">[User Deleted]</span>
                                        @endif
                                    </div>
                                    <div class="last-message" id="lastMessage_{{ isset($user) && $user ? $user->id : 'deleted' }}"></div>
                                </div>
                                <div class="unread-badge" id="unreadBadge_{{ $user->id }}" style="display: none;"></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script>
        // Initialize Pusher
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
        });
        
        // System variables
        let currentChatUserId = null;
        const unreadCounts = {};
        
        // Load all unread counts on page load
        async function loadAllUnreadCounts() {
            try {
                const response = await fetch("{{ route('chat.all.unread.counts') }}");
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        const counts = data.unread_counts;
                        for (const userId in counts) {
                            if (counts[userId] > 0) {
                                unreadCounts[userId] = counts[userId];
                                showNotificationDot(userId);
                                updateUnreadBadge(userId, counts[userId]);
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading unread counts:', error);
            }
        }
        
        // Subscribe to channel
        const channel = pusher.subscribe('chat-channel');
        
        // Handle new messages
        channel.bind('new-message', function(data) {
            const isCurrentChat = data.message.sender_id === currentChatUserId || 
                                 (data.message.sender_id === {{ auth()->id() }} && data.message.receiver_id === currentChatUserId);
            
            if (isCurrentChat) {
                // If chat is open
                const isSent = data.message.sender_id === {{ auth()->id() }};
                addMessageToChat(data.message, isSent);
                scrollToBottom();
            } else {
                // If chat is not open
                updateLastMessage(data.message.sender_id, data.message.message);
                incrementUnreadCount(data.message.sender_id);
            }
        });
        
        // Handle message updates
        channel.bind('message-updated', function(data) {
            const messageElement = document.querySelector(`.message[data-message-id="${data.message.id}"]`);
            if (messageElement) {
                const contentElement = messageElement.querySelector('.message-content');
                contentElement.innerHTML = data.message.message + 
                    (data.message.updated_at !== data.message.created_at ? 
                    '<span class="edited-indicator">(edited)</span>' : '');
            }
        });
        
        // Handle message deletion
        channel.bind('message-deleted', function(data) {
            const messageElement = document.querySelector(`.message[data-message-id="${data.message_id}"]`);
            if (messageElement) {
                messageElement.remove();
            }
        });
        
        // Add message to chat
        function addMessageToChat(message, isSent) {
            const messageElement = document.createElement('div');
            messageElement.className = `message ${isSent ? 'sent' : 'received'}`;
            messageElement.dataset.messageId = message.id;
            
            const profilePicContent = isSent ? 
                `@if(auth()->user()->profile && auth()->user()->profile->photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile->photo) }}" alt="{{ auth()->user()->username }}" onerror="this.parentElement.innerHTML='<div class=\\'initials\\'>{{ substr(auth()->user()->username, 0, 1) }}</div>'">
                @else
                    <div class="initials">{{ substr(auth()->user()->username, 0, 1) }}</div>
                @endif` :
                `@if(isset($user) && $user)
    <div class="initials">{{ substr($user->username, 0, 1) }}</div>
@else
    <div class="initials bg-secondary">?</div>
@endif`;
            
            const timeString = new Date(message.created_at).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // تم إزالة مؤشر التعديل لتجنب المشاكل
            const editedIndicator = '';
            
            messageElement.innerHTML = `
                ${isSent ? `
                    <div class="message-actions">
                        <div class="message-action edit">Edit</div>
                        <div class="message-action delete">Delete</div>
                    </div>
                ` : ''}
                <div class="profile-pic">
                    ${profilePicContent}
                </div>
                <div>
                    <div class="message-content">
                        ${message.message}
                        ${editedIndicator}
                    </div>
                    <div class="message-time">
                        ${timeString}
                    </div>
                    ${isSent ? `
                    <div class="edit-message-container">
                        <textarea class="edit-message-input">${message.message}</textarea>
                        <div class="edit-actions">
                            <button class="edit-button">Save</button>
                            <button class="cancel-edit-button">Cancel</button>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('chatMessages').appendChild(messageElement);
            
            // Add event listeners for the new message
            if (isSent) {
                addMessageEventListeners(messageElement);
            }
        }
        
        // Scroll to bottom
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Auto-adjust textarea height
        function adjustTextareaHeight() {
            const textarea = document.getElementById('messageInput');
            textarea.style.height = 'auto';
            textarea.style.height = `${Math.min(textarea.scrollHeight, 120)}px`;
        }
        
        // Send message
        async function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            
            if (!message || !currentChatUserId) return;
            
            try {
                const response = await fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        receiver_id: currentChatUserId,
                        message: message
                    })
                });
                
                if (response.ok) {
                    messageInput.value = '';
                    adjustTextareaHeight();
                    document.getElementById('sendButton').disabled = true;
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        }
        
        // Edit message
        async function editMessage(messageId, newContent) {
            try {
                const response = await fetch("{{ route('chat.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message_id: messageId,
                        message: newContent
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to update message');
                }
            } catch (error) {
                console.error('Error editing message:', error);
            }
        }
        
        // Delete message
        async function deleteMessage(messageId) {
            try {
                const response = await fetch("{{ route('chat.delete') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        message_id: messageId
                    })
                });
                
                if (!response.ok) {
                    throw new Error('Failed to delete message');
                }
            } catch (error) {
                console.error('Error deleting message:', error);
            }
        }
        
        // Add event listeners for message actions
        function addMessageEventListeners(messageElement) {
            const messageId = messageElement.dataset.messageId;
            const editButton = messageElement.querySelector('.message-action.edit');
            const deleteButton = messageElement.querySelector('.message-action.delete');
            const saveEditButton = messageElement.querySelector('.edit-button');
            const cancelEditButton = messageElement.querySelector('.cancel-edit-button');
            const editInput = messageElement.querySelector('.edit-message-input');
            const messageContent = messageElement.querySelector('.message-content');
            const editContainer = messageElement.querySelector('.edit-message-container');
            
            // Show edit form
            editButton.addEventListener('click', () => {
                editContainer.style.display = 'block';
                editInput.value = messageContent.textContent.replace('(edited)', '').trim();
                editInput.focus();
            });
            
            // Delete message
            deleteButton.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this message?')) {
                    deleteMessage(messageId);
                }
            });
            
            // Save edited message
            saveEditButton.addEventListener('click', () => {
                const newContent = editInput.value.trim();
                if (newContent && newContent !== messageContent.textContent.replace('(edited)', '').trim()) {
                    editMessage(messageId, newContent);
                }
                editContainer.style.display = 'none';
            });
            
            // Cancel editing
            cancelEditButton.addEventListener('click', () => {
                editContainer.style.display = 'none';
            });
            
            // Handle Enter key in edit field
            editInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    saveEditButton.click();
                }
            });
        }
        
        // Open chat with user
        async function openChat(userId, username, profilePhoto) {
            // Update UI
            document.getElementById('chatPlaceholder').style.display = 'none';
            document.getElementById('chatActive').style.display = 'flex';
            document.getElementById('chatTitle').textContent = username;
            
            // Update profile picture
            const profilePic = document.getElementById('chatProfilePic');
            if (profilePhoto) {
                profilePic.innerHTML = `<img src="${profilePhoto}" alt="${username}" onerror="this.parentElement.innerHTML='<div class=\\'initials\\'>${username.charAt(0).toUpperCase()}</div>'">`;
            } else {
                profilePic.innerHTML = `<div class="initials">${username.charAt(0).toUpperCase()}</div>`;
            }
            
            // Remove active class from all users and add to current user
            document.querySelectorAll('.user-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`.user-item[data-user-id="${userId}"]`).classList.add('active');
            
            // Clear previous messages
            document.getElementById('chatMessages').innerHTML = '';
            
            // Reset unread count
            resetUnreadCount(userId);
            
            // Save current user ID
            currentChatUserId = userId;
            
            // Fetch messages from server
            try {
                const response = await fetch(`/chat/messages/${userId}`);
                
                // Check if request was successful
                if (!response.ok) {
                    throw new Error('Failed to fetch messages');
                }
                
                const data = await response.json();
                const messages = data.messages;
                
                // Check if there are messages
                if (messages && messages.length > 0) {
                    messages.forEach(message => {
                        const isSent = message.sender_id === {{ auth()->id() }};
                        addMessageToChat(message, isSent);
                        
                        // Update last message in user list
                        updateLastMessage(userId, message.message);
                    });
                    
                    // Scroll to latest message
                    scrollToBottom();
                    
                    // Add event listeners for sent messages
                    document.querySelectorAll('.message.sent[data-message-id]').forEach(messageElement => {
                        addMessageEventListeners(messageElement);
                    });
                } else {
                    // If no messages
                    document.getElementById('chatMessages').innerHTML = '<p class="no-messages">No messages yet</p>';
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
                document.getElementById('chatMessages').innerHTML = '<p class="error-message">Error loading messages</p>';
            }
        }
        
        // Update last message in user list
        function updateLastMessage(userId, message) {
            const lastMessageElement = document.getElementById(`lastMessage_${userId}`);
            if (lastMessageElement) {
                lastMessageElement.textContent = message.length > 30 ? message.substring(0, 30) + '...' : message;
            }
        }
        
        // Increment unread message count
        function incrementUnreadCount(userId) {
            if (userId === currentChatUserId) return;
            
            if (!unreadCounts[userId]) {
                unreadCounts[userId] = 0;
            }
            unreadCounts[userId]++;
            
            updateUnreadBadge(userId, unreadCounts[userId]);
            showNotificationDot(userId);
        }
        
        // Update unread badge count
        function updateUnreadBadge(userId, count) {
            const badge = document.getElementById(`unreadBadge_${userId}`);
            if (badge) {
                badge.textContent = count;
                badge.style.display = 'flex';
            }
        }
        
        // Show notification dot
        function showNotificationDot(userId) {
            const notificationDot = document.getElementById(`notificationDot_${userId}`);
            if (notificationDot) {
                notificationDot.style.display = 'inline-block';
            }
        }
        
        // Reset unread message count
        function resetUnreadCount(userId) {
            unreadCounts[userId] = 0;
            const badge = document.getElementById(`unreadBadge_${userId}`);
            if (badge) {
                badge.style.display = 'none';
            }
            
            // Hide notification dot
            const notificationDot = document.getElementById(`notificationDot_${userId}`);
            if (notificationDot) {
                notificationDot.style.display = 'none';
            }
            
            // Mark messages as read on server
            fetch(`/admin/chat/mark-as-read/${userId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(error => console.error('Error marking messages as read:', error));
        }
        
        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', () => {
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            
            messageInput.addEventListener('input', () => {
                adjustTextareaHeight();
                sendButton.disabled = messageInput.value.trim() === '' || !currentChatUserId;
            });
            
            messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            sendButton.addEventListener('click', sendMessage);
            
            // Load unread counts on page load
            loadAllUnreadCounts();
            
            // إخفاء النقطة الحمراء في الشريط الجانبي عند دخول صفحة الدردشة
            document.getElementById('sidebarChatNotification').style.display = 'none';
        });
    </script>
@endsection