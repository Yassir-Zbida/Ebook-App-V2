<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role.
     * Using data from UserRoleMiddleware
     */
    public function index(Request $request)
    {
        if ($request->is_admin) {
            return view('admin.index');
        } else {
            return view('customer.index');
        }
    }
}