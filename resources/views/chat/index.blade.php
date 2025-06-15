@php
    function getFileIcon($fileName) {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $icons = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'rar' => '🗄️',
            'zip' => '🗄️'
        ];
        return $icons[strtolower($ext)] ?? '📁';
    }

    function formatFileSize($bytes) {
        $bytes = (int)$bytes;
        if ($bytes < 1024) return $bytes . ' Bytes';
        else if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        else return round($bytes / 1048576, 1) . ' MB';
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat with {{ $receiver->username }}</title>
    <script src="{{ asset('js/libs/pusher.min.js') }}"></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .emoji-picker {
            position: absolute;
            bottom: 60px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            width: 200px;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
        }
        .emoji-picker .emoji {
            font-size: 20px;
            padding: 5px;
            cursor: pointer;
            display: inline-block;
        }
        .emoji-picker .emoji:hover {
            background: #f0f0f0;
            border-radius: 4px;
        }
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f5f5f5;
        }
        
        .header-content {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background-color: white;
            box-shadow: var(--shadow-md);
        }
        
        .main-content {
            margin-top: 70px;
            height: calc(100vh - 70px);
            display: flex;
            flex-direction: column;
        }
        
        .chat-container {
            max-width: 800px;
            width: 100%;
            height: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .chat-header-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background-color: white;
            box-shadow: var(--shadow-md);
        }
        
        .chat-header {
            padding: 15px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #fafafa;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e5e5ea;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .profile-pic img {
            width: 100%;
            height: 100%;
        }
        
        .profile-pic .initials {
            font-weight: 600;
            color: #666;
        }
        
        .chat-title {
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
        
        .message-image {
            max-width: 300px;
            max-height: 300px;
            border-radius: 12px;
            cursor: pointer;
            border: 1px solid #eaeaea;
        }
        
        .file-message {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 8px;
            max-width: 250px;
        }
        
        .file-icon {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .file-info {
            flex: 1;
        }
        
        .file-name {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .file-size {
            font-size: 12px;
            color: #666;
        }
        
        .download-link {
            color: #007aff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .image-message-content {
            padding: 0;
            background-color: transparent !important;
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
            background-color: #fafafa;
        }
        
        .input-container {
            display: flex;
            gap: 8px;
            position: relative;
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
        
        .message-actions {
            position: absolute;
            top: -10px;
            right: 10px;
            display: none;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 10;
        }
        
        .sent .message-actions {
            left: 10px;
            right: auto;
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
        
        .edit-message-container {
            width: 100%;
            margin-top: 5px;
        }
        
        .edit-message-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e5ea;
            border-radius: 15px;
            font-size: 14px;
        }
        
        .edit-actions {
            display: flex;
            gap: 5px;
            margin-top: 5px;
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
            margin-left: 5px;
            font-style: italic;
        }
        
        .fullscreen-image-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            cursor: zoom-out;
        }
        
        .fullscreen-image {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        emoji-picker {
            position: absolute;
            bottom: 60px;
            right: 10px;
            z-index: 999;
            display: none;
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    
    <div class="main-content">
        <div class="container-fluid h-100 px-0">
            <div class="chat-container h-100 mx-auto">
                <div class="chat-header">
                    <div class="profile-pic">
                        @if($receiver->profile && $receiver->profile->photo)
                            <img src="{{ asset('storage/' . $receiver->profile->photo) }}" alt="{{ $receiver->username }}">
                        @else
                            <div class="initials">{{ substr($receiver->username, 0, 1) }}</div>
                        @endif
                    </div>
        <div class="chat-title">{{ $receiver->username }}</div>
    </div>
    
    <div class="chat-messages" id="chatMessages">
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id == auth()->id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                @if($message->sender_id == auth()->id())
                    <div class="message-actions">
                        @unless(strpos($message->message, 'image:') === 0 || strpos($message->message, 'file:') === 0)
                            <div class="message-action edit">Edit</div>
                        @endunless
                        <div class="message-action delete">Delete</div>
                    </div>
                @endif
                <div class="profile-pic">
                    @if($message->sender_id == auth()->id())
                        @if(auth()->user()->profile && auth()->user()->profile->photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile->photo) }}" alt="{{ auth()->user()->username }}">
                        @else
                            <div class="initials">{{ substr(auth()->user()->username, 0, 1) }}</div>
                        @endif
                    @else
                        @if($receiver->profile && $receiver->profile->photo)
                            <img src="{{ asset('storage/' . $receiver->profile->photo) }}" alt="{{ $receiver->username }}">
                        @else
                            <div class="initials">{{ substr($receiver->username, 0, 1) }}</div>
                        @endif
                    @endif
                </div>
                <div>
                    @if(strpos($message->message, 'image:') === 0)
                        <div class="message-content image-message-content">
                            <img src="{{ str_replace('image:', '', $message->message) }}" class="message-image" onclick="showFullscreenImage('{{ str_replace('image:', '', $message->message) }}')">
                            @if($message->updated_at != $message->created_at)
                                <span class="edited-indicator">(edited)</span>
                            @endif
                        </div>
                    @elseif(strpos($message->message, 'file:') === 0)
                        @php
                            $fileData = explode('|', str_replace('file:', '', $message->message));
                            $fileUrl = $fileData[0];
                            $fileName = $fileData[1];
                            $fileSize = $fileData[2];
                        @endphp
                        <div class="message-content file-message">
                            <div class="file-icon">{{ getFileIcon($fileName) }}</div>
                            <div class="file-info">
                                <div class="file-name">{{ $fileName }}</div>
                                <div class="file-size">{{ formatFileSize($fileSize) }}</div>
                                <a href="{{ $fileUrl }}" class="download-link" download>Download</a>
                            </div>
                        </div>
                    @else
                        <div class="message-content">
                            {{ $message->message }}
                            @if($message->updated_at != $message->created_at)
                                <span class="edited-indicator">(edited)</span>
                            @endif
                        </div>
                    @endif
                    <div class="message-time">
                        {{ $message->created_at->format('h:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="chat-input">
        <div class="input-container">
            <textarea id="messageInput" class="message-input" placeholder="Type a message..." rows="1"></textarea>
            
            <button id="fileAttachButton" class="send-button" type="button" style="background-color: #9c27b0;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M14 2V8H20" stroke="currentColor" stroke-width="2"/>
                    <path d="M16 13H8" stroke="currentColor" stroke-width="2"/>
                    <path d="M16 17H8" stroke="currentColor" stroke-width="2"/>
                    <path d="M10 9H8" stroke="currentColor" stroke-width="2"/>
                </svg>
            </button>
            
            <input type="file" id="fileInput" accept=".pdf,.doc,.docx,.rar,.zip" style="display: none;">
            
            <div class="position-relative">
                <button id="emojiButton" class="send-button" type="button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M16 14C16 14 14.5 16 12 16C9.5 16 8 14 8 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M15 9H15.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9 9H9.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <div id="emojiPicker" class="emoji-picker">
                    <!-- Emojis will be added by JavaScript -->
                </div>
            </div>
            
            <button id="sendButton" class="send-button" type="button" disabled>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M22 2L11 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <input type="file" id="imageInput" accept="image/*" style="display: none;">
            
            <button id="attachButton" class="send-button" type="button" style="background-color: #4CAF50;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <path d="M21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<script>
    // Initialize Pusher
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });

    // Subscribe to channel
    const channel = pusher.subscribe('chat-channel');
    
    // Handle incoming messages
    channel.bind('new-message', function(data) {
        if (data.message.sender_id === {{ $receiver->id }} || data.message.sender_id === {{ auth()->id() }}) {
            addMessageToChat(data.message, data.message.sender_id === {{ auth()->id() }});
        }
    });

    // Handle message updates
    channel.bind('message-updated', function(data) {
        const messageElement = document.querySelector(`.message[data-message-id="${data.message.id}"]`);
        if (messageElement) {
            const contentElement = messageElement.querySelector('.message-content');
            if (data.message.message.startsWith('image:')) {
                const imageUrl = data.message.message.replace('image:', '');
                contentElement.innerHTML = `
                    <img src="${imageUrl}" class="message-image" onclick="showFullscreenImage('${imageUrl}')">
                    ${data.message.updated_at !== data.message.created_at ? 
                    '<span class="edited-indicator">(edited)</span>' : ''}
                `;
            } else if (data.message.message.startsWith('file:')) {
                const [fileUrl, fileName, fileSize] = data.message.message.replace('file:', '').split('|');
                contentElement.innerHTML = `
                    <div class="file-message">
                        <div class="file-icon">${getFileIcon(fileName)}</div>
                        <div class="file-info">
                            <div class="file-name">${fileName}</div>
                            <div class="file-size">${formatFileSize(fileSize)}</div>
                            <a href="${fileUrl}" class="download-link" download>Download</a>
                        </div>
                    </div>
                `;
            } else {
                contentElement.innerHTML = data.message.message + 
                    (data.message.updated_at !== data.message.created_at ? 
                    '<span class="edited-indicator">(edited)</span>' : '');
            }
        }
    });

    // Handle message deletion
    channel.bind('message-deleted', function(data) {
        const messageElement = document.querySelector(`.message[data-message-id="${data.message_id}"]`);
        if (messageElement) {
            messageElement.remove();
        }
    });

    // Function to upload image
    async function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append('receiver_id', {{ $receiver->id }});
        formData.append('_token', '{{ csrf_token() }}');
        
        try {
            const response = await fetch("{{ route('chat.send.image') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to upload image');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error uploading image:', error);
            throw error;
        }
    }

    // Function to upload file
    async function uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('receiver_id', {{ $receiver->id }});
        formData.append('_token', '{{ csrf_token() }}');
        
        try {
            const response = await fetch("{{ route('chat.send.file') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                }
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to upload file');
            }
            
            return await response.json();
        } catch (error) {
            console.error('Error uploading file:', error);
            throw error;
        }
    }

    // Function to send text message
    async function sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();
        
        if (!message) return;
        
        try {
            const response = await fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    receiver_id: {{ $receiver->id }},
                    message: message
                })
            });
            
            if (!response.ok) {
                throw new Error('Failed to send message');
            }
            
            const data = await response.json();
            if (data.success) {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                document.getElementById('sendButton').disabled = true;
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Failed to send message: ' + error.message);
        }
    }

    // Function to add message to chat
    function addMessageToChat(message, isSent) {
        const messageElement = document.createElement('div');
        messageElement.className = `message ${isSent ? 'sent' : 'received'}`;
        messageElement.dataset.messageId = message.id;
        
        const profilePicContent = isSent ? 
            `@if(auth()->user()->profile && auth()->user()->profile->photo)
                <img src="{{ asset('storage/' . auth()->user()->profile->photo) }}" alt="{{ auth()->user()->username }}">
            @else
                <div class="initials">{{ substr(auth()->user()->username, 0, 1) }}</div>
            @endif` :
            `@if($receiver->profile && $receiver->profile->photo)
                <img src="{{ asset('storage/' . $receiver->profile->photo) }}" alt="{{ $receiver->username }}">
            @else
                <div class="initials">{{ substr($receiver->username, 0, 1) }}</div>
            @endif`;
        
        const timeString = new Date(message.created_at).toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const editedIndicator = message.updated_at !== message.created_at ? 
            '<span class="edited-indicator">(edited)</span>' : '';
        
        const isImageMessage = message.message.startsWith('image:');
        const isFileMessage = message.message.startsWith('file:');
        
        let messageContent;
        if (isImageMessage) {
            const imageUrl = message.message.replace('image:', '');
            messageContent = `
                <div class="message-content image-message-content">
                    <img src="${imageUrl}" class="message-image" onclick="showFullscreenImage('${imageUrl}')">
                    ${editedIndicator}
                </div>
            `;
        } else if (isFileMessage) {
            const [fileUrl, fileName, fileSize] = message.message.replace('file:', '').split('|');
            messageContent = `
                <div class="message-content file-message">
                    <div class="file-icon">${getFileIcon(fileName)}</div>
                    <div class="file-info">
                        <div class="file-name">${fileName}</div>
                        <div class="file-size">${formatFileSize(fileSize)}</div>
                        <a href="${fileUrl}" class="download-link" download>Download</a>
                    </div>
                </div>
            `;
        } else {
            messageContent = `
                <div class="message-content">
                    ${message.message}
                    ${editedIndicator}
                </div>
            `;
        }
        
        messageElement.innerHTML = `
            ${isSent ? `
                <div class="message-actions">
                    ${!isImageMessage && !isFileMessage ? '<div class="message-action edit">Edit</div>' : ''}
                    <div class="message-action delete">Delete</div>
                </div>
            ` : ''}
            <div class="profile-pic">
                ${profilePicContent}
            </div>
            <div>
                ${messageContent}
                <div class="message-time">${timeString}</div>
            </div>
        `;
        
        document.getElementById('chatMessages').appendChild(messageElement);
        scrollToBottom();
        
        // Add event listeners for new message
        if (isSent) {
            addMessageEventListeners(messageElement, isImageMessage || isFileMessage);
        }
    }

    // Function to add event listeners to message actions
    function addMessageEventListeners(messageElement, isNonEditable = false) {
        const messageId = messageElement.dataset.messageId;
        const deleteButton = messageElement.querySelector('.message-action.delete');
        
        // Handle delete action
        if (deleteButton) {
            deleteButton.addEventListener('click', () => {
                if (confirm('Are you sure you want to delete this message?')) {
                    deleteMessage(messageId);
                }
            });
        }

        // Handle edit action (only for text messages)
        if (!isNonEditable) {
            const editButton = messageElement.querySelector('.message-action.edit');
            const messageContent = messageElement.querySelector('.message-content');
            
            if (editButton && messageContent) {
                editButton.addEventListener('click', () => {
                    // Remove any existing edit containers
                    const existingEditContainer = messageElement.querySelector('.edit-message-container');
                    if (existingEditContainer) {
                        existingEditContainer.remove();
                    }
                    
                    // Create edit container
                    const editContainer = document.createElement('div');
                    editContainer.className = 'edit-message-container';
                    editContainer.innerHTML = `
                        <textarea class="edit-message-input">${messageContent.textContent.replace('(edited)', '').trim()}</textarea>
                        <div class="edit-actions">
                            <button class="edit-button">Save</button>
                            <button class="cancel-edit-button">Cancel</button>
                        </div>
                    `;
                    
                    messageElement.appendChild(editContainer);
                    
                    // Handle save action
                    const saveButton = editContainer.querySelector('.edit-button');
                    saveButton.addEventListener('click', () => {
                        const newContent = editContainer.querySelector('.edit-message-input').value.trim();
                        if (newContent && newContent !== messageContent.textContent.replace('(edited)', '').trim()) {
                            editMessage(messageId, newContent)
                                .then(() => editContainer.remove())
                                .catch(error => console.error('Error:', error));
                        } else {
                            editContainer.remove();
                        }
                    });
                    
                    // Handle cancel action
                    const cancelButton = editContainer.querySelector('.cancel-edit-button');
                    cancelButton.addEventListener('click', () => {
                        editContainer.remove();
                    });
                    
                    // Handle Enter key in edit input
                    const editInput = editContainer.querySelector('.edit-message-input');
                    editInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            saveButton.click();
                        }
                    });
                    
                    editInput.focus();
                });
            }
        }
    }

    // Function to edit message
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
            
            return await response.json();
        } catch (error) {
            console.error('Error updating message:', error);
            throw error;
        }
    }

    // Function to delete message
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
            
            return await response.json();
        } catch (error) {
            console.error('Error deleting message:', error);
            throw error;
        }
    }

    // Function to show fullscreen image
    function showFullscreenImage(imageUrl) {
        const overlay = document.createElement('div');
        overlay.className = 'fullscreen-image-overlay';
        
        const img = document.createElement('img');
        img.src = imageUrl;
        img.className = 'fullscreen-image';
        
        overlay.appendChild(img);
        document.body.appendChild(overlay);
        
        overlay.addEventListener('click', () => {
            document.body.removeChild(overlay);
        });
    }

    // Function to get file icon
    function getFileIcon(fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        const icons = {
            pdf: '📄',
            doc: '📝',
            docx: '📝',
            rar: '🗄️',
            zip: '🗄️'
        };
        return icons[ext] || '📁';
    }

    // Function to format file size
    function formatFileSize(bytes) {
        bytes = parseInt(bytes);
        if (bytes < 1024) return bytes + ' Bytes';
        else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        else return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // Function to scroll to bottom
    function scrollToBottom() {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-resize textarea
    function adjustTextareaHeight() {
        const textarea = document.getElementById('messageInput');
        textarea.style.height = 'auto';
        textarea.style.height = `${Math.min(textarea.scrollHeight, 120)}px`;
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', () => {
        scrollToBottom();
        
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const attachButton = document.getElementById('attachButton');
        const imageInput = document.getElementById('imageInput');
        const fileAttachButton = document.getElementById('fileAttachButton');
        const fileInput = document.getElementById('fileInput');
        
        // Auto-resize textarea
        messageInput.addEventListener('input', adjustTextareaHeight);
        
        // Send message on Enter key (without Shift)
        messageInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Enable/disable send button based on input
        messageInput.addEventListener('input', () => {
            sendButton.disabled = messageInput.value.trim() === '';
        });
        
        // Send button click handler
        sendButton.addEventListener('click', sendMessage);
        
        // Attach image button click handler
        attachButton.addEventListener('click', () => {
            imageInput.click();
        });
        
        // Image input change handler
        imageInput.addEventListener('change', async function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file (JPEG, PNG, GIF)');
                    return;
                }
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size should be less than 5MB');
                    return;
                }
                
                // Show loading indicator
                attachButton.innerHTML = '<div class="spinner"></div>';
                attachButton.disabled = true;
                
                try {
                    await uploadImage(file);
                    this.value = '';
                } catch (error) {
                    alert(error.message);
                } finally {
                    attachButton.innerHTML = `
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M17 8L12 3L7 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 3V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>`;
                    attachButton.disabled = false;
                }
            }
        });
        
        // Attach file button click handler
        fileAttachButton.addEventListener('click', () => {
            fileInput.click();
        });
        
        // File input change handler
        fileInput.addEventListener('change', async function(e) {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const validTypes = ['application/pdf', 'application/msword', 
                                  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                  'application/x-rar-compressed', 'application/zip'];
                const maxSize = 10 * 1024 * 1024; // 10MB
                
                // Validate file type
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid file type: PDF, DOC, DOCX, RAR, ZIP');
                    return;
                }
                
                // Validate file size
                if (file.size > maxSize) {
                    alert('File size should be less than 10MB');
                    return;
                }
                
                // Show loading indicator
                fileAttachButton.innerHTML = '<div class="spinner"></div>';
                fileAttachButton.disabled = true;
                
                try {
                    await uploadFile(file);
                    this.value = '';
                } catch (error) {
                    alert('Error: ' + error.message);
                } finally {
                    fileAttachButton.innerHTML = `
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z" stroke="currentColor" stroke-width="2"/>
                            <path d="M14 2V8H20" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 13H8" stroke="currentColor" stroke-width="2"/>
                            <path d="M16 17H8" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 9H8" stroke="currentColor" stroke-width="2"/>
                        </svg>`;
                    fileAttachButton.disabled = false;
                }
            }
        });
        
        // Emoji picker functionality
        const emojiButton = document.getElementById('emojiButton');
        const emojiPicker = document.getElementById('emojiPicker');
        
        // Common emojis
        const emojis = ['😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '🥰', '😘', '😗', '😙', '😚', '😋', '😛', '😝', '😜', '🤪', '🤨', '🧐', '🤓', '😎', '🤩', '🥳', '😏', '😒', '😞', '😔', '😟', '😕', '🙁', '😣', '😖', '😫', '😩', '🥺', '😢', '😭', '😤', '😠', '😡', '🤬', '🤯', '😳', '🥵', '🥶', '😱', '😨', '😰', '😥', '😓', '🤗', '🤔', '🤭', '🤫', '🤥', '😶', '😐', '😑', '😬', '🙄', '😯', '😦', '😧', '😮', '😲', '😴', '🤤', '😪', '😵', '🤐', '🥴', '🤢', '🤮', '🤧', '😷', '🤒', '🤕', '🤑', '🤠', '😈', '👿', '👹', '👺', '🤡', '💩', '👻', '💀', '☠️', '👽', '👾', '🤖', '🎃', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾'];
        
        // Add emojis to the picker
        emojis.forEach(emoji => {
            const span = document.createElement('span');
            span.className = 'emoji';
            span.textContent = emoji;
            span.addEventListener('click', () => {
                messageInput.value += emoji;
                messageInput.focus();
                sendButton.disabled = messageInput.value.trim() === '';
                emojiPicker.style.display = 'none';
            });
            emojiPicker.appendChild(span);
        });
        
        // Toggle emoji picker
        emojiButton.addEventListener('click', (e) => {
            e.stopPropagation();
            emojiPicker.style.display = emojiPicker.style.display === 'block' ? 'none' : 'block';
        });
        
        // Close emoji picker when clicking outside
        document.addEventListener('click', () => {
            emojiPicker.style.display = 'none';
        });
        
        // Prevent closing when clicking inside the picker
        emojiPicker.addEventListener('click', (e) => {
            e.stopPropagation();
        });
        
        // Add event listeners to existing messages
        document.querySelectorAll('.message[data-message-id]').forEach(messageElement => {
            if (messageElement.classList.contains('sent')) {
                const isNonEditable = messageElement.querySelector('.message-image') !== null || 
                                     messageElement.querySelector('.file-message') !== null;
                addMessageEventListeners(messageElement, isNonEditable);
            }
        });
    });
</script>
</body>
</html>