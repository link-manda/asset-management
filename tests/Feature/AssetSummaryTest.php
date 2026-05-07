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
}
