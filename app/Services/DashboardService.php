<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Alert;
use App\Models\MaintenanceTask;
use App\Models\TemperatureReading;
use App\Models\Inspection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class DashboardService
{
    /**
     * Get manager dashboard statistics using Redis caching.
     * Cache duration: 5 minutes (300 seconds).
     */
    public function getManagerStats(): array
    {
        return Cache::remember('manager_dashboard_stats', 300, function () {
            return [
                'rooms' => Room::with('compressors', 'evaporator')->get(),
                'active_alerts' => Alert::where('status', 'open')->count(),
                'equipment_health' => $this->calculateEquipmentHealth(),
                'maintenance_backlog' => MaintenanceTask::whereIn('status', ['open', 'diagnosed', 'assigned', 'in_progress'])->count(),
                'monthly_maintenance_cost' => MaintenanceTask::whereMonth('created_at', Carbon::now()->month)->sum('cost'),
                
                // For Charts
                'temperature_trends' => $this->getTemperatureTrends(),
                'maintenance_costs_chart' => $this->getMaintenanceCosts(),
                'alert_frequency' => $this->getAlertFrequency(),
            ];
        });
    }

    /**
     * Get maintenance technician dashboard statistics using Redis caching.
     */
    public function getMaintenanceStats(int $technicianId): array
    {
        return Cache::remember("maintenance_dashboard_stats_{$technicianId}", 300, function () use ($technicianId) {
            return [
                'today_inspections' => Inspection::whereDate('scheduled_date', Carbon::today())
                                                 ->where('technician_id', $technicianId)
                                                 ->get(),
                'assigned_tasks' => MaintenanceTask::where('technician_id', $technicianId)
                                                   ->whereIn('status', [MaintenanceTask::STATUS_ASSIGNED, MaintenanceTask::STATUS_IN_PROGRESS])
                                                   ->with(['room', 'compressor'])
                                                   ->get(),
                'open_alerts' => Alert::where('status', 'open')->latest()->take(10)->get(),
            ];
        });
    }

    private function calculateEquipmentHealth()
    {
        // Placeholder for health calculation logic
        $total = \App\Models\Compressor::count() + \App\Models\Evaporator::count();
        $faulty = \App\Models\Compressor::where('status', 'faulty')->count() + \App\Models\Evaporator::where('status', 'faulty')->count();

        if ($total == 0) return 100;
        return round((($total - $faulty) / $total) * 100);
    }

    private function getTemperatureTrends()
    {
        // Last 7 days readings
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $labels[] = $date;
            $avg = TemperatureReading::whereDate('created_at', $date)->avg('temperature') ?? 0;
            $data[] = round($avg, 2);
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getMaintenanceCosts()
    {
        // Last 6 months costs
        $labels = [];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $cost = MaintenanceTask::whereMonth('created_at', $month->month)
                                   ->whereYear('created_at', $month->year)
                                   ->sum('cost');
            $data[] = $cost;
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getAlertFrequency()
    {
        // Alerts by severity
        $severities = Alert::selectRaw('severity, count(*) as count')->groupBy('severity')->pluck('count', 'severity');
        return [
            'labels' => $severities->keys()->toArray(),
            'data' => $severities->values()->toArray()
        ];
    }
}
