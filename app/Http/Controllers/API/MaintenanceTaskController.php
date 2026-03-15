<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceTask;
use App\Http\Resources\API\MaintenanceTaskResource;
use Illuminate\Http\Request;

class MaintenanceTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = MaintenanceTask::with(['room', 'compressor', 'technician'])->latest()->paginate(10);
        return MaintenanceTaskResource::collection($tasks);
    }
}
