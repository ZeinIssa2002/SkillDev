<?php 

namespace App\Http\Controllers\Auth;

use App\Models\Account;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Profile;
use App\Models\TermsCondition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:account,email|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isInstructor = $request->has('is_instructor');
        $email = $request->email;
        $username = explode('@', $email)[0] . '_' . substr(md5(time()), 0, 6); 

        $account = new Account();
        $account->username = $username; 
        $account->email = $email;
        $account->password = Hash::make($request->password);
        $account->account_type = $isInstructor ? 'instructor' : 'user';
        $account->save();

        $profile = new Profile();
        $profile->profilename = $username; 
        $profile->profileinfo = $request->input('profileinfo', 'Hello there!');
        $profile->account_id = $account->id;
        $profile->save();

        $account->profile_id = $profile->profile_id;
        $account->save();

        if ($isInstructor) {
            $instructor = new Instructor();
            $instructor->account_id = $account->id;
            $instructor->profile_id = $profile->profile_id;
            $instructor->save();

            $account->instructor_id = $instructor->id;
            $profile->instructor_id = $instructor->id;
        } else {
            $user = new User();
            $user->account_id = $account->id;
            $user->profile_id = $profile->profile_id;
            $user->save();

            $account->user_id = $user->id;
            $profile->user_id = $user->id;
        }

        $profile->save();
        $account->save();

        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }

}