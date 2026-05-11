# Asset List Filtering Design Specification

## Overview
Implement advanced filtering capabilities for the Master Asset List (Catalog) to allow users to quickly locate assets based on multiple criteria including category, location, and item status.

## 1. Logic & Architecture
The filtering logic will follow **Approach 2: Dedicated Query Scope** in the `Asset` model. This ensures maintainability and allows the same logic to be reused for reports or API endpoints.

*   **Logic Rule (Partial Match):** An Asset (Master) will be displayed if **at least one** of its physical units (`AssetItem`) matches the selected filter criteria (Location and Status).
*   **Query Scope:** `Asset::scopeFilter($query, array $filters)`
    *   `search`: Case-insensitive search on `assets.name` and `assets.asset_code`.
    *   `category_id`: Direct match on `assets.category_id`.
    *   `location_id`: Filter via `whereHas('items', fn($q) => $q->where('location_id', $val))`.
    *   `status`: Filter via `whereHas('items', fn($q) => $q->where('status', $val))`.

## 2. Implementation Details

### Model (`App\Models\Asset`)
Add `scopeFilter` method to handle the logic described above.

### Controller (`App\Http\Controllers\AssetController`)
Update `index` method:
1.  Retrieve filter inputs from `Request`.
2.  Pass inputs to `Asset::filter($request->only([...]))`.
3.  Eager load `category`, `uom`, and `items` to prevent N+1 issues.
4.  Fetch master lists for Category and Location to populate dropdowns in the view.

### View (`resources/views/assets/index.blade.php`)
*   Replace the current single search input with a comprehensive filter bar.
*   **Filter Bar Components:**
    *   Text input for name/code search.
    *   Select dropdown for Category.
    *   Select dropdown for Location.
    *   Select dropdown for Status (Available, Deployed, Maintenance, Broken, Disposed).
    *   "Filter" button and "Reset" link.
*   **Pagination:** Ensure `links()` includes `appends(request()->query())` to maintain filter state across pages.

## 3. UI/UX Design
*   The filter bar will be placed inside the card header, expanding if necessary or using a responsive grid layout.
*   Filters should be non-destructive; resetting should clear all inputs and return to the default master list.

## 4. Testing Strategy
*   **Model Test:** Verify `scopeFilter` returns correct results for combined criteria.
*   **Feature Test:** Ensure the `index` page correctly applies filters from query parameters and displays the filtered list.
