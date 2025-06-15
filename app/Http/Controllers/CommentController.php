<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Account;
class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'comment' => 'required|string|max:255',
        ]);
    

        Comment::create([
            'course_id' => $request->course_id,
            'comment' => $request->comment,
            'account_id' => Auth::id(), 
        ]);
    
        return redirect()->back()->with('success', 'Comment added successfully!');
    }
    

public function update(Request $request, $id)
{

    $comment = Comment::findOrFail($id);


    if (Auth::check() && Auth::id() === $comment->account_id) {

        $validated = $request->validate([
            'comment' => 'required|string|max:255',
        ]);


        $comment->comment = $validated['comment'];
        $comment->save();


        return response()->json(['success' => true], 200);
    }


    return response()->json(['success' => false, 'message' => 'You cannot edit this comment.'], 403);
}


public function destroy($id)
{

    $comment = Comment::findOrFail($id);


    if (Auth::check() && Auth::id() === $comment->account_id) {

        $comment->delete();


        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }


    return redirect()->back()->with('error', 'You cannot delete this comment.');
}


public function storeReply(Request $request, $commentId)
{
    $request->validate([
        'reply' => 'required|string|max:500',
    ]);


    $parentComment = Comment::findOrFail($commentId);


    $accountId = Auth::id();


    $reply = new Comment();
    $reply->comment = $request->input('reply');
    $reply->course_id = $parentComment->course_id;
    $reply->account_id = $accountId;
    $reply->parent_id = $parentComment->id;
    $reply->save();

    return redirect()->back()->with('success', 'Reply added successfully.');
}

}
