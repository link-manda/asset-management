<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_page_is_accessible()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('reports.summary'));
        $response->assertStatus(200);
    }

    public function test_summary_by_category_returns_correct_data()
    {
        $user = User::factory()->create();
        
        // Ensure dependencies exist for factories
        \App\Models\Location::create(['name' => 'IT Room']);
        \App\Models\UnitOfMeasurement::create(['name' => 'Unit', 'symbol' => 'pcs']);
        
        $category = \App\Models\Category::create(['name' => 'IT Equipment']);
        $asset = \App\Models\Asset::factory()->create(['category_id' => $category->id]);
        
        // Let's create one specific item to ensure our assertion works precisely.
        \App\Models\AssetItem::create([
            'asset_id' => $asset->id,
            'item_code' => 'TEST-SUM-01',
            'status' => 'Available',
            'purchase_price' => 1000,
            'location_id' => \App\Models\Location::first()->id,
            'residual_value' => 0,
            'useful_life_months' => 48,
            'purchase_date' => now()->subYear(),
        ]);

        $response = $this->actingAs($user)->get(route('reports.summary', ['by' => 'category']));
        $response->assertViewHas('data');
        $data = $response->viewData('data');
        
        $itGroup = $data->where('label', 'IT Equipment')->first();
        $this->assertNotNull($itGroup);
        $this->assertGreaterThanOrEqual(1, $itGroup->total_units);
    }

    public function test_summary_by_department_returns_correct_data()
    {
        $user = User::factory()->create();
        
        // Ensure dependencies exist
        $div = \App\Models\Division::create(['name' => 'HQ']);
        $dept = \App\Models\Department::create(['name' => 'Finance', 'division_id' => $div->id]);
        $assignedUser = User::factory()->create(['department_id' => $dept->id]);
        
        \App\Models\UnitOfMeasurement::create(['name' => 'Unit', 'symbol' => 'pcs']);
        \App\Models\Location::create(['name' => 'IT Room']);
        $category = \App\Models\Category::create(['name' => 'IT Equipment']);
        $asset = \App\Models\Asset::factory()->create(['category_id' => $category->id]);
        
        $item = \App\Models\AssetItem::create([
            'asset_id' => $asset->id,
            'item_code' => 'TEST-DEPT-01',
            'status' => 'Deployed',
            'purchase_price' => 2000,
            'location_id' => \App\Models\Location::first()->id,
            'purchase_date' => now()->subYear(),
            'useful_life_months' => 48,
        ]);

        \App\Models\AssetAssignment::create([
            'asset_item_id' => $item->id,
            'assigned_to' => $assignedUser->id,
            'assigned_date' => now(),
            'condition_on_checkout' => 'Good',
        ]);

        $response = $this->actingAs($user)->get(route('reports.summary', ['by' => 'department']));
        $response->assertViewHas('data');
        $data = $response->viewData('data');
        
        $deptGroup = $data->where('label', 'Finance')->first();
        $this->assertNotNull($deptGroup);
    }
}
