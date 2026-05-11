<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'other';
        });

        return view('roles.create', compact('groupedPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // Prevent editing Super Admin to avoid lockouts
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with('error', 'Super Admin role cannot be modified.');
        }

        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return count($parts) > 1 ? $parts[1] : 'other';
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Prevent editing Super Admin
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with('error', 'Super Admin role cannot be modified.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deleting core roles
        if (in_array($role->name, ['Super Admin', 'Staff', 'Manager'])) {
            return redirect()->route('roles.index')->with('error', 'Core system roles cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
