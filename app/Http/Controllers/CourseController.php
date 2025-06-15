<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Category;
use App\Models\CourseUser;
use App\Models\User;
use App\Models\CourseImage;
use App\Models\CourseReport;
use App\Models\UserProgress;
use App\Models\CourseLevel;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    // El método checkInstructor ha sido reemplazado por el middleware InstructorMiddleware
    public function index()
    {
        $user = Auth::user();
    
        if ($user->account_type == 'instructor') {
            $instructor = Instructor::where('account_id', $user->id)->first();
    
            // التحقق من وجود instructor و confirmation
            if (!$instructor) {
                return redirect()->route('homepage')->with('error', '⚠️ You are not registered as an instructor yet!');
            }
    
            if (!$instructor->confirmation) {
                return redirect()->route('homepage')->with('error', '⏳ Your instructor account is under review, please wait for activation.');
            }
    
            $courses = Course::where('instructor_id', $instructor->id)->get();
            return view('course.indexinstructor', compact('courses'));
    
        } else {
            $courses = Course::all(); 
            return view('course.indexuser', compact('courses'));
        }
    }
///////////////////////////////////////////////
public function create()
{

    $categories = Category::with('courses')->get();
    
    // Get instructor's courses for prerequisite dropdown
    $instructor = Instructor::where('account_id', Auth::id())->first();
    $instructorCourses = Course::where('instructor_id', $instructor->id)->get();
    
    return view('course.create', compact('categories', 'instructorCourses'));
}

public function store(Request $request)
{

    
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'coursepreview' => 'required|string|max:255',
        'content' => 'required|string',
        'difficulty_level' => 'required|in:beginner,intermediate,advanced',
        'category_id' => 'required|exists:categories,id',
        'prerequisite_id' => 'nullable|exists:courses,id',
        'course_images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'video' => 'nullable|mimes:mp4|max:10240',
        'course_files.*' => 'nullable|file|mimes:rar,zip,pdf,docx|max:10240', // 10MB max for files
        'levels' => 'required|array|min:1',
        'levels.*.title' => 'required|string|max:255',
        'levels.*.passing_score' => 'required|integer|min:1|max:100',
        'levels.*.text_contents' => 'sometimes|array',
        'levels.*.images.*' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        'levels.*.videos.*' => 'sometimes|mimes:mp4|max:10240',
        'levels.*.questions' => 'sometimes|array',
        'levels.*.questions.*.question_text' => 'required|string',
        'levels.*.questions.*.options' => 'required|array',
        'levels.*.questions.*.correct_answer' => 'required|integer|min:0',
    ]);

    $userId = Auth::id();
    $instructor = Instructor::where('account_id', $userId)->first();

    if (!$instructor) {
        return response()->json(['error' => 'Instructor not found for the logged-in user!'], 404);
    }

    $validated['instructor_id'] = $instructor->id;

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('photos', 'public');
        $validated['photo'] = $photoPath;
    }

    // Handle video upload
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('videos', 'public');
        $validated['video'] = $videoPath;
    }

    // Handle multiple images upload
    $courseImages = [];
    if ($request->hasFile('course_images')) {
        foreach ($request->file('course_images') as $image) {
            $path = $image->store('course_images', 'public');
            $courseImages[] = ['image_path' => $path];
        }
    }

    // Handle placement test data if enabled
    if ($request->has('enable_placement_test')) {
        $placementQuestions = [];
        $requestQuestions = $request->input('placement_questions', []);
        
        foreach ($requestQuestions as $question) {
            if (!empty($question['text']) && !empty($question['options'])) {
                $placementQuestions[] = [
                    'text' => $question['text'],
                    'options' => $question['options'],
                    'correct' => (int)$question['correct']
                ];
            }
        }
        
        if (count($placementQuestions) < 2) {
            return back()->withErrors(['placement_questions' => 'At least 2 placement questions are required']);
        }
        
        $validated['placement_test'] = json_encode($placementQuestions, JSON_UNESCAPED_UNICODE);
        $validated['placement_pass_score'] = $request->input('placement_pass_score', 70);
    } else {
        $validated['placement_test'] = null;
        $validated['placement_pass_score'] = null;
    }

    // Create course
    $course = Course::create($validated);

    // Save additional images
    if (!empty($courseImages)) {
        $course->images()->createMany($courseImages);
    }
    
    // Handle file uploads
    if ($request->hasFile('course_files')) {
        $uploadedFiles = [];
        $existingFiles = is_array($course->files) ? $course->files : [];
        
        foreach ($request->file('course_files') as $file) {
            try {
                // Validate file type and size
                $validated = $request->validate([
                    'course_files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480', // 20MB max
                ]);
                
                // Upload the file
                $path = $file->store('course_files/' . $course->id, 'public');
                
                // Add to uploaded files array
                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString()
                ];
            } catch (\Exception $e) {
                // Log error but don't fail the entire request
                \Log::error('File upload failed: ' . $e->getMessage());
            }
        }
        
        // Merge existing files with newly uploaded ones
        $allFiles = array_merge($existingFiles, $uploadedFiles);
        $course->files = $allFiles;
        $course->save();
    }

    // Handle levels and questions
    foreach ($request->levels as $levelIndex => $levelData) {
        $level = $course->levels()->create([
            'title' => $levelData['title'],
            'order' => $levelIndex + 1,
            'passing_score' => $levelData['passing_score'],
        ]);

        // Handle text contents
        if (!empty($levelData['text_contents'])) {
            $level->text_contents = $levelData['text_contents'];
        }

        // Handle images
        $imagePaths = [];
        if (!empty($levelData['images'])) {
            foreach ($levelData['images'] as $image) {
                $path = $image->store('level_images', 'public');
                $imagePaths[] = $path;
            }
            $level->images = $imagePaths;
        }

        // Handle videos
        $videoPaths = [];
        if (!empty($levelData['videos'])) {
            foreach ($levelData['videos'] as $video) {
                $path = $video->store('level_videos', 'public');
                $videoPaths[] = $path;
            }
            $level->videos = $videoPaths;
        }

        $level->save();

        // Handle questions if exists
        if (!empty($levelData['questions'])) {
            foreach ($levelData['questions'] as $questionData) {
                $level->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'options' => $questionData['options'],
                    'correct_answer' => $questionData['correct_answer'],
                ]);
            }
        }
    }

    return redirect()->route('course.index')->with('success', '✅ Course created successfully!');
}


public function update(Request $request, $id)
{

    
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'coursepreview' => 'required|string|max:255',
        'content' => 'required|string',
        'difficulty_level' => 'required|in:beginner,intermediate,advanced',
        'category_id' => 'required|exists:categories,id',
        'prerequisite_id' => 'nullable|exists:courses,id',
        'levels' => 'required|array|min:1',
    ]);

    // Handle placement test data if enabled
    if ($request->has('enable_placement_test')) {
        $placementQuestions = [];
        $requestQuestions = $request->input('placement_questions', []);
        
        foreach ($requestQuestions as $question) {
            if (!empty($question['text']) && !empty($question['options'])) {
                $placementQuestions[] = [
                    'text' => $question['text'],
                    'options' => $question['options'],
                    'correct' => (int)$question['correct']
                ];
            }
        }
        
        if (count($placementQuestions) < 2) {
            return back()->withErrors(['placement_questions' => 'At least 2 placement questions are required']);
        }
        
        $validated['placement_test'] = json_encode($placementQuestions, JSON_UNESCAPED_UNICODE);
        $validated['placement_pass_score'] = $request->input('placement_pass_score', 70);
    } else {
        $validated['placement_test'] = null;
        $validated['placement_pass_score'] = null;
    }

    $course = Course::with(['levels.questions', 'images'])->findOrFail($id);
    $instructor = Instructor::where('account_id', Auth::id())->first();

    if (!$instructor || $course->instructor_id !== $instructor->id) {
        return redirect()->back()->with('error', 'You do not have permission to edit this course.');
    }

        $deletedData = [
            'images' => json_decode($request->deleted_images, true) ?? [],
            'levels' => json_decode($request->deleted_levels, true) ?? [],
            'questions' => json_decode($request->deleted_questions, true) ?? [],
            'files' => json_decode($request->deleted_files, true) ?? [],
            'thumbnail' => $request->has('deleted_thumbnail'),
            'video' => $request->has('deleted_video')
        ];
        
        // معالجة الصور والفيديوهات والنصوص المحذوفة من المستويات
        foreach ($request->all()['levels'] ?? [] as $level) {
            if (empty($level['id'])) continue;
            
            $levelObj = $course->levels()->find($level['id']);
            if (!$levelObj) continue;
            
            // معالجة الصور المحذوفة
            if (isset($level['removed_images']) && !empty($level['removed_images'])) {
                $currentImages = is_array($levelObj->images) ? $levelObj->images : (json_decode($levelObj->images, true) ?: []);
                
                foreach ($level['removed_images'] as $imageIndex) {
                    $imageIndex = (int) $imageIndex;
                    if (isset($currentImages[$imageIndex])) {
                        \Storage::delete('public/' . $currentImages[$imageIndex]);
                        unset($currentImages[$imageIndex]);
                    }
                }
                
                $levelObj->images = array_values($currentImages);
                $levelObj->save();
            }
            
            // معالجة الفيديوهات المحذوفة
            if (isset($level['removed_videos']) && !empty($level['removed_videos'])) {
                $currentVideos = is_array($levelObj->videos) ? $levelObj->videos : (json_decode($levelObj->videos, true) ?: []);
                
                foreach ($level['removed_videos'] as $videoIndex) {
                    $videoIndex = (int) $videoIndex;
                    if (isset($currentVideos[$videoIndex])) {
                        \Storage::delete('public/' . $currentVideos[$videoIndex]);
                        unset($currentVideos[$videoIndex]);
                    }
                }
                
                $levelObj->videos = array_values($currentVideos);
                $levelObj->save();
            }
            
            // معالجة النصوص المحذوفة
            if (isset($level['removed_texts']) && !empty($level['removed_texts'])) {
                $currentTexts = is_array($levelObj->text_contents) ? $levelObj->text_contents : (json_decode($levelObj->text_contents, true) ?: []);
                
                foreach ($level['removed_texts'] as $textIndex) {
                    $textIndex = (int) $textIndex;
                    if (isset($currentTexts[$textIndex])) {
                        unset($currentTexts[$textIndex]);
                    }
                }
                
                $levelObj->text_contents = array_values($currentTexts);
                $levelObj->save();
            }
        }

        // Placement Test Logic (update)
        if ($request->has('enable_placement_test')) {
            $placementQuestions = $request->input('placement_questions', []);
            $placementPassScore = $request->input('placement_pass_score', 70);
            // Validate question count
            if (count($placementQuestions) < 2 || count($placementQuestions) > 20) {
                throw new \Exception('Placement test must have between 2 and 20 questions.');
            }
            foreach ($placementQuestions as $q) {
                if (!isset($q['options']) || count($q['options']) < 2) {
                    throw new \Exception('Each placement question must have at least 2 options.');
                }
            }
            $placementTestJson = json_encode($placementQuestions, JSON_UNESCAPED_UNICODE);
            $course->placement_test = $placementTestJson;
            $course->placement_pass_score = $placementPassScore;
        } else {
            $course->placement_test = null;
            $course->placement_pass_score = null;
        }
        $course->update([
            'title' => $validated['title'],
            'coursepreview' => $validated['coursepreview'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'prerequisite_id' => $validated['prerequisite_id'],
            'difficulty_level' => $validated['difficulty_level'],
        ]);

// معالجة الصورة المصغرة
if ($request->hasFile('photo')) {
    if ($course->photo) Storage::delete('public/' . $course->photo);
    $course->photo = $request->file('photo')->store('photos', 'public');
} elseif ($request->input('deleted_thumbnail') == '1') {
    // Only delete if explicitly marked for deletion
    if ($course->photo) {
        Storage::delete('public/' . $course->photo);
        $course->photo = null;
    }
}

// معالجة الفيديو
if ($request->hasFile('video')) {
    if ($course->video) Storage::delete('public/' . $course->video);
    $course->video = $request->file('video')->store('videos', 'public');
} elseif ($request->input('deleted_video') == '1') {
    // Only delete if explicitly marked for deletion
    if ($course->video) {
        Storage::delete('public/' . $course->video);
        $course->video = null;
    }
}

// Handle file uploads
if ($request->hasFile('course_files')) {
    foreach ($request->file('course_files') as $file) {
        try {
            $course->uploadFile($file);
        } catch (\Exception $e) {
            // Log error but don't fail the entire request
            \Log::error('File upload failed: ' . $e->getMessage());
        }
    }
}

// Handle file deletions
if (!empty($deletedData['files'])) {
    foreach ($deletedData['files'] as $fileIndex) {
        try {
            $course->deleteFile($fileIndex);
        } catch (\Exception $e) {
            \Log::error('File deletion failed: ' . $e->getMessage());
        }
    }
}
        $course->save();

        // حذف الصور المحددة
        if (!empty($deletedData['images'])) {
            $imagesToDelete = $course->images()->whereIn('id', $deletedData['images'])->get();
            foreach ($imagesToDelete as $image) {
                Storage::delete('public/' . $image->image_path);
                $image->delete();
            }
        }

        // إضافة صور جديدة للكورس
        if ($request->hasFile('course_images')) {
            foreach ($request->file('course_images') as $image) {
                $path = $image->store('course_images', 'public');
                $course->images()->create(['image_path' => $path]);
            }
        }

        // حذف المستويات والأسئلة المحددة
        if (!empty($deletedData['levels'])) {
            CourseLevel::whereIn('id', $deletedData['levels'])
                     ->where('course_id', $course->id)
                     ->delete();
        }

        if (!empty($deletedData['questions'])) {
            Question::whereIn('id', $deletedData['questions'])
                   ->whereHas('level', function($query) use ($course) {
                       $query->where('course_id', $course->id);
                   })
                   ->delete();
        }

        // معالجة المستويات
        foreach ($validated['levels'] as $levelIndex => $levelData) {
            $levelData['order'] = $levelIndex + 1;

            if (isset($levelData['id'])) {
                // تحديث مستوى موجود
                $level = $course->levels()->where('id', $levelData['id'])->first();
                if ($level) {
                    $level->update([
                        'title' => $levelData['title'],
                        'order' => $levelData['order'],
                        'text_contents' => $levelData['text_contents'] ?? [],
                        'passing_score' => $levelData['passing_score'] ?? 70, // إضافة علامة النجاح الافتراضية
                    ]);
                }
            } else {
                // إنشاء مستوى جديد
                $level = $course->levels()->create([
                    'title' => $levelData['title'],
                    'order' => $levelData['order'],
                    'text_contents' => $levelData['text_contents'] ?? [],
                    'images' => [],
                    'videos' => [],
                    'passing_score' => $levelData['passing_score'] ?? 70, // إضافة علامة النجاح الافتراضية
                ]);
            }

            // معالجة الوسائط الجديدة
            if (!empty($levelData['images'])) {
                $imagePaths = $level->images ?? [];
                foreach ($levelData['images'] as $image) {
                    $path = $image->store('level_images', 'public');
                    $imagePaths[] = $path;
                }
                $level->images = $imagePaths;
            }

            if (!empty($levelData['videos'])) {
                $videoPaths = $level->videos ?? [];
                foreach ($levelData['videos'] as $video) {
                    $path = $video->store('level_videos', 'public');
                    $videoPaths[] = $path;
                }
                $level->videos = $videoPaths;
            }

            $level->save();

            // معالجة الأسئلة
            if (!empty($levelData['questions'])) {
                foreach ($levelData['questions'] as $questionData) {
                    if (isset($questionData['id'])) {
                        // تحديث سؤال موجود
                        $question = $level->questions()->where('id', $questionData['id'])->first();
                        if ($question) {
                            $question->update([
                                'question_text' => $questionData['question_text'],
                                'options' => $questionData['options'],
                                'correct_answer' => $questionData['correct_answer'],
                            ]);
                        }
                    } else {
                        // إنشاء سؤال جديد
                        $level->questions()->create([
                            'question_text' => $questionData['question_text'],
                            'options' => $questionData['options'],
                            'correct_answer' => $questionData['correct_answer'],
                        ]);
                    }
                }
            }
        }

        return redirect()->route('course.index')->with('success', '✅ Course updated successfully!');
    }

public function destroy($id)
{
    // Ensure this is a DELETE request
    if (request()->method() !== 'DELETE') {
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid HTTP method.'], 405);
        } else {
            abort(405, 'Method Not Allowed');
        }
    } 

    $course = Course::findOrFail($id);
    $instructor = Instructor::where('account_id', Auth::id())->first();
    if ($instructor && $course->instructor_id === $instructor->id) {
        // حذف جميع البيانات المرتبطة بالدورة بشكل مرتب
        // حذف جميع البيانات المرتبطة بالدورة بشكل مرتب
        // Delete course levels and their questions
        foreach ($course->levels as $level) {
            // Delete questions for this level
            foreach ($level->questions as $question) {
                $question->delete();
            }
            // Delete level media files (images/videos/texts)
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
        // Delete user progress
        $course->userProgress()->delete();
        // Delete course images
        foreach ($course->images as $img) {
            if ($img->image_path) { \Storage::delete('public/' . $img->image_path); }
            $img->delete();
        }
        // Delete course thumbnail and video
        if ($course->photo) { \Storage::delete('public/' . $course->photo); }
        if ($course->video) { \Storage::delete('public/' . $course->video); }
        // Delete comments and ratings
        $course->comments()->delete();
        $course->ratings()->delete();
        // Finally, delete the course
        $course->delete();
        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Course deleted successfully!']);
        } else {
            return redirect()->route('course.index')->with('success', 'Course deleted successfully!');
        }

    } else {
        return redirect()->route('course.index')->with('error', 'You do not have permission to delete this course.');
    }
}



public function edit($id)
{

    
    $course = Course::findOrFail($id);
    
    // Get the instructor
    $instructor = Instructor::where('account_id', Auth::id())->first();
    if (!$instructor || $course->instructor_id !== $instructor->id) {
        return redirect()->route('course.index')->with('error', 'You do not have permission to edit this course.');
    }
    
    // Get all categories for the category dropdown
    $categories = Category::all();
    
    // Get all courses belonging to this instructor
    $instructorCourses = Course::where('instructor_id', $instructor->id)->get();
    
    return view('course.edit', compact('course', 'categories', 'instructorCourses'));
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        public function toggleFavorite(Request $request)
        {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
            ]);
        
            $authId = Auth::id();
            $courseId = $request->input('course_id');
        

            $user = User::where('account_id', $authId)->first();
        

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ]);
            }
        
            $userId = $user->id;
        

            $courseUser = CourseUser::where('user_id', $userId)
                ->where('course_id', $courseId)
                ->first();
        
            if ($courseUser) {

                $courseUser->favorite = !$courseUser->favorite;
                $courseUser->save();
        
                return response()->json([
                    'success' => true,
                    'message' => $courseUser->favorite 
                        ? 'Course has been added to favorites.' 
                        : 'Course has been removed from favorites.',
                    'favorite' => $courseUser->favorite, 
                ]);
            }
        

            CourseUser::create([
                'course_id' => $courseId,
                'user_id' => $userId,
                'favorite' => true,
                'apply' => false, 
            ]);
        
            return response()->json([
                'success' => true,
                'message' => 'Course has been added to favorites.',
                'favorite' => true,
            ]);
        }
        

    // Removed individual getFavoriteStatus as it's replaced by getAllCoursesStatuses
    
    /**
     * Get statuses for all courses at once
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCoursesStatuses()
    {
        $authId = Auth::id();
        $user = User::where('account_id', $authId)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'statuses' => []
            ]);
        }
        
        $userId = $user->id;
        
        // Get all course statuses for this user in a single query
        $courseUsers = CourseUser::where('user_id', $userId)->get();
        
        $statuses = [];
        
        foreach ($courseUsers as $courseUser) {
            $statuses[$courseUser->course_id] = [
                'apply' => $courseUser->apply,
                'favorite' => $courseUser->favorite
            ];
        }
        
        return response()->json([
            'success' => true,
            'statuses' => $statuses
        ]);
    }
    
    /////////////////////////////////////////////////////////////////////////

     public function applyCourse(Request $request)
{
    $request->validate([
        'course_id' => 'required|exists:courses,id',
    ]);

    $authId = Auth::id();
    $courseId = $request->input('course_id');
    $course = Course::findOrFail($courseId);

    // Check if course has a prerequisite
    if ($course->prerequisite_id) {
        // Check if user has completed the prerequisite course
        $prerequisiteCompleted = UserProgress::where('user_id', $authId)
            ->where('course_id', $course->prerequisite_id)
            ->where('completed', true)
            ->exists();
        // Check if user passed placement test for this course
        $placementPassed = UserProgress::where('user_id', $authId)
            ->where('course_id', $course->id)
            ->where('placement_passed', true)
            ->exists();
        if (!$prerequisiteCompleted && !$placementPassed) {
            $prerequisiteCourse = Course::find($course->prerequisite_id);
            return response()->json([
                'success' => false,
                'message' => 'You must complete the prerequisite course first: '.
                            '<a href="'.route('course.show', $prerequisiteCourse->id).'">'.
                            $prerequisiteCourse->title.'</a>',
            ]);
        }
    }

    $user = User::where('account_id', $authId)->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found.',
        ]);
    }

    $userId = $user->id;

    $courseUser = CourseUser::where('user_id', $userId)
        ->where('course_id', $courseId)
        ->first();

    if ($courseUser) {
        if ($courseUser->apply) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this course.',
            ]);
        } else {
            $courseUser->apply = true;
            $courseUser->save();

            return response()->json([
                'success' => true,
                'message' => 'You have successfully applied for this course.',
            ]);
        }
    }

    CourseUser::create([
        'course_id' => $courseId,
        'user_id' => $userId,
        'favorite' => false,
        'apply' => true,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'You have successfully applied for this course.',
    ]);
}

// Individual course status functions have been removed and replaced by getAllCoursesStatuses
/////////////////////////////////////////////////////////////

public function show($id)
{
    $course = Course::with(['levels' => function($query) {
        $query->orderBy('order');
    }])->findOrFail($id);

    $userProgress = null;
    $prerequisiteCompleted = false;
    $placementPassed = false;
    $placementTest = null;
    $placementPassScore = 70; // Default pass score

    if (Auth::check()) {
        $authId = Auth::id();
        $user = User::where('account_id', $authId)->first();
        $userId = $user ? $user->id : null;
        
        // Get user progress for this course
        $userProgress = UserProgress::where('user_id', $authId)
            ->where('course_id', $id)
            ->first();
            
        // Check if user has applied to this course
        $courseApplied = $userId ? CourseUser::where('user_id', $userId)
            ->where('course_id', $id)
            ->where('apply', true)
            ->exists() : false;
            
        // If user has applied but no progress exists, create one
        if ($courseApplied && !$userProgress) {
            $userProgress = UserProgress::create([
                'user_id' => $authId,
                'course_id' => $id,
                'current_level' => 1,
                'completed' => false,
                'placement_passed' => false
            ]);
        }
        
        // Check prerequisite completion
        if ($course->prerequisite_id) {
            $prerequisiteCompleted = UserProgress::where('user_id', $authId)
                ->where('course_id', $course->prerequisite_id)
                ->where('completed', true)
                ->exists();
        } else {
            $prerequisiteCompleted = true; // No prerequisite required
        }
        
        // Check placement test status
        $placementPassed = $userProgress ? (bool)$userProgress->placement_passed : false;
    }

    // Get placement test data
    if ($course->placement_test) {
        $placementTest = json_decode($course->placement_test, true);
        $placementPassScore = $course->placement_pass_score ?? 70;
    }

    return view('course.show', compact('course', 'userProgress', 'prerequisiteCompleted', 'placementPassed', 'placementTest', 'placementPassScore'));
}

// app/Http/Controllers/CourseController.php

public function submitPlacementTest(Request $request, $id)
{
    $request->validate([
        'answers' => 'required|array',
    ]);

    $course = Course::findOrFail($id);
    $userId = Auth::id();
    $userProgress = UserProgress::firstOrCreate([
        'user_id' => $userId,
        'course_id' => $id
    ], [
        'current_level' => 1,
        'completed' => false,
        'placement_passed' => false
    ]);

    // جلب بيانات الاختبار
    $placementTest = $course->placement_test ? json_decode($course->placement_test, true) : [];
    $placementPassScore = $course->placement_pass_score ?? 70;
    $answers = $request->input('answers', []);
    $correctCount = 0;
    $total = count($placementTest);

    foreach ($placementTest as $idx => $q) {
        if (!isset($q['correct'])) {
            // إذا لم يوجد correct، اعتبر السؤال خاطئ أو تجاهله
            continue;
        }
        if (isset($answers[$idx]) && intval($answers[$idx]) === intval($q['correct'])) {
            $correctCount++;
        }
    }
    $score = $total > 0 ? ($correctCount / $total) * 100 : 0;
    if ($score >= $placementPassScore) {
        $userProgress->placement_passed = true;
        $userProgress->save();
        return redirect()->route('course.show', $id)->with('success', 'Congratulations! You passed the placement test and can now apply for the course.');
    } else {
        return redirect()->route('course.show', $id)->with('error', 'Unfortunately, you did not pass the placement test. Please try again later.');
    }
}

////////////////////////////////////////////
public function report(Request $request)
{
    $validated = $request->validate([
        'course_id' => 'required|exists:courses,id',
        'reason' => 'required|string',
        'details' => 'required|string',
    ]);
    
    $report = new CourseReport();
    $report->course_id = $validated['course_id'];
    $report->reporter_id = auth()->id();
    $report->reason = $validated['reason'];
    $report->details = $validated['details'];
    $report->save();
    
    return back()->with('success', 'Your report has been submitted successfully.');
}
///////////////////////////////////
public function submitTest(Request $request, $id)
{
    $request->validate([
        'level_id' => 'required|exists:course_levels,id',
        'answers' => 'required|array',
    ]);

    $level = CourseLevel::with('questions')->findOrFail($request->level_id);
    $userProgress = UserProgress::where('user_id', Auth::id())
                               ->where('course_id', $id)
                               ->firstOrFail();

    // التحقق من الإجابات
    $correctCount = 0;
    $totalQuestions = count($level->questions);
    
    foreach ($level->questions as $question) {
        $userAnswer = $request->answers[$question->id] ?? null;
        if ($userAnswer == $question->correct_answer) {
            $correctCount++;
        }
    }

    // Calculate percentage
    $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
    $passingScore = $level->passing_score ?? 70; // Default to 70% if not set
    $passed = $score >= $passingScore;

    if ($passed) {
        // الانتقال للمستوى التالي أو إكمال الكورس
        $nextLevel = $level->order + 1;
        $maxLevel = CourseLevel::where('course_id', $id)->max('order');

        if ($nextLevel <= $maxLevel) {
            // Update to next level but stay on the same page
            $userProgress->update(['current_level' => $nextLevel]);
            return back()->with('success', 'Congratulations! You passed this level. Your score: ' . number_format($score, 1) . '%');
        } else {
            // Course completed
            $userProgress->update(['completed' => true]);
            return redirect()->route('course.show', $id)
                           ->with('success', "Congratulations! You completed the course. Your score: " . number_format($score, 1) . "%, Passing score: {$passingScore}%.");
        }
    } else {
        return back()->with('error', "You need {$passingScore}% to pass this level. Your score: {$score}%. Please try again.");
    }
}
public function showLevel($id, $level)
{
    $course = Course::with(['levels' => function($query) {
        $query->orderBy('order');
    }])->findOrFail($id);

    $userProgress = UserProgress::firstOrCreate([
        'user_id' => Auth::id(),
        'course_id' => $id
    ], [
        'current_level' => 1,
        'completed' => false
    ]);

    // التأكد من أن المستخدم في المستوى الصحيح
    if ($userProgress->current_level != $level) {
        return redirect()->route('course.show', $id);
    }

    return view('course.show', compact('course', 'userProgress'));
}




public function inProgressCourses()
{
    $userId = Auth::id();
    $courses = UserProgress::with('course')
        ->where('user_id', $userId)
        ->where('completed', 0)
        ->get();
            
    return view('profile.in-progress-courses', compact('courses'));
}

public function completedCourses()
{
    $userId = Auth::id();
    $courses = UserProgress::with('course')
        ->where('user_id', $userId)
        ->where('completed', 1)
        ->get();
        
    return view('profile.completed-courses', compact('courses'));
}
}
