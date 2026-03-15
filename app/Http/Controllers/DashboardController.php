<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('Manager')) {
            return $this->managerDashboard();
        } elseif ($user->hasRole('Maintenance Officer')) {
            return $this->maintenanceDashboard();
        }

        abort(403, 'Unauthorized action.');
    }

    protected function managerDashboard()
    {
        $rooms = \App\Models\Room::with('compressors', 'evaporator')->get();
        $activeAlerts = \Illuminate\Support\Facades\Cache::remember('manager_active_alerts', 60, fn() => \App\Models\Alert::where('status', 'open')->count());
        $activeMaintenance = \Illuminate\Support\Facades\Cache::remember('manager_active_maintenance', 60, fn() => \App\Models\MaintenanceTask::whereIn('status', ['open', 'in_progress', 'diagnosed'])->count());
        $totalCost = \Illuminate\Support\Facades\Cache::remember('manager_total_cost', 60, fn() => \App\Models\MaintenanceTask::sum('cost'));

        return view('dashboard.manager', compact('rooms', 'activeAlerts', 'activeMaintenance', 'totalCost'));
    }

    protected function maintenanceDashboard()
    {
        $openTasks = \App\Models\MaintenanceTask::where('technician_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress'])->get();
        $recentAlerts = \App\Models\Alert::where('status', 'open')->latest()->take(5)->get();

        return view('dashboard.maintenance', compact('openTasks', 'recentAlerts'));
    }
}
