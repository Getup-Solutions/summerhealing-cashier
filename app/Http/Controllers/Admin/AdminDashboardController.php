<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function home()
    {
        return Inertia::render('Admin/Dashboard/Home');

    }

    // public function profileInfo()
    // {
    //     return Inertia::render('AdminDashboard/ProfileInfo', [
    //         'user' => Auth::guard('web')->user(),
    //     ]);

    // }
}
