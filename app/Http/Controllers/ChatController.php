<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Illuminate\Support\Facades\Log;
class ChatController extends Controller
{
    // El método checkguest ha sido reemplazado por el middleware GuestMiddleware
    public function index($id)
    {
        $receiver = Account::findOrFail($id);
        $sender = Auth::user();
    
        // Get messages between users
        $messages = Message::whereIn('sender_id', [$sender->id, $receiver->id])
            ->whereIn('receiver_id', [$sender->id, $receiver->id])
            ->orderBy('created_at')
            ->get();
    
        return view('chat.index', compact('receiver', 'messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:account,id',
            'message' => 'required|string',
        ]);
    
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);
    
        // Send event to Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );
    
        $pusher->trigger('chat-channel', 'new-message', [
            'message' => $message,
            'sender' => Auth::user(),
        ]);
    
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function updateMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
            'message' => 'required|string',
        ]);

        $message = Message::findOrFail($request->message_id);

        // Check if the authenticated user is the sender
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $message->update([
            'message' => $request->message,
        ]);

        // Send update event to Pusher
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $pusher->trigger('chat-channel', 'message-updated', [
            'message' => $message,
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function deleteMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required|exists:messages,id',
        ]);

        $message = Message::findOrFail($request->message_id);

        // Check if the authenticated user is the sender
        if ($message->sender_id !== Auth::id()) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
        }

        $messageId = $message->id;
        $message->delete();

        // Send delete event to Pusher
        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]);

        $pusher->trigger('chat-channel', 'message-deleted', [
            'message_id' => $messageId,
        ]);

        return response()->json(['success' => true]);
    }

    public function getMessages($id)
    {
        $receiver = Account::findOrFail($id);
        $sender = Auth::user();
    
        // Get messages between users
        $messages = Message::whereIn('sender_id', [$sender->id, $receiver->id])
            ->whereIn('receiver_id', [$sender->id, $receiver->id])
            ->orderBy('created_at')
            ->get();
        
        // Mark messages as read when admin opens the chat
        Message::where('receiver_id', $sender->id)
            ->where('sender_id', $receiver->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    
        return response()->json(['messages' => $messages]);
    }
    public function sendImage(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:account,id',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
    ]);

    try {
        // Store the image
        $imagePath = $request->file('image')->store('chat_images', 'public');
        
        // Create message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => 'image:' . asset('storage/' . $imagePath),
            'is_read' => false,
        ]);

        // Broadcast event
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );

        $pusher->trigger('chat-channel', 'new-message', [
            'message' => $message,
            'sender' => Auth::user(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'image_url' => asset('storage/' . $imagePath)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to upload image: ' . $e->getMessage()
        ], 500);
    }
}
public function sendFile(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:account,id',
        'file' => 'required|file|mimes:pdf,doc,docx,rar,zip|max:10240', // 10MB max
    ]);

    try {
        // تخزين الملف
        $filePath = $request->file('file')->store('chat_files', 'public');
        $fileUrl = asset('storage/' . $filePath);
        $fileName = $request->file('file')->getClientOriginalName();
        $fileSize = $request->file('file')->getSize();

        // إنشاء الرسالة
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => 'file:' . $fileUrl . '|' . $fileName . '|' . $fileSize,
            'is_read' => false,
        ]);

        // بث الحدث باستخدام Pusher
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );
        
        $pusher->trigger('chat-channel', 'new-message', [
            'message' => $message,
            'sender' => Auth::user(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'file_url' => $fileUrl
        ]);

    } catch (\Exception $e) {
        Log::error('Error uploading file: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to upload file'
        ], 500);
    }
}
    public function adminindex()
    {
        // الحصول على المستخدمين الذين تواصلوا مع المدير
        $users = Account::whereHas('sentMessages', function($query) {
                $query->where('receiver_id', Auth::id());
            })
            ->orWhereHas('receivedMessages', function($query) {
                $query->where('sender_id', Auth::id());
            })
            ->withCount(['receivedMessages as unread_count' => function($query) {
                $query->where('sender_id', Auth::id())
                      ->where('is_read', false);
            }])
            ->get();

        return view('admin.chats', compact('users'));
    }
    
    public function markAsRead($senderId)
{
    Message::where('sender_id', $senderId)
           ->where('receiver_id', auth()->id())
           ->where('is_read', false)
           ->update(['is_read' => true]);

    return response()->json(['success' => true]);
}

// Get unread message count for a specific user
public function getUnreadCount($userId)
{
    $currentUser = Auth::id();
    
    $unreadCount = Message::where('receiver_id', $currentUser)
        ->where('sender_id', $userId)
        ->where('is_read', false)
        ->count();
        
    return response()->json([
        'success' => true,
        'unread_count' => $unreadCount
    ]);
}

// Get all unread message counts for admin
public function getAllUnreadCounts()
{
    $currentUser = Auth::id();
    
    $unreadCounts = Message::where('receiver_id', $currentUser)
        ->where('is_read', false)
        ->selectRaw('sender_id, count(*) as count')
        ->groupBy('sender_id')
        ->get()
        ->pluck('count', 'sender_id')
        ->toArray();
        
    return response()->json([
        'success' => true,
        'unread_counts' => $unreadCounts
    ]);
}
}