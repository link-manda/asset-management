<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetAssignmentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_displays_assignments()
    {
        $response = $this->actingAs($this->user)->get(route('assignments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('assignments.index');
        $response->assertViewHas('assignments');
    }

    public function test_index_applies_search_filter()
    {
        $response = $this->actingAs($this->user)->get(route('assignments.index', ['search' => 'laptop']));

        $response->assertStatus(200);
        $response->assertViewIs('assignments.index');
        $response->assertViewHas('assignments');
    }
    
    // We mock the PDF and Excel facades to test the routing logic in the controller
    public function test_index_routes_to_pdf_export()
    {
        Pdf::shouldReceive('loadView')
            ->once()
            ->andReturnSelf();
        Pdf::shouldReceive('setPaper')
            ->once()
            ->with('a4', 'landscape')
            ->andReturnSelf();
        Pdf::shouldReceive('download')
            ->once()
            ->andReturn(response('pdf-download', 200));

        $response = $this->actingAs($this->user)->get(route('assignments.index', ['export' => 'pdf']));
        
        $response->assertStatus(200);
    }

    public function test_index_routes_to_excel_export()
    {
        Excel::fake();

        $response = $this->actingAs($this->user)->get(route('assignments.index', ['export' => 'excel']));

        Excel::assertDownloaded('assignment_history_' . now()->format('YmdHis') . '.xlsx');
    }
}
