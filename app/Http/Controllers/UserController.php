<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Division;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('department.division')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::with('departments')->get();
        return view('users.create', compact('divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,staff',
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department_id' => $request->department_id,
        ]);

        // Map form role to Spatie role names
        $roleMap = [
            'admin' => 'Super Admin',
            'manager' => 'Manager',
            'staff' => 'Staff'
        ];

        if (isset($roleMap[$request->role])) {
            $user->assignRole($roleMap[$request->role]);
        }

        return redirect()->route('users.index')->with('success', 'User successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Allow user to edit their own profile or if they have permission
        if ($user->id !== auth()->id() && !auth()->user()->can('edit users')) {
            abort(403);
        }

        $divisions = Division::with('departments')->get();
        return view('users.edit', compact('user', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Allow user to update their own profile or if they have permission
        if ($user->id !== auth()->id() && !auth()->user()->can('edit users')) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Only validate role and department if the user has 'edit users' permission
        // (Prevents users from changing their own role/dept if they are just editing profile)
        if (auth()->user()->can('edit users')) {
            $rules['role'] = 'required|in:admin,manager,staff';
            $rules['department_id'] = 'required|exists:departments,id';
        }

        $request->validate($rules);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if (auth()->user()->can('edit users') && $request->has('department_id')) {
            $data['department_id'] = $request->department_id;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Only update roles if authorized
        if (auth()->user()->can('edit users') && $request->has('role')) {
            // Map form role to Spatie role names
            $roleMap = [
                'admin' => 'Super Admin',
                'manager' => 'Manager',
                'staff' => 'Staff'
            ];

            if (isset($roleMap[$request->role])) {
                $user->syncRoles($roleMap[$request->role]);
            }
        }

        if ($user->id === auth()->id() && !auth()->user()->can('edit users')) {
            return back()->with('success', 'Your profile has been updated.');
        }

        return redirect()->route('users.index')->with('success', 'User account successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User successfully removed.');
    }
}
