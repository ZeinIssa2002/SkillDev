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

    public function index()
    {
        $user = Auth::user();
    
        if ($user->account_type == 'instructor') {
            $instructor = Instructor::where('account_id', $user->id)->first();
    

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


    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('photos', 'public');
        $validated['photo'] = $photoPath;
    }

 
    if ($request->hasFile('video')) {
        $videoPath = $request->file('video')->store('videos', 'public');
        $validated['video'] = $videoPath;
    }

  
    $courseImages = [];
    if ($request->hasFile('course_images')) {
        foreach ($request->file('course_images') as $image) {
            $path = $image->store('course_images', 'public');
            $courseImages[] = ['image_path' => $path];
        }
    }

 
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

 
    $course = Course::create($validated);

  
    if (!empty($courseImages)) {
        $course->images()->createMany($courseImages);
    }
    
    
    if ($request->hasFile('course_files')) {
        $uploadedFiles = [];
        $existingFiles = is_array($course->files) ? $course->files : [];
        
        foreach ($request->file('course_files') as $file) {
            try {
             
                $validated = $request->validate([
                    'course_files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:20480', // 20MB max
                ]);
                
                
                $path = $file->store('course_files/' . $course->id, 'public');
                
               
                $uploadedFiles[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                    'uploaded_at' => now()->toDateTimeString()
                ];
            } catch (\Exception $e) {
                
                \Log::error('File upload failed: ' . $e->getMessage());
            }
        }
        
        
        $allFiles = array_merge($existingFiles, $uploadedFiles);
        $course->files = $allFiles;
        $course->save();
    }

   
    foreach ($request->levels as $levelIndex => $levelData) {
        $level = $course->levels()->create([
            'title' => $levelData['title'],
            'order' => $levelIndex + 1,
            'passing_score' => $levelData['passing_score'],
        ]);

     
        if (!empty($levelData['text_contents'])) {
            $level->text_contents = $levelData['text_contents'];
        }

      
        $imagePaths = [];
        if (!empty($levelData['images'])) {
            foreach ($levelData['images'] as $image) {
                $path = $image->store('level_images', 'public');
                $imagePaths[] = $path;
            }
            $level->images = $imagePaths;
        }

      
        $videoPaths = [];
        if (!empty($levelData['videos'])) {
            foreach ($levelData['videos'] as $video) {
                $path = $video->store('level_videos', 'public');
                $videoPaths[] = $path;
            }
            $level->videos = $videoPaths;
        }

        $level->save();

    
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
        
      
        foreach ($request->all()['levels'] ?? [] as $level) {
            if (empty($level['id'])) continue;
            
            $levelObj = $course->levels()->find($level['id']);
            if (!$levelObj) continue;
            
          
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

      
        if ($request->has('enable_placement_test')) {
            $placementQuestions = $request->input('placement_questions', []);
            $placementPassScore = $request->input('placement_pass_score', 70);
        
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


if ($request->hasFile('photo')) {
    if ($course->photo) Storage::delete('public/' . $course->photo);
    $course->photo = $request->file('photo')->store('photos', 'public');
} elseif ($request->input('deleted_thumbnail') == '1') {
    
    if ($course->photo) {
        Storage::delete('public/' . $course->photo);
        $course->photo = null;
    }
}


if ($request->hasFile('video')) {
    if ($course->video) Storage::delete('public/' . $course->video);
    $course->video = $request->file('video')->store('videos', 'public');
} elseif ($request->input('deleted_video') == '1') {
    
    if ($course->video) {
        Storage::delete('public/' . $course->video);
        $course->video = null;
    }
}


if ($request->hasFile('course_files')) {
    foreach ($request->file('course_files') as $file) {
        try {
            $course->uploadFile($file);
        } catch (\Exception $e) {
            
            \Log::error('File upload failed: ' . $e->getMessage());
        }
    }
}


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

        
        if (!empty($deletedData['images'])) {
            $imagesToDelete = $course->images()->whereIn('id', $deletedData['images'])->get();
            foreach ($imagesToDelete as $image) {
                Storage::delete('public/' . $image->image_path);
                $image->delete();
            }
        }

       
        if ($request->hasFile('course_images')) {
            foreach ($request->file('course_images') as $image) {
                $path = $image->store('course_images', 'public');
                $course->images()->create(['image_path' => $path]);
            }
        }

     
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

     
        foreach ($validated['levels'] as $levelIndex => $levelData) {
            $levelData['order'] = $levelIndex + 1;

            if (isset($levelData['id'])) {
             
                $level = $course->levels()->where('id', $levelData['id'])->first();
                if ($level) {
                    $level->update([
                        'title' => $levelData['title'],
                        'order' => $levelData['order'],
                        'text_contents' => $levelData['text_contents'] ?? [],
                        'passing_score' => $levelData['passing_score'] ?? 70, 
                    ]);
                }
            } else {
              
                $level = $course->levels()->create([
                    'title' => $levelData['title'],
                    'order' => $levelData['order'],
                    'text_contents' => $levelData['text_contents'] ?? [],
                    'images' => [],
                    'videos' => [],
                    'passing_score' => $levelData['passing_score'] ?? 70, 
                ]);
            }

           
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

           
            if (!empty($levelData['questions'])) {
                foreach ($levelData['questions'] as $questionData) {
                    if (isset($questionData['id'])) {
                        
                        $question = $level->questions()->where('id', $questionData['id'])->first();
                        if ($question) {
                            $question->update([
                                'question_text' => $questionData['question_text'],
                                'options' => $questionData['options'],
                                'correct_answer' => $questionData['correct_answer'],
                            ]);
                        }
                    } else {
                       
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
            return redirect()->route('course.index')->with('success', 'Course deleted successfully!');
        }

    } else {
        return redirect()->route('course.index')->with('error', 'You do not have permission to delete this course.');
    }
}



public function edit($id)
{

    
    $course = Course::findOrFail($id);
    
    
    $instructor = Instructor::where('account_id', Auth::id())->first();
    if (!$instructor || $course->instructor_id !== $instructor->id) {
        return redirect()->route('course.index')->with('error', 'You do not have permission to edit this course.');
    }
    
   
    $categories = Category::all();
    
   
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

   
    if ($course->prerequisite_id) {
       
        $prerequisiteCompleted = UserProgress::where('user_id', $authId)
            ->where('course_id', $course->prerequisite_id)
            ->where('completed', true)
            ->exists();
     
        $placementPassed = UserProgress::where('user_id', $authId)
            ->where('course_id', $course->id)
            ->where('placement_passed', true)
            ->exists();
        if (!$prerequisiteCompleted && !$placementPassed) {
            $prerequisiteCourse = Course::find($course->prerequisite_id);
            $message = '<div class="d-flex align-items-center">'.

                     '<div>Before you can enroll in this course, you need to complete the prerequisite course: '.
                     '<a href="'.route('course.show', $prerequisiteCourse->id).'" class="fw-bold text-primary">'.
                     $prerequisiteCourse->title.'</a></div></div>';
            
            return response()->json([
                'success' => false,
                'message' => $message,
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
    $placementPassScore = 70; 

    if (Auth::check()) {
        $authId = Auth::id();
        $user = User::where('account_id', $authId)->first();
        $userId = $user ? $user->id : null;
        
    
        $userProgress = UserProgress::where('user_id', $authId)
            ->where('course_id', $id)
            ->first();
            
    
        $courseApplied = $userId ? CourseUser::where('user_id', $userId)
            ->where('course_id', $id)
            ->where('apply', true)
            ->exists() : false;
            
     
        if ($courseApplied && !$userProgress) {
            $userProgress = UserProgress::create([
                'user_id' => $authId,
                'course_id' => $id,
                'current_level' => 1,
                'completed' => false,
                'placement_passed' => false
            ]);
        }
        
       
        if ($course->prerequisite_id) {
            $prerequisiteCompleted = UserProgress::where('user_id', $authId)
                ->where('course_id', $course->prerequisite_id)
                ->where('completed', true)
                ->exists();
        } else {
            $prerequisiteCompleted = true;
        }
        
      
        $placementPassed = $userProgress ? (bool)$userProgress->placement_passed : false;
    }


    if ($course->placement_test) {
        $placementTest = json_decode($course->placement_test, true);
        $placementPassScore = $course->placement_pass_score ?? 70;
    }

    return view('course.show', compact('course', 'userProgress', 'prerequisiteCompleted', 'placementPassed', 'placementTest', 'placementPassScore'));
}



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

   
    $placementTest = $course->placement_test ? json_decode($course->placement_test, true) : [];
    $placementPassScore = $course->placement_pass_score ?? 70;
    $answers = $request->input('answers', []);
    $correctCount = 0;
    $total = count($placementTest);

    foreach ($placementTest as $idx => $q) {
        if (!isset($q['correct'])) {
           
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

   
    $correctCount = 0;
    $totalQuestions = count($level->questions);
    
    foreach ($level->questions as $question) {
        $userAnswer = $request->answers[$question->id] ?? null;
        if ($userAnswer == $question->correct_answer) {
            $correctCount++;
        }
    }

   
    $score = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;
    $passingScore = $level->passing_score ?? 70; 
    $passed = $score >= $passingScore;

    if ($passed) {
        
        $nextLevel = $level->order + 1;
        $maxLevel = CourseLevel::where('course_id', $id)->max('order');

        if ($nextLevel <= $maxLevel) {
            
            $userProgress->update(['current_level' => $nextLevel]);
            $message = "You passed this level.\nYour score: " . number_format($score, 1) . '%';
            return back()->with('success', $message);
        } else {
            $userProgress->update(['completed' => true]);
            $message = "You completed the course!\nYour score: " . number_format($score, 1) . "%\nPassing score: {$passingScore}%.";
            return redirect()->route('course.show', $id)->with('success', $message);
        }
    } else {
        $message = "You need {$passingScore}% to pass this level.\nYour score: {$score}%\nPlease try again.";
        return back()->with('error', $message);
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
