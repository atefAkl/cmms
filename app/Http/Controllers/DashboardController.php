<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Manager')) {
            $stats = $this->dashboardService->getManagerStats();
            return view('dashboard.manager', compact('stats'));
        } elseif ($user->hasRole('Maintenance Officer') || $user->hasRole('Technician')) {
            $stats = $this->dashboardService->getMaintenanceStats($user->id);
            return view('dashboard.maintenance', compact('stats'));
        }

        abort(403, 'Unauthorized action.');
    }
}
