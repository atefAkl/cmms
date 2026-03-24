<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\RefrigerationSystem;
use App\Models\SystemDevice;
use Laravel\Sanctum\Sanctum;

class SystemComponentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $system;
    protected $dummyDevice;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $room = \App\Models\Room::create([
            'name' => 'Test Room',
            'slug' => 'test-room',
            'room_number' => '101',
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id,
        ]);
        $this->system = RefrigerationSystem::create([
            'name' => 'Test Fridge System',
            'status' => 'active',
            'room_id' => $room->id,
        ]);
        $this->dummyDevice = \App\Models\Device::create([
            'name' => 'Dummy Test Device',
            'created_by' => $this->user->id,
            'updated_by' => $this->user->id,
            'status' => 'active'
        ]);
    }

    public function test_can_create_root_component_and_retrieve_tree()
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->user);

        // 1. Create Root Feature
        $response = $this->postJson("/api/systems/{$this->system->id}/components", [
            'device_id' => $this->dummyDevice->id,
            'name' => 'Compressor 1',
            'component_type' => 'compressor',
            'install_type' => 'init',
            'status' => 'working'
        ]);

        $response->assertStatus(201);
        $rootId = $response->json('component.id');
        $this->assertDatabaseHas('system_devices', [
            'id' => $rootId,
            'name' => 'Compressor 1',
            'level' => 0
        ]);

        // 2. Create Child Feature
        $childResponse = $this->postJson("/api/systems/{$this->system->id}/components", [
            'device_id' => $this->dummyDevice->id,
            'parent_id' => $rootId,
            'name' => 'Sub Compressor Part',
            'component_type' => 'part',
            'install_type' => 'init'
        ]);
        $childResponse->assertStatus(201);
        $this->assertDatabaseHas('system_devices', [
            'id' => $childResponse->json('component.id'),
            'level' => 1
        ]);

        // 3. Retrieve Tree
        $treeResponse = $this->getJson("/api/systems/{$this->system->id}/components");
        $treeResponse->assertStatus(200);
        $components = $treeResponse->json('components');
        
        $this->assertCount(1, $components);
        $this->assertEquals('Compressor 1', $components[0]['name']);
        $this->assertCount(1, $components[0]['children']);
        $this->assertEquals('Sub Compressor Part', $components[0]['children'][0]['name']);
    }

    public function test_cannot_exceed_max_depth_level_3()
    {
        $this->actingAs($this->user);

        // Level 0
        $l0 = SystemDevice::create([
            'refrigeration_system_id' => $this->system->id,
            'device_id' => $this->dummyDevice->id,
            'name' => 'L0',
            'level' => 0
        ]);
        // Level 1
        $l1 = SystemDevice::create([
            'refrigeration_system_id' => $this->system->id,
            'device_id' => $this->dummyDevice->id,
            'parent_id' => $l0->id,
            'name' => 'L1',
            'level' => 1
        ]);
        // Level 2
        $l2 = SystemDevice::create([
            'refrigeration_system_id' => $this->system->id,
            'device_id' => $this->dummyDevice->id,
            'parent_id' => $l1->id,
            'name' => 'L2',
            'level' => 2
        ]);
        // Level 3
        $l3 = SystemDevice::create([
            'refrigeration_system_id' => $this->system->id,
            'device_id' => $this->dummyDevice->id,
            'parent_id' => $l2->id,
            'name' => 'L3',
            'level' => 3
        ]);

        // Try creating Level 4
        $response = $this->postJson("/api/systems/{$this->system->id}/components", [
            'device_id' => $this->dummyDevice->id,
            'parent_id' => $l3->id,
            'name' => 'L4 (Should Fail)',
            'install_type' => 'init'
        ]);

        $response->assertStatus(422);
        $this->assertEquals('Maximum component depth (level 3) exceeded.', $response->json('error'));
    }

    public function test_backward_compatibility_system_devices_model()
    {
        // Old code used to inject `refrigeration_system_id` and `installed` directly
        $device = new SystemDevice();
        $device->name = 'Legacy Thermostat';
        $device->device_id = $this->dummyDevice->id;
        $device->refrigeration_system_id = $this->system->id;
        $device->installed = now();
        $device->save();

        $this->assertDatabaseHas('system_devices', [
            'name' => 'Legacy Thermostat',
            'device_id' => $this->dummyDevice->id,
            'refrigeration_system_id' => $this->system->id
        ]);

        // Ensure aliases work correctly
        $this->assertEquals($this->system->id, $device->system_id);
        $this->assertNotNull($device->installed_at);
        
        // Re-saving with aliases
        $device->system_id = $this->system->id;
        $device->save();
        $this->assertEquals($this->system->id, $device->refrigeration_system_id);
    }
}
