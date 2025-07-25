<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Profile;
use App\Models\User;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{


    // صفحة البرايفيسي
    public function edit()
    {
        $account = Auth::user(); 
        $profile = $account->profile; 

        $profilename = $profile->profilename;
        $profileinfo = $profile->profileinfo;

        return view('profile.edit', [
            'user' => $account, 
            'username' => $profilename, 
            'bio' => $profileinfo,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $account = Auth::user();
        $profile = $account->profile;
    
        $request->validate([
            'profilename' => 'required|string|max:255|unique:account,username,' . $account->id,
            'profileinfo' => 'nullable|string|max:1000',
        ]);
    
        if ($profile) {
            $profile->profilename = $request->input('profilename');
            $profile->profileinfo = $request->input('profileinfo');
            $profile->save();
    
            $account->username = $request->input('profilename');
            $account->save();
        }
    
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
    


    public function updateAccount(Request $request)
    {


        $request->validate([
            'new_email' => 'required|email|unique:account,email,' . Auth::id(), 
            'old_password' => 'required', 
            'new_password' => 'nullable|string|min:8|confirmed', 
        ]);
    
        $account = Auth::user();
    

        if (!Hash::check($request->input('old_password'), $account->password)) {
            return redirect()->route('profile.edit')->withErrors(['old_password' => 'The provided password is incorrect.']);
        }
    

        if ($request->filled('new_email')) {
            $account->email = $request->input('new_email');
        }
    

        if ($request->filled('new_password')) {
            $account->password = Hash::make($request->input('new_password')); 
        }
    

        $account->save();
    
        return redirect()->route('profile.edit')->with('success', 'Account updated successfully!');
    }

    public function deleteProfileInfo()
{

    $account = Auth::user(); 
    $profile = $account->profile; 

    if ($profile && $profile->profileinfo) {
        $profile->profileinfo = null; 
        $profile->save();
    }

    return redirect()->back()->with('success', 'Profile info deleted successfully!');
}

public function search(Request $request)
{
    $searchTerm = $request->input('search');


    $courses = Course::where('title', 'like', '%' . $searchTerm . '%')
                     ->orWhere('coursepreview', 'like', '%' . $searchTerm . '%')
                     ->get();


    $accounts = Account::whereHas('profile', function($query) use ($searchTerm) {
                        $query->where('profilename', 'like', '%' . $searchTerm . '%')
                              ->orWhere('profileinfo', 'like', '%' . $searchTerm . '%');
                     })
                     ->whereIn('account_type', ['instructor', 'user'])
                     ->with('profile')
                     ->get();

    return view('search', compact('courses', 'accounts'));
}

public function showProfile()
{
    $auth_id = Auth::id(); 
    $profile = Profile::where('account_id', $auth_id)->first();
    $account = Account::where('id', $auth_id)->first();
    

    $instructor = null;
    if ($account && $account->account_type === 'instructor') {
        $instructor = Instructor::where('profile_id', $profile->profile_id)->first();
        

        if ($instructor && $instructor->rating === null) {
            $instructor->rating = 0;
        }
    }
    
    return view('profile.profile', compact('profile', 'account', 'instructor'));
}
  
public function profileshowdisplay($profile_id)
{
    $profile = Profile::where('profile_id', $profile_id)->firstOrFail();
    $account = $profile->account;

 
    $isInstructor = ($account->account_type === 'instructor');
    
    if ($isInstructor) {
        $instructor = Instructor::where('profile_id', $profile->profile_id)->firstOrFail();
        return view('profile.show', compact('profile', 'account', 'instructor', 'profile_id', 'isInstructor'));
    }

    return view('profile.show', compact('profile', 'account', 'profile_id', 'isInstructor'));
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
public function FavoriteCourse()
{

    $auth_id = Auth::id();
    
    $user = User::where('account_id', $auth_id)->first();
    
    $userId = $user->id;
    

    $favoriteCourseIds = CourseUser::where('user_id', $userId)
        ->where('favorite', 1)
        ->pluck('course_id');
    

    $favoriteCourses = Course::whereIn('id', $favoriteCourseIds)->get();

    return view('profile.favorite-courses', ['courses' => $favoriteCourses]);
}



public function AppliedCourses()
{

    $auth_id = Auth::id();
    
    $user = User::where('account_id', $auth_id)->first();
    
    $userId = $user->id;
    

    $appliedCourseIds = CourseUser::where('user_id', $userId)
        ->where('apply', 1)
        ->pluck('course_id');
    

    $appliedCourses = Course::whereIn('id', $appliedCourseIds)->get();
    
    return view('profile.applied-courses', ['courses' => $appliedCourses]);
}

public function photoProfile(Request $request, $profileId)
{
    $profile = Profile::findOrFail($profileId);


    $request->validate([
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('photo')) {

        $path = $request->file('photo')->store('profile_photos', 'public');
        

        $profile->photo = $path;
    }


    $profile->save();

    return redirect()->route('profile.show', $profile->profile_id)
                     ->with('success', 'Profile updated successfully!');
}
public function deletePhoto($profileId)
{

    $profile = Profile::findOrFail($profileId);

    if ($profile->photo) {

        Storage::disk('public')->delete($profile->photo);


        $profile->photo = null;
        $profile->save();
    }

    return redirect()->route('profile.show', $profile->profile_id)
                     ->with('success', 'Photo Deleted!');
}

/////////////////////////////////////////////////////////
public function showWorkHours()
{  


    // Get instructor data
    $instructor = Instructor::where('account_id', auth()->id())->first();

    return view('profile.instructor-workhours', [
        'instructor' => $instructor,
        'workHours' => $instructor->workhours ?? ''
    ]);
}

public function updateWorkHours(Request $request)
{   


    $request->validate([
        'workhours' => 'required|string|max:255'
    ]);

 
    $instructor = Instructor::where('account_id', auth()->id())->first();
    $instructor->workhours = $request->workhours;
    $instructor->save();

    return redirect()->route('instructor.workhours')
        ->with('success', 'Work hours updated successfully!');
}
//////////////////////////////////////////////////////////////////////////////////
public function toggleHide(Request $request, $profile_id)
{

    $profile = Profile::findOrFail($profile_id);
    $profile->hide = !$profile->hide;
    $profile->save();

    return back()->with('success', 'Privacy settings updated successfully');
}
//////////////////////////////////////////////
public function instructorCourses($id)
{
    $account = Account::findOrFail($id);
    $instructor = Instructor::where('account_id', $account->id)->first(); 
    $courses = Course::where('instructor_id', $instructor->id)->get();
    return view('course.indexuser', compact('courses', 'account'));
}
}
