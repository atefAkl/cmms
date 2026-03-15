<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Services\ProcurementService;
use Illuminate\Http\Request;

class ProcurementApiController extends Controller
{
    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }

    public function index()
    {
        return response()->json(PurchaseOrder::with(['supplier', 'warehouse', 'items.inventoryItem'])->latest()->get());
    }

    public function approve(PurchaseOrder $purchaseRecord)
    {
        $this->procurementService->approveOrder($purchaseRecord);
        return response()->json(['message' => 'Order approved successfully', 'order' => $purchaseRecord]);
    }

    public function reject(PurchaseOrder $purchaseRecord)
    {
        $this->procurementService->rejectOrder($purchaseRecord);
        return response()->json(['message' => 'Order rejected', 'order' => $purchaseRecord]);
    }
}
