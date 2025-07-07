<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

class DashboardController extends Controller
{
    /**
     * Show the dashboard based on user role.
     * Using data from UserRoleMiddleware
     */
    public function index(Request $request)
    {
        if ($request->is_admin) {
            // Use the admin dashboard controller to get dynamic data
            $adminController = new AdminDashboardController();
            return $adminController->index();
        } else {
            return view('customer.index');
        }
    }
}