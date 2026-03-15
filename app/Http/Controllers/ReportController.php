<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TemperatureReading;
use App\Models\MaintenanceTask;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function exportTemperaturePDF()
    {
        $readings = TemperatureReading::with(['room', 'recorder'])->orderBy('recorded_at', 'desc')->take(100)->get();
        $pdf = Pdf::loadView('reports.temperature', compact('readings'));
        return $pdf->download('temperature_trends.pdf');
    }

    public function exportMaintenanceCSV()
    {
        $tasks = MaintenanceTask::with(['room', 'technician'])->get();
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=maintenance_costs.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use ($tasks) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Room', 'Issue', 'Status', 'Cost', 'Technician']);
            foreach ($tasks as $task) {
                fputcsv($file, [
                    $task->id,
                    $task->room->name ?? 'N/A',
                    $task->issue_description,
                    $task->status,
                    $task->cost,
                    $task->technician->name ?? 'N/A'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
