<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function dashboard()
    {

        return view('admin.index');
    }

    /* Language Translation */


    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "User not found!"
            ], 404);
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        if ($user->update()) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200);
        } else {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Something went wrong!"
            ], 500); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->get('current_password'), $user->password)) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your current password does not match the password you provided. Please try again."
            ], 400); // Status code here
        }

        $user->password = Hash::make($request->get('password'));

        if ($user->update()) {
            Session::flash('message', 'Password updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Password updated successfully!"
            ], 200);
        } else {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Something went wrong!"
            ], 500); // Status code here
        }
    }
}
