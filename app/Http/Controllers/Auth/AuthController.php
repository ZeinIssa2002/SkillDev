<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = Account::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'Email does not exist.']);
        }
    
        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);
    
            return $this->redirectToProperPage($user);
    
        } else {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
    }

    public function loginAsGuest()
    {
 
        $guestAccount = new Account();
        $guestAccount->username = 'guest_' . Str::random(8);
        $guestAccount->email = 'guest_' . Str::random(8) . '@temp.example';
        $guestAccount->password = Hash::make(Str::random(32));
        $guestAccount->account_type = 'guest';
        $guestAccount->save();

        Auth::login($guestAccount);

        return $this->redirectToProperPage($guestAccount)
            ->with('info', 'You are using a temporary guest account without profile');
    }

    protected function redirectToProperPage($user)
    {
        switch ($user->account_type) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome ' . $user->username);
            
            case 'instructor':
                return redirect()->route('homepage')
                    ->with('success', 'Welcome ' . $user->username);
            
            case 'guest':

                return redirect()->route('homepage')
                    ->with('info', 'You are browsing as guest. Some features may be limited.');
            
            default: // user
                return redirect()->route('homepage');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function Homepage()
    {
        if (Auth::check()) {
 
            $userType = auth()->user()->account_type;
            return view('homepage', compact('userType'));
        }
        return redirect()->route('logout');
    }


    public static function cleanupGuestAccounts()
    {
        $expiryDate = Carbon::now()->subDays(1); 
        Account::where('account_type', 'guest')
               ->where('created_at', '<', $expiryDate)
               ->delete();
    }
}