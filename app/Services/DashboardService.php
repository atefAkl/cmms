<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Alert;
use App\Models\MaintenanceTask;
use App\Models\TemperatureReading;
use App\Models\Inspection;
use App\Models\PmTask;
use App\Models\RefrigerationSystem;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
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
                'rooms' => Room::with('refrigerationSystems.assets')->get(),
                'active_alerts' => Alert::where('status', 'open')->count(),
                'equipment_health' => $this->calculateEquipmentHealth(),
                'upcoming_pm' => PmTask::where('status', PmTask::STATUS_PENDING)
                                       ->where('scheduled_date', '>', Carbon::today())
                                       ->orderBy('scheduled_date')
                                       ->take(5)
                                       ->with('schedule')
                                       ->get(),
                'maintenance_backlog' => MaintenanceTask::whereIn('status', [
                                            MaintenanceTask::STATUS_OPEN, 
                                            MaintenanceTask::STATUS_DIAGNOSED, 
                                            MaintenanceTask::STATUS_ASSIGNED, 
                                            MaintenanceTask::STATUS_IN_PROGRESS
                                        ])->count(),
                'monthly_maintenance_cost' => MaintenanceTask::whereMonth('created_at', Carbon::now()->month)->sum('cost'),
                
                // Inventory & Procurement Stats
                'low_stock_count' => InventoryItem::whereColumn('stock', '<=', 'min_stock_level')->count(),
                'pending_purchases_count' => PurchaseOrder::where('status', 'pending')->count(),
                'total_inventory_value' => InventoryItem::selectRaw('SUM(stock * cost) as total')->value('total') ?? 0,

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
                'today_pm' => PmTask::whereDate('scheduled_date', Carbon::today())
                                    ->where('status', PmTask::STATUS_PENDING)
                                    ->with('schedule.equipment')
                                    ->get(),
                'today_inspections' => Inspection::whereDate('scheduled_date', Carbon::today())
                                                 ->where('technician_id', $technicianId)
                                                 ->get(),
                'assigned_tasks' => MaintenanceTask::where('technician_id', $technicianId)
                                                   ->whereIn('status', [MaintenanceTask::STATUS_ASSIGNED, MaintenanceTask::STATUS_IN_PROGRESS])
                                                   ->with(['room', 'asset', 'refrigerationSystem'])
                                                   ->get(),
                'open_alerts' => Alert::where('status', 'open')->latest()->take(10)->get(),
            ];
        });
    }

    private function calculateEquipmentHealth()
    {
        $totalSystems = RefrigerationSystem::count();
        $faultySystems = RefrigerationSystem::where('status', 'inactive')->count();
        
        $totalAssets = \App\Models\Asset::count();
        $faultyAssets = \App\Models\Asset::whereIn('status', ['faulty', 'inactive'])->count();

        $totalCount = $totalSystems + $totalAssets;
        $faultyCount = $faultySystems + $faultyAssets;

        if ($totalCount == 0) return 100;
        return round((($totalCount - $faultyCount) / $totalCount) * 100);
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
