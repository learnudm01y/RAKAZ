<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page
     * Data is loaded via AJAX after page load
     */
    public function index()
    {
        // Return the view without any data
        // All data will be loaded via AJAX to improve initial page load time
        return view('dashboard');
    }
}
