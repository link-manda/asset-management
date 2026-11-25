# RBAC Feature Design Specification

## Overview
Implement a Role-Based Access Control (RBAC) system for the Asset Management application to securely manage user permissions and access levels. The system will replace the current static string-based role column with a robust, relational model.

## 1. Architecture & Data Model
*   **Package:** `spatie/laravel-permission`
*   **Granularity:** Module-based CRUD (e.g., `view assets`, `create assets`, `edit users`, `delete locations`).
*   **Migration Strategy:**
    1.  Publish and run Spatie's migrations.
    2.  Create a custom migration to map existing values in `users.role` to Spatie's `model_has_roles`.
    3.  Drop the `role` column from the `users` table to maintain a single source of truth.
*   **Seeding:** A dedicated `RolesAndPermissionsSeeder` will initialize default roles (e.g., Super Admin, Manager, Staff) and define the core matrix of module permissions.

## 2. User Interface (Configuration Page)
*   **Layout:** Role Detail View.
*   **Index View:** A list/table of all available roles with options to Create, Edit, or Delete roles.
*   **Detail/Edit View:** 
    *   When selecting a specific role, the UI displays a categorized list of permissions.
    *   Permissions are grouped visually by module (e.g., a "Locations" card with checkboxes for view, create, edit, delete).
    *   Form submission will sync the selected permissions to the role via standard Laravel controllers.
*   **Styling:** Will match the existing application layout (Tailwind CSS/Bootstrap depending on current setup), utilizing the existing `app.blade.php` layout.

## 3. Integration & Security
*   **Route Protection:** Apply Spatie's `role` or `permission` middleware to relevant route groups in `web.php`.
*   **UI Protection:** Use Blade directives (`@can`, `@hasrole`) to conditionally render navigation items, action buttons (edit/delete), and sensitive data based on the authenticated user's permissions.
*   **Exception Handling:** Ensure 403 Unauthorized responses are handled gracefully, potentially redirecting to a friendly error view or dashboard with an alert.
