<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{



    public function submitFeedback(Request $request)
    {

        $request->validate([
            'feedbackinfo' => 'required|string|max:500', 
        ]);


        Feedback::create([
            'account_id' => Auth::id(), 
            'feedbackinfo' => $request->feedbackinfo,
        ]);

        return redirect()->back()->with('success', 'Your feedback has been submitted successfully.');
    }


}
