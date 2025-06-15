<?php

namespace App\Http\Controllers;
use App\Models\CourseRating; 
use Illuminate\Http\Request;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;  
use App\Models\Course;  
use App\Models\User;  
use App\Models\InstructorRating;  
use App\Models\Profile;  
use App\Models\Instructor;  

class RatingController extends Controller
{

    public function getRating()
    {

        $rating = Rating::where('account_id', Auth::id())->first();

        return response()->json([
            'success' => true,
            'rating' => $rating ? $rating->rating : null,
        ]);
    }


    public function submitRating(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);


        $rating = Rating::updateOrCreate(
            ['account_id' => Auth::id()],  
            ['rating' => $request->rating]
        );

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully.',
            'data' => $rating,
        ]);
    }

        public function calculateWebsiteRating()
        {
            $averageRating = Rating::avg('rating'); 
    
            return response()->json([
                'success' => true,
                'average_rating' => round($averageRating, 1), 
            ]);
        }
        //////////////////////////////////////////////////////////////////////////

    public function rateCourse(Request $request, $courseId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);


        $accountId = auth()->id();  


        $user = User::where('account_id', $accountId)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userId = $user->id;  


        $existingRating = CourseRating::where('course_id', $courseId)
                                       ->where('user_id', $userId)  
                                       ->first();

        if ($existingRating) {

            $existingRating->rating = $request->rating;
            $existingRating->save();
        } else {

            CourseRating::create([
                'course_id' => $courseId,
                'user_id' => $userId,  
                'rating' => $request->rating,
            ]);
        }

        return response()->json(['success' => true]);
    }


    public function getUserRating($courseId)
    {

        $accountId = auth()->id();


        $user = User::where('account_id', $accountId)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userId = $user->id;


        $rating = CourseRating::where('course_id', $courseId)
                              ->where('user_id', $userId)  
                              ->first();

        return response()->json(['rating' => $rating ? $rating->rating : null]);
    }


    public function getAverageRating($courseId)
    {
        $averageRating = CourseRating::where('course_id', $courseId)
                                     ->avg('rating');

        return response()->json([
            'success' => true,
            'average_rating' => round($averageRating, 1)  
        ]);
    }
    //////////////////////////////////////////////////////////////////////////////////////////
    public function rateProfile(Request $request, $profile_id)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);
    

        $profile = Profile::where('profile_id', $profile_id)->firstOrFail();
    
    
        $instructor_id = $profile->instructor_id;  
    
     
        $accountId = auth()->id();
    
        
        $user = User::where('account_id', $accountId)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $userId = $user->id;  
    
      
        $existingRating = InstructorRating::where('instructor_id', $instructor_id)
                                       ->where('user_id', $userId)  
                                       ->first();
    
        if ($existingRating) {
        
            $existingRating->rating = $request->rating;
            $existingRating->save();
        } else {
        
            InstructorRating::create([
                'instructor_id' => $instructor_id, 
                'user_id' => $userId, 
                'rating' => $request->rating,
            ]);
        }
    
        return response()->json(['success' => true]);
    }
    
    public function getInstructorRating($profile_id)
    {
    
        $profile = Profile::where('profile_id', $profile_id)->firstOrFail();
    
     
        $instructor_id = $profile->instructor_id;
    
     
        $accountId = auth()->id();
    
     
        $user = User::where('account_id', $accountId)->first();
    
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
    
        $userId = $user->id;
    
    
        $rating = InstructorRating::where('instructor_id', $instructor_id)
                               ->where('user_id', $userId)  
                               ->first();
    
        return response()->json(['rating' => $rating ? $rating->rating : null]);
    }
    
    public function getInstructorAverageRating($profile_id)
    {
    
        $profile = Profile::where('profile_id', $profile_id)->firstOrFail();
    
    
        $instructor_id = $profile->instructor_id;
    
    
        $averageRating = InstructorRating::where('instructor_id', $instructor_id)->avg('rating');
    
     
        $roundedAverage = round($averageRating, 1);
    
    
        Instructor::where('id', $instructor_id)->update(['rating' => $roundedAverage]);
    
        return response()->json([
            'success' => true,
            'average_rating' => $roundedAverage 
        ]);
    }
    
    
}


