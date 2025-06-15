<?php

namespace App\Http\Controllers;

use App\Models\CourseFeedback;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseFeedbackController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'instructor_id' => 'required',  
            'type' => 'required|in:suggestion,bug_report,general_comment,praise,other',
            'details' => 'required|string|min:10|max:1000',
        ]);
        
        // Verify the course exists and belongs to the instructor
        $course = \App\Models\Course::find($validated['course_id']);
        if (!$course || $course->instructor_id != $validated['instructor_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid course or instructor information'
            ], 422);
        }

        $feedback = new CourseFeedback();
        $feedback->course_id = $validated['course_id'];
        $feedback->reporter_id = auth()->id();
        $feedback->instructor_id = $validated['instructor_id'];
        $feedback->type = $validated['type'];
        $feedback->details = $validated['details'];
        $feedback->save();


        return back()->with('success', 'Thank you for your feedback!');
    }

    public function instructorIndex(Request $request)
    {
        $status = $request->query('status');
        
  
        $instructorId = auth()->user()->instructor_id;
        

        $query = CourseFeedback::with(['course', 'reporter'])
                    ->where('instructor_id', $instructorId);
    

        if ($status === 'resolved') {
            $query->where('resolved', true);
        } elseif ($status === 'in_progress') {
            $query->where('in_progress', true)->where('resolved', false);
        } elseif ($status === 'pending') {
            $query->where('in_progress', false)->where('resolved', false);
        }
    

        $feedbacks = $query->orderBy('created_at', 'desc')->paginate(10);
    
        return view('profile.feedback', compact('feedbacks'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:resolved,in_progress,pending'
        ]);
    
        $feedback = CourseFeedback::findOrFail($id);
    
        // التحقق من أن التقييم يعود للمدرب الحالي
        if ($feedback->instructor_id != auth()->user()->instructor_id) {
            abort(403, 'Unauthorized action.');
        }
    
        // تحديث الحالة بناءً على القيمة المحددة
        switch ($request->status) {
            case 'resolved':
                $feedback->update([
                    'resolved' => true,
                    'in_progress' => false
                ]);
                break;
                
            case 'in_progress':
                $feedback->update([
                    'in_progress' => true,
                    'resolved' => false
                ]);
                break;
                
            case 'pending':
                $feedback->update([
                    'in_progress' => false,
                    'resolved' => false
                ]);
                break;
        }
    
        return back()->with('success', 'Feedback status updated successfully');
    }
}