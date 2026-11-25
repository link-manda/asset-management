# Asset List Filtering Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement advanced filtering (search, category, location, status) on the Master Asset List page.

**Architecture:** Use Laravel Query Scopes (`scopeFilter`) in the `Asset` model to encapsulate filtering logic. The controller will delegate filtering to this scope, and the view will provide a multi-input filter bar.

**Tech Stack:** Laravel (PHP), Tailwind CSS, Lucide Icons.

---

### Task 1: Create Feature Test for Filtering

**Files:**
- Create: `tests/Feature/AssetFilterTest.php`

- [ ] **Step 1: Write initial tests for filtering**

```php
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
        $user = User::factory()->create();
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
```

- [ ] **Step 2: Run tests to verify they fail**

Run: `php artisan test tests/Feature/AssetFilterTest.php`
Expected: FAIL (filtering logic not yet implemented in controller/model)

- [ ] **Step 3: Commit**

```bash
git add tests/Feature/AssetFilterTest.php
git commit -m "test: add feature tests for asset filtering"
```

---

### Task 2: Implement `scopeFilter` in `Asset` Model

**Files:**
- Modify: `app/Models/Asset.php`

- [ ] **Step 1: Add `scopeFilter` method**

```php
    /**
     * Scope a query to filter assets based on various criteria.
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('asset_code', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['category_id'] ?? null, function ($query, $categoryId) {
            $query->where('category_id', $categoryId);
        });

        $query->when($filters['location_id'] ?? null, function ($query, $locationId) {
            $query->whereHas('items', function ($q) use ($locationId) {
                $q->where('location_id', $locationId);
            });
        });

        $query->when($filters['status'] ?? null, function ($query, $status) {
            $query->whereHas('items', function ($q) use ($status) {
                $q->where('status', $status);
            });
        });

        return $query;
    }
```

- [ ] **Step 2: Commit**

```bash
git add app/Models/Asset.php
git commit -m "feat: add scopeFilter to Asset model"
```

---

### Task 3: Update `AssetController@index`

**Files:**
- Modify: `app/Http/Controllers/AssetController.php`

- [ ] **Step 1: Update `index` method to apply filters and fetch master data**

```php
    /**
     * index: Display asset list (Master) with pagination.
     */
    public function index(Request $request)
    {
        $assets = Asset::with(['category', 'uom', 'items'])
            ->filter($request->all())
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $statuses = ['Available', 'Deployed', 'Maintenance', 'Broken', 'Disposed'];

        return view('assets.index', compact('assets', 'categories', 'locations', 'statuses'));
    }
```

- [ ] **Step 2: Run tests to verify they pass**

Run: `php artisan test tests/Feature/AssetFilterTest.php`
Expected: PASS

- [ ] **Step 3: Commit**

```bash
git add app/Http/Controllers/AssetController.php
git commit -m "feat: update AssetController to handle filtering"
```

---

### Task 4: Update View `assets/index.blade.php`

**Files:**
- Modify: `resources/views/assets/index.blade.php`

- [ ] **Step 1: Replace simple search with full filter form**

```html
            <div class="card-header border-b border-default-200">
                <form action="{{ route('assets.index') }}" method="GET" class="flex flex-wrap gap-3 items-center w-full">
                    <div class="relative flex-1 min-w-[200px]">
                        <input name="search" value="{{ request('search') }}" class="ps-11 form-input form-input-sm w-full" placeholder="Search name or asset code..." type="text" />
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                            <i class="size-3.5 flex items-center text-default-500" data-lucide="search"></i>
                        </div>
                    </div>
                    
                    <select name="category_id" class="form-select form-select-sm min-w-[150px]">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="location_id" class="form-select form-select-sm min-w-[150px]">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" class="form-select form-select-sm min-w-[130px]">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-sm bg-primary text-white px-4">
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'category_id', 'location_id', 'status']))
                            <a href="{{ route('assets.index') }}" class="btn btn-sm bg-default-150 text-default-700">
                                Reset
                            </a>
                        @endif
                    </div>
                    
                    <div class="ms-auto">
                        @can('create assets')
                        <a href="{{ route('assets.create') }}" class="btn btn-sm bg-primary text-white">
                            <i class="size-4 me-1" data-lucide="plus"></i> Add New Asset
                        </a>
                        @endcan
                    </div>
                </form>
            </div>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/assets/index.blade.php
git commit -m "feat: update assets index view with filter UI"
```

---

### Task 5: Final Verification

- [ ] **Step 1: Run all tests**

Run: `php artisan test`
Expected: All tests pass (no regressions)

- [ ] **Step 2: Final commit**

```bash
git commit --allow-empty -m "chore: complete asset filtering implementation"
```
