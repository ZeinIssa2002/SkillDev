<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FriendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // El método checkInstructorOrUser ha sido reemplazado por el middleware InstructorOrUserMiddleware
    public function sendRequest($id)
    {
        $receiver = Account::findOrFail($id);

        // تأكد من أن الحسابين ليسا متشابهين
        if (auth()->id() == $receiver->id) {
            return redirect()->back()->with('error', 'لا يمكن إرسال طلب صداقة لنفسك.');
        }

        // تحقق من وجود طلب صداقة مسبق بين المستخدمين
        $existingRequest = FriendRequest::where(function($query) use ($receiver) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $receiver->id);
        })->orWhere(function($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                  ->where('receiver_id', auth()->id());
        })->first();

        if ($existingRequest) {
            if ($existingRequest->status == 'pending') {
                if ($existingRequest->sender_id == auth()->id()) {
                    return redirect()->back()->with('info', 'لقد أرسلت طلب صداقة مسبقاً.');
                } else {
                    return redirect()->back()->with('info', 'لديك طلب صداقة من هذا المستخدم.');
                }
            } elseif ($existingRequest->status == 'accepted') {
                return redirect()->back()->with('info', 'أنتم أصدقاء بالفعل.');
            } elseif ($existingRequest->status == 'rejected') {
                return redirect()->back()->with('info', 'تم رفض طلب الصداقة مسبقاً.');
            }
        }

        // إنشاء طلب صداقة جديد
        FriendRequest::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم إرسال طلب الصداقة بنجاح.');
    }

    public function acceptRequest($id)
    {
        $request = FriendRequest::findOrFail($id);
        
        if ($request->receiver_id == auth()->id()) {
            $request->update(['status' => 'accepted']);
            return redirect()->route('friend.requests')->with('success', 'Friend request accepted.');
        }
    
        return redirect()->route('friend.requests')->with('error', 'Unauthorized action.');
    }
    
    public function rejectRequest($id)
    {
        $request = FriendRequest::findOrFail($id);
        
        if ($request->receiver_id == auth()->id()) {
            $request->update(['status' => 'rejected']);
            return redirect()->route('friend.requests')->with('success', 'Friend request rejected.');
        }
    
        return redirect()->route('friend.requests')->with('error', 'Unauthorized action.');
    }

    public function checkFriendshipStatus($id)
    {
    

        $user = Account::findOrFail($id);
        $friendship = FriendRequest::where(function($query) use ($user) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', auth()->id());
        })->first();

        if (!$friendship) {
            return 'none';
        }

        return $friendship->status;
    }
    public function showFriends()
    {


        // الحصول على جميع طلبات الصداقة المقبولة حيث المستخدم هو المرسل أو المستقبل
        $friends = FriendRequest::where(function($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
        })
        ->where('status', 'accepted')
        ->with(['sender', 'receiver'])
        ->get()
        ->map(function($request) {
            // تحديد من هو الصديق (الطرف الآخر غير المستخدم الحالي)
            return $request->sender_id == auth()->id() ? $request->receiver : $request->sender;
        });
    
        return view('friends.index', compact('friends'));
    }
    public function removeFriend($id)
{
    // البحث عن طلب الصداقة بين المستخدم الحالي والصديق المراد إزالته
    $friendship = FriendRequest::where(function($query) use ($id) {
        $query->where('sender_id', auth()->id())
              ->where('receiver_id', $id)
              ->where('status', 'accepted');
    })->orWhere(function($query) use ($id) {
        $query->where('sender_id', $id)
              ->where('receiver_id', auth()->id())
              ->where('status', 'accepted');
    })->first();

    if ($friendship) {
        $friendship->delete();
        return redirect()->route('friends.list')->with('success', 'Friend removed successfully.');
    }

    return redirect()->route('friends.list')->with('error', 'Friend not found.');
}
public function showFriendRequests()
{
    $pendingRequests = FriendRequest::with('sender.profile')
        ->where('receiver_id', auth()->id())
        ->where('status', 'pending')
        ->latest()
        ->get();

    return view('friends.requests', compact('pendingRequests'));
}
}