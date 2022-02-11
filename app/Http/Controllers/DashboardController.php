<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // show the users current (incomplete) checklists
        $user = Auth::user();
        $checklists = $user->checklists()->incomplete()->get();

        return view('dashboard', compact('user', 'checklists'));
    }
}
