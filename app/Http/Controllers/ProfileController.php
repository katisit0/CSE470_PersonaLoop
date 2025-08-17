<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Journal;

class ProfileController extends Controller
{
    // Show the profile page
    public function index()
    {
        $user = Auth::user();

        // Eager load related data
        $user->load('personas', 'achievements', 'journals');
        $pastJournals = Journal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.index', compact('user', 'pastJournals'));
    }

    // Update basic profile info (name, email, public/private toggle)
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'is_profile_public' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'is_profile_public' => $request->has('is_profile_public'),
        ]);

        return redirect()->back()->with('success', 'Profile info updated.');
    }

    // Delete account
    public function destroy()
    {
        $user = Auth::user();

        Auth::logout();
        $user->delete();

        return redirect('/')->with('success', 'Your profile has been deleted.');
    }
}