<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetItem;
use App\Models\Category;
use App\Models\Department;
use App\Models\Division;
use App\Models\Location;
use App\Models\UnitOfMeasurement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AssetSummaryTest extends TestCase
{
    use RefreshDatabase;

    private function userWithReportAccess(): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::firstOrCreate(['name' => 'view reports']);

        $user = User::factory()->create();
        $user->givePermissionTo('view reports');

        return $user;
    }

    public function test_summary_page_is_accessible()
    {
        $user = $this->userWithReportAccess();
        $response = $this->actingAs($user)->get(route('reports.summary'));
        $response->assertStatus(200);
    }

    public function test_summary_by_category_returns_correct_data()
    {
        $user = $this->userWithReportAccess();

        // Ensure dependencies exist for factories
        Location::create(['name' => 'IT Room']);
        UnitOfMeasurement::create(['name' => 'Unit', 'symbol' => 'pcs']);

        $category = Category::create(['name' => 'IT Equipment']);
        $asset = Asset::factory()->create(['category_id' => $category->id]);

        // Let's create one specific item to ensure our assertion works precisely.
        AssetItem::create([
            'asset_id' => $asset->id,
            'item_code' => 'TEST-SUM-01',
            'status' => 'Available',
            'purchase_price' => 1000,
            'location_id' => Location::first()->id,
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
        $user = $this->userWithReportAccess();

        // Ensure dependencies exist
        $div = Division::create(['name' => 'HQ']);
        $dept = Department::create(['name' => 'Finance', 'division_id' => $div->id]);
        $assignedUser = User::factory()->create(['department_id' => $dept->id]);

        UnitOfMeasurement::create(['name' => 'Unit', 'symbol' => 'pcs']);
        Location::create(['name' => 'IT Room']);
        $category = Category::create(['name' => 'IT Equipment']);
        $asset = Asset::factory()->create(['category_id' => $category->id]);

        $item = AssetItem::create([
            'asset_id' => $asset->id,
            'item_code' => 'TEST-DEPT-01',
            'status' => 'Deployed',
            'purchase_price' => 2000,
            'location_id' => Location::first()->id,
            'purchase_date' => now()->subYear(),
            'useful_life_months' => 48,
        ]);

        AssetAssignment::create([
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
