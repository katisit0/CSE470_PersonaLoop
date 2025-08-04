<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Persona;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get the persona selected *today*
        $persona = $user->personas()->wherePivot('date', today())->first();

        return view('dashboard', compact('persona'));
    }
}