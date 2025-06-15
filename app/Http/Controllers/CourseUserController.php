<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseUser;
use Illuminate\Support\Facades\Auth;

class CourseUserController extends Controller
{
    public function addToFavorite($courseId)
    {
        $userId = Auth::id(); // Get logged-in user ID

        // Check if the row already exists
        $entry = CourseUser::firstOrCreate(
            ['course_id' => $courseId, 'user_id' => $userId],
            ['favorite' => false, 'apply' => false]
        );

        // Update the favorite field
        $entry->favorite = true;
        $entry->save();

        return response()->json(['success' => true, 'message' => 'Course added to favorites!']);
    }

    public function applyCourse($courseId)
    {
        $userId = Auth::id(); // Get logged-in user ID

        // Check if the row already exists
        $entry = CourseUser::firstOrCreate(
            ['course_id' => $courseId, 'user_id' => $userId],
            ['favorite' => false, 'apply' => false]
        );

        // Update the apply field
        $entry->apply = true;
        $entry->save();

        return response()->json(['success' => true, 'message' => 'Course applied successfully!']);
    }
}
