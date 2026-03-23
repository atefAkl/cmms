<?php

namespace App\Http\Controllers;

use App\Models\RefrigerationSystem;
use App\Services\AssetInitializationService;
use Illuminate\Http\Request;

class SystemInitializationController extends Controller
{
    protected $initializationService;

    public function __construct(AssetInitializationService $initializationService)
    {
        $this->initializationService = $initializationService;
    }

    public function store(Request $request, RefrigerationSystem $refrigeration_system)
    {
        $validated = $request->validate([
            'compressors_count' => 'required|integer|min:1|max:10',
            'evaporators_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            $this->initializationService->initialize($refrigeration_system, $validated);
            return back()->with('success', 'System successfully scaled with base components.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
