<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login
     */



     public function login(Request $request)
     {
         $request->validate([
             'login' => 'required|string',
             'password' => 'required',
         ], [
             'login.required' => __('auth.invalid_credentials'),
             'password.required' => __('auth.invalid_credentials'),
         ]);

         $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

         $user = User::where($loginType, $request->login)->first();

         if (!$user) {
             throw ValidationException::withMessages([
                 'login' => [__('auth.invalid_credentials')],
             ]);
         }

         $guard = $user->getAuthGuard();

         if (Auth::guard($guard)->attempt([$loginType => $request->login, 'password' => $request->password], $request->remember)) {

             return redirect()->route('root');
         }

         throw ValidationException::withMessages([
             'login' => [__('auth.invalid_credentials')],
         ]);
     }


    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $guard = Auth::getDefaultDriver();
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
