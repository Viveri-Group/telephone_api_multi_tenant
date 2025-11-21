<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Dashboard\GetDashboardStatsAction;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render(
            'Auth/Dashboard',
            [
            ]
        );
    }
}
