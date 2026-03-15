<?php

namespace App\Services;

use App\Models\SparePart;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class SparePartService
{
    /**
     * Adjust stock manually or automatically.
     */
    public function adjustStock(SparePart $part, int $quantity, string $type, ?string $referenceType = null, ?int $referenceId = null): InventoryTransaction
    {
        return DB::transaction(function () use ($part, $quantity, $type, $referenceType, $referenceId) {
            $transaction = InventoryTransaction::create([
                'spare_part_id' => $part->id,
                'type' => $type,
                'quantity' => $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            if ($type === 'in') {
                $part->increment('stock', $quantity);
            } else {
                if ($part->stock < $quantity) {
                    throw new \Exception("Insufficient stock for part: {$part->name}");
                }
                $part->decrement('stock', $quantity);
            }

            return $transaction;
        });
    }
}
