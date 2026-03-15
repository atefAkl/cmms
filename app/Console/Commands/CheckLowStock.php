<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for low stock spare parts and generates purchase orders';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\InventoryService $inventoryService)
    {
        $this->info('Starting inventory stock check...');
        $inventoryService->checkLowStockAndReorder();
        $this->info('Inventory check complete.');
    }
}
