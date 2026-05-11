# RBAC Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a full RBAC system using Spatie Permission with a configuration UI.

**Architecture:** Install Spatie Permission, migrate existing roles, implement a Role Detail View for management, and gate the UI/routes.

**Tech Stack:** Laravel 11, Spatie Laravel Permission, Tailwind CSS.

---

### Task 1: Install and Configure Spatie Permission

**Files:**
- Modify: `app/Models/User.php`

- [ ] **Step 1: Install the package**
Run: `composer require spatie/laravel-permission`

- [ ] **Step 2: Publish migrations and config**
Run: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`

- [ ] **Step 3: Run migrations**
Run: `php artisan migrate`

- [ ] **Step 4: Add trait to User model**
Modify: `app/Models/User.php`
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity, HasRoles;
    // ...
}
```

- [ ] **Step 5: Commit**
```bash
git add composer.json composer.lock config/permission.php database/migrations/ app/Models/User.php
git commit -m "chore: install and configure spatie/laravel-permission"
```

---

### Task 2: Data Migration and Seeding

**Files:**
- Create: `database/seeders/RolesAndPermissionsSeeder.php`
- Create: `database/migrations/[timestamp]_migrate_existing_user_roles.php`

- [ ] **Step 1: Create Seeder**
Run: `php artisan make:seeder RolesAndPermissionsSeeder`

- [ ] **Step 2: Implement Seeder**
Modify: `database/seeders/RolesAndPermissionsSeeder.php`
```php
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = ['assets', 'categories', 'locations', 'users', 'departments', 'divisions'];
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::create(['name' => "$action $module"]);
            }
        }

        $superAdmin = Role::create(['name' => 'Super Admin']);
        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo(['view assets', 'create assets', 'edit assets', 'view users']);

        $staff = Role::create(['name' => 'Staff']);
        $staff->givePermissionTo(['view assets']);
    }
}
```

- [ ] **Step 3: Create Migration to convert existing roles**
Run: `php artisan make:migration migrate_existing_user_roles`

- [ ] **Step 4: Implement Role Migration**
Modify the new migration:
```php
public function up(): void
{
    foreach (\App\Models\User::all() as $user) {
        if ($user->role) {
            $roleName = ucwords($user->role); 
            if (\Spatie\Permission\Models\Role::where('name', $roleName)->exists()) {
                $user->assignRole($roleName);
            }
        }
    }
}
```

- [ ] **Step 5: Run seeder and migration**
Run: `php artisan db:seed --class=RolesAndPermissionsSeeder`
Run: `php artisan migrate`

- [ ] **Step 6: Commit**
```bash
git add database/seeders/RolesAndPermissionsSeeder.php database/migrations/
git commit -m "feat: seed roles/permissions and migrate existing user data"
```

---

### Task 3: Administrative UI (Role Management)

**Files:**
- Modify: `routes/web.php`
- Create: `app/Http/Controllers/RoleController.php`
- Create: `resources/views/roles/index.blade.php`
- Create: `resources/views/roles/edit.blade.php`

- [ ] **Step 1: Define Routes**
Modify: `routes/web.php`
```php
use App\Http\Controllers\RoleController;

Route::group(['middleware' => ['auth', 'role:Super Admin']], function () {
    Route::resource('roles', RoleController::class);
});
```

- [ ] **Step 2: Create Controller**
Run: `php artisan make:controller RoleController`

- [ ] **Step 3: Implement Index and Edit methods**
Modify: `app/Http/Controllers/RoleController.php`
Group permissions by module for the view.

- [ ] **Step 4: Create Views**
Create: `resources/views/roles/index.blade.php` and `resources/views/roles/edit.blade.php`.

- [ ] **Step 5: Implement Update Logic**
Modify: `RoleController@update` to sync permissions using `$role->syncPermissions($request->permissions)`.

- [ ] **Step 6: Commit**
```bash
git add app/Http/Controllers/RoleController.php routes/web.php resources/views/roles/
git commit -m "feat: add role management UI"
```

---

### Task 4: UI Gating and Protection

**Files:**
- Modify: `app/Providers/AppServiceProvider.php`
- Modify: `resources/views/layouts/partials/sidenav.blade.php`
- Modify: `resources/views/assets/index.blade.php`

- [ ] **Step 1: Super Admin Gate**
Modify: `app/Providers/AppServiceProvider.php`
```php
public function boot(): void
{
    \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
    });
}
```

- [ ] **Step 2: Update Sidebar**
Modify: `resources/views/layouts/partials/sidenav.blade.php`

- [ ] **Step 3: Protect Action Buttons**
Modify views to use `@can` directives.

- [ ] **Step 4: Commit**
```bash
git add app/Providers/AppServiceProvider.php resources/views/
git commit -m "feat: implement UI gating with blade directives"
```

---

### Task 5: Final Testing and Cleanup

**Files:**
- Create: `database/migrations/[timestamp]_drop_role_column_from_users_table.php`

- [ ] **Step 1: Verification**
Manual check of different user roles.

- [ ] **Step 2: Drop old role column**
Run: `php artisan make:migration drop_role_column_from_users_table`
Implement `Schema::table('users', fn($table) => $table->dropColumn('role'))`.
Run: `php artisan migrate`

- [ ] **Step 3: Commit**
```bash
git add database/migrations/
git commit -m "chore: cleanup users table by dropping old role column"
```
