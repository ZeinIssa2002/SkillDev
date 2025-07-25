<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Instructor;
use App\Models\CourseReport;
use App\Models\Account;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Feedback;

use Pusher\Pusher;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

public function dashboard()
{
    return view('admin.dashboard');
}

public function courses()
{
    $courses = Course::with('ratings')->get()->map(function($course) {
        $course->average_rating = $course->ratings->avg('rating');
        $course->ratings_count = $course->ratings->count();
        return $course;
    });
    
    return view('admin.courses', compact('courses'));
}

    public function destroyCourse($id)
    {

        if (request()->method() !== 'DELETE') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Invalid HTTP method.'], 405);
            } else {
                abort(405, 'Method Not Allowed');
            }
        }

        $course = Course::findOrFail($id);
        

        foreach ($course->levels as $level) {

            foreach ($level->questions as $question) {
                $question->delete();
            }

            if (is_array($level->images)) {
                foreach ($level->images as $image) {
                    if ($image) { \Storage::delete('public/' . $image); }
                }
            }
            if (is_array($level->videos)) {
                foreach ($level->videos as $video) {
                    if ($video) { \Storage::delete('public/' . $video); }
                }
            }
            $level->delete();
        }
        

        $course->userProgress()->delete();

        if (method_exists($course, 'users')) {
            $course->users()->detach();
        }
        

        foreach ($course->images as $img) {
            if ($img->image_path) { \Storage::delete('public/' . $img->image_path); }
            $img->delete();
        }
        

        if ($course->photo) { \Storage::delete('public/' . $course->photo); }
        if ($course->video) { \Storage::delete('public/' . $course->video); }
        

        $course->comments()->delete();
        $course->ratings()->delete();
        

        $course->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
        } else {
            return redirect()->route('admin.courses')
                ->with('success', 'Course deleted successfully!');
        }
    }

    public function instructors()
    {
        $instructors = Instructor::with(['account', 'ratings'])->get()->map(function($instructor) {
            $instructor->average_rating = $instructor->ratings->avg('rating');
            $instructor->ratings_count = $instructor->ratings->count();
            return $instructor;
        });
        
        return view('admin.instructors', compact('instructors'));
    }

    public function toggleConfirmation($id)
    {

        $instructor = Instructor::findOrFail($id);
        $instructor->confirmation = !$instructor->confirmation;
        $instructor->save();

        $status = $instructor->confirmation ? 'The Instructor has been activated' : 'The Instructor has been deactivated';
        return back()->with('success', $status);
    }
    public function reports(Request $request)
    {

        $query = CourseReport::with(['course', 'reporter'])
                    ->latest();
        

        if ($request->status == 'pending') {
            $query->where('resolved', false)->where('in_progress', false);
        } elseif ($request->status == 'in-progress') {
            $query->where('in_progress', true);
        } elseif ($request->status == 'resolved') {
            $query->where('resolved', true);
        }
        

        if ($request->search) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%');
            })->orWhereHas('reporter', function($q) use ($request) {
                $q->where('username', 'like', '%'.$request->search.'%');
            });
        }
        
        $reports = $query->paginate(10);
        
        return view('admin.reports', compact('reports'));
    }

public function reportDetails($id)
{

    $report = CourseReport::with(['course', 'reporter'])->findOrFail($id);
    
    return response()->json([
        'course_id' => $report->course_id,
        'course_title' => $report->course->title,
        'reporter_username' => $report->reporter->username,
        'reason' => ucfirst($report->reason),
        'details' => $report->details,
        'resolved' => $report->resolved,
        'in_progress' => $report->in_progress,
        'created_at' => $report->created_at->format('M d, Y H:i'),
        'updated_at' => $report->updated_at->format('M d, Y H:i')
    ]);
}

public function updateReport(Request $request, $id)
{
    $report = CourseReport::findOrFail($id);
    
    if ($request->status == 'in-progress') {
        $report->update([
            'in_progress' => true,
            'resolved' => false
        ]);
        return back()->with('success', 'Report marked as In Progress successfully.');
    }
    elseif ($request->status == 'resolved') {
        $report->update([
            'resolved' => true,
            'in_progress' => false
        ]);
        return back()->with('success', 'Report marked as Resolved successfully.');
    }
    
    return back()->with('error', 'Invalid action.');
}

public function deleteReport($id)
{

    $report = CourseReport::findOrFail($id);
    $report->delete();
    
    return back()->with('success', 'Report deleted successfully.');
}

///////////////////////////////////////////////////////////
public function users()
{
    $users = Profile::whereHas('account', function($query) {
        $query->where('account_type', 'user');
    })->with('account')->get();

    return view('admin.users', compact('users'));
}
////////////////////////////////////
public function manageFeedbacks()
{
    $feedbacks = Feedback::with('account')->paginate(10);
    return view('admin.feedback', compact('feedbacks'));
}
public function deleteFeedback($id)
{
    $feedback = Feedback::findOrFail($id);
    $feedback->delete();
    
    return redirect()->route('admin.feedbacks')->with('success', 'Feedback deleted successfully');
}


}