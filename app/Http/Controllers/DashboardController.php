<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller {
    /**
     * Display the user's dashboard
     *
     * @return View
     */
    public function dashboard(): View {
        // show the users current (incomplete) checklists
        $user = Auth::user();
        $checklists = $user->checklists()->incomplete()->get();

        return view("dashboard", compact("user", "checklists"));
    }
}
