<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Exports\AssignmentHistoryExport;
use App\Models\AssetAssignment;
use App\Models\AssetItem;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentHistoryExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed required data for factories manually
        \App\Models\Category::create(['name' => 'Electronics', 'code' => 'ELC', 'depreciation_method' => 'straight_line', 'useful_life_years' => 5, 'residual_value_percentage' => 10]);
        \App\Models\UnitOfMeasurement::create(['name' => 'Piece', 'symbol' => 'PCS']);
        \App\Models\Location::create(['name' => 'Main Office', 'code' => 'MO', 'type' => 'office']);
    }

    public function test_query_returns_all_without_search()
    {
        // Generate some test data
        $user = User::factory()->create();
        $asset = Asset::factory()->create();
        $item = $asset->items()->first();
        
        $assignment = AssetAssignment::create([
            'asset_item_id' => $item->id,
            'assigned_to' => $user->id,
            'assigned_date' => now(),
            'condition_on_checkout' => 'Good',
        ]);

        $request = new Request();
        $export = new AssignmentHistoryExport($request->search);
        
        $results = $export->query()->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals($assignment->id, $results->first()->id);
    }

    public function test_query_filters_by_search()
    {
        // Setup matching record
        $userMatch = User::factory()->create(['name' => 'John Doe']);
        $assetMatch = Asset::factory()->create(['name' => 'MacBook Pro']);
        $itemMatch = $assetMatch->items()->first();
        $itemMatch->update(['item_code' => 'ITM-001']);
        
        $assignmentMatch = AssetAssignment::create([
            'asset_item_id' => $itemMatch->id,
            'assigned_to' => $userMatch->id,
            'assigned_date' => now(),
            'condition_on_checkout' => 'Good',
        ]);

        // Setup non-matching record
        $userNoMatch = User::factory()->create(['name' => 'Jane Smith']);
        $assetNoMatch = Asset::factory()->create(['name' => 'ThinkPad']);
        $itemNoMatch = $assetNoMatch->items()->first();
        $itemNoMatch->update(['item_code' => 'ITM-002']);
        
        $assignmentNoMatch = AssetAssignment::create([
            'asset_item_id' => $itemNoMatch->id,
            'assigned_to' => $userNoMatch->id,
            'assigned_date' => now(),
            'condition_on_checkout' => 'Good',
        ]);

        // Search for 'MacBook'
        $request = new Request(['search' => 'MacBook']);
        $export = new AssignmentHistoryExport($request->search);
        
        $results = $export->query()->get();
        
        $this->assertCount(1, $results);
        $this->assertEquals($assignmentMatch->id, $results->first()->id);

        // Search for 'John'
        $request2 = new Request(['search' => 'John']);
        $export2 = new AssignmentHistoryExport($request2->search);
        
        $results2 = $export2->query()->get();
        
        $this->assertCount(1, $results2);
        $this->assertEquals($assignmentMatch->id, $results2->first()->id);
        
        // Search for 'ITM-002'
        $request3 = new Request(['search' => 'ITM-002']);
        $export3 = new AssignmentHistoryExport($request3->search);
        
        $results3 = $export3->query()->get();
        
        $this->assertCount(1, $results3);
        $this->assertEquals($assignmentNoMatch->id, $results3->first()->id);
    }
}
