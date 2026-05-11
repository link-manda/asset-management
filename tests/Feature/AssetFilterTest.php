<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetItem;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('Super Admin');
        $this->actingAs($user);
    }

    public function test_can_filter_assets_by_search_name()
    {
        Asset::factory()->create(['name' => 'MacBook Pro']);
        Asset::factory()->create(['name' => 'Dell XPS']);

        $response = $this->get(route('assets.index', ['search' => 'MacBook']));

        $response->assertStatus(200);
        $response->assertSee('MacBook Pro');
        $response->assertDontSee('Dell XPS');
    }

    public function test_can_filter_assets_by_category()
    {
        $cat1 = Category::factory()->create(['name' => 'Electronics']);
        $cat2 = Category::factory()->create(['name' => 'Furniture']);

        Asset::factory()->create(['name' => 'Laptop', 'category_id' => $cat1->id]);
        Asset::factory()->create(['name' => 'Chair', 'category_id' => $cat2->id]);

        $response = $this->get(route('assets.index', ['category_id' => $cat1->id]));

        $response->assertStatus(200);
        $response->assertSee('Laptop');
        $response->assertDontSee('Chair');
    }

    public function test_can_filter_assets_by_location()
    {
        $loc1 = Location::factory()->create(['name' => 'Office A']);
        $loc2 = Location::factory()->create(['name' => 'Warehouse B']);

        $asset1 = Asset::factory()->create(['name' => 'Projector']);
        AssetItem::factory()->create(['asset_id' => $asset1->id, 'location_id' => $loc1->id]);

        $asset2 = Asset::factory()->create(['name' => 'Printer']);
        AssetItem::factory()->create(['asset_id' => $asset2->id, 'location_id' => $loc2->id]);

        $response = $this->get(route('assets.index', ['location_id' => $loc1->id]));

        $response->assertStatus(200);
        $response->assertSee('Projector');
        $response->assertDontSee('Printer');
    }

    public function test_can_filter_assets_by_status()
    {
        $asset1 = Asset::factory()->create(['name' => 'Monitor 1']);
        AssetItem::factory()->create(['asset_id' => $asset1->id, 'status' => 'Available']);

        $asset2 = Asset::factory()->create(['name' => 'Monitor 2']);
        AssetItem::factory()->create(['asset_id' => $asset2->id, 'status' => 'Deployed']);

        $response = $this->get(route('assets.index', ['status' => 'Available']));

        $response->assertStatus(200);
        $response->assertSee('Monitor 1');
        $response->assertDontSee('Monitor 2');
    }
}
