<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\InventoryItem;
use App\Models\ItemCategory;
use App\Models\PurchaseOrder;

class PurchaseOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $supplier;
    protected $warehouse;
    protected $item;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        
        $this->supplier = Supplier::create([
            'name' => 'General Supply Co.',
            'contact_person' => 'John Doe',
        ]);

        $branch = \App\Models\Branch::create([
            'name' => 'Main Headquarter Branch',
            'slug' => 'main-headquarter-branch',
            'address' => 'Test Address',
            'phone' => '1234567890',
            'email' => 'branch@example.com',
            'website' => 'http://example.com',
            'logo' => 'logo.png',
            'favicon' => 'favicon.png',
            'timezone' => 'UTC',
            'currency' => 'USD',
            'language' => 'en',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i:s',
            'date_time_format' => 'Y-m-d H:i:s',
            'is_active' => true
        ]);

        $this->warehouse = Warehouse::create([
            'name' => 'Main District Warehouse',
            'slug' => 'main-district-warehouse',
            'branch_id' => $branch->id,
            'max_room_count' => 10,
            'max_path_count' => 5,
            'diameter' => ['length' => 100, 'width' => 100],
            'door_dimensions' => ['height' => 3, 'width' => 3],
            'is_active' => true,
        ]);

        $category = ItemCategory::create([
            'name' => 'Compressors',
            'slug' => 'compressors',
        ]);

        $this->item = InventoryItem::create([
            'name' => 'Copeland Scroll',
            'category_id' => $category->id,
            'type' => 'part',
            'uom' => 'unit',
            'stock' => 5,
            'cost' => 150.00,
            'min_stock_level' => 2,
            'is_active' => true,
        ]);
    }

    public function test_can_view_create_purchase_order_page()
    {
        $response = $this->actingAs($this->user)->get(route('purchasing.create'));
        $response->assertStatus(200);
        $response->assertSee('Record New Purchase Order');
    }

    public function test_can_store_purchase_order()
    {
        $payload = [
            'supplier_id' => $this->supplier->id,
            'warehouse_id' => $this->warehouse->id,
            'reference_number' => 'PO-TEST-123',
            'transaction_date' => now()->format('Y-m-d'),
            'notes' => 'Urgent compressor order',
            'items' => [
                [
                    'inventory_item_id' => $this->item->id,
                    'quantity' => 10,
                    'unit_cost' => 140.50,
                ]
            ]
        ];

        $response = $this->actingAs($this->user)->post(route('purchasing.store'), $payload);
        
        $response->assertRedirect(route('purchasing.index'));

        $this->assertDatabaseHas('purchase_orders', [
            'reference_number' => 'PO-TEST-123',
            'warehouse_id' => $this->warehouse->id,
            'total_cost' => 1405.00,
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('purchase_order_items', [
            'inventory_item_id' => $this->item->id,
            'quantity' => 10,
            'unit_cost' => 140.50
        ]);
    }

    public function test_approving_purchase_order_creates_inventory_transactions()
    {
        // 1. Setup pending order
        $order = PurchaseOrder::create([
            'supplier_id' => $this->supplier->id,
            'warehouse_id' => $this->warehouse->id,
            'transaction_date' => now()->format('Y-m-d'),
            'status' => 'pending',
            'approval_status' => 'pending',
            'payment_status' => 'unpaid',
            'total_cost' => 200.00,
        ]);

        $order->items()->create([
            'inventory_item_id' => $this->item->id,
            'quantity' => 20,
            'unit_cost' => 10.00,
        ]);

        $initialStock = $this->item->fresh()->stock;

        // 2. Approve
        $response = $this->actingAs($this->user)->post(route('purchasing.approve', $order->id));
        $response->assertRedirect(route('purchasing.show', $order->id));

        // 3. Assert Approval Status
        $this->assertEquals('approved', $order->fresh()->approval_status);

        // 4. Assert Transaction creation
        $this->assertDatabaseHas('inventory_transactions', [
            'inventory_item_id' => $this->item->id,
            'warehouse_id' => $this->warehouse->id,
            'type' => 'in',
            'quantity' => 20,
        ]);

        // 5. Assert global stock update
        $this->assertEquals($initialStock + 20, $this->item->fresh()->stock);
    }
}
