<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Budget;
use App\Models\Invoice;
use Carbon\Carbon;

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


    public function root(Request $request)
    {
        $today = Carbon::today();

        // Determine fiscal year start (July 1)
        $fiscalYearStart = $today->month < 7
            ? Carbon::create($today->year - 1, 7, 1)
            : Carbon::create($today->year, 7, 1);

        // Fiscal year end is always June 30 of the *next* year
        $fiscalYearEnd = $fiscalYearStart->copy()->addYear()->subDay();

        $startDate = $request->input('start_date') ?? $fiscalYearStart->toDateString();
        $endDate = $request->input('end_date') ?? $fiscalYearEnd->toDateString();

        $totalRevenues = Budget::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalExpenses = Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $difference = $totalRevenues - $totalExpenses;

        return view('admin.index', compact(
            'startDate',
            'endDate',
            'totalRevenues',
            'totalExpenses',
            'difference'
        ));
    }



    /* Language Translation */
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        }
        return redirect()->back();
    }

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
            ], 404); // Status code here
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
