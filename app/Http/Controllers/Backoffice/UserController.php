<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $query = User::with('roles', 'company');

        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('company_id', auth()->user()->company_id);
        }

        $users = $query->latest()->paginate(20);

        return view('backoffice.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        abort_unless(auth()->user()->hasRole('superadmin'), 403);

        $roles = Role::pluck('name', 'name');
        $companies = Company::pluck('name', 'id');

        return view('backoffice.users.create', compact('roles', 'companies'));
    }

    /**
     * Store a newly created user.
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        // Non-superadmins cannot choose company, default to their own
        if (!auth()->user()->hasRole('superadmin')) {
            $data['company_id'] = auth()->user()->company_id;
        }

        $user = User::create($data);

        // Assign role
        if (auth()->user()->hasRole('superadmin')) {
            // Superadmin can assign any role from form
            if (!empty($data['role']) && Role::where('name', $data['role'])->exists()) {
                $user->assignRole($data['role']);
            }
        } else {
            // Non-superadmins can only create 'client' users
            $user->assignRole('client');
        }

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorizeAccess($user);

        $roles = Role::pluck('name', 'name');
        $companies = auth()->user()->hasRole('superadmin')
            ? Company::pluck('name', 'id')
            : [];

        return view('backoffice.users.edit', compact('user', 'roles', 'companies'));
    }

    /**
     * Update the specified user.
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorizeAccess($user);

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Only superadmin can change company
        if (!auth()->user()->hasRole('superadmin')) {
            unset($data['company_id']);
        }

        $user->update($data);

        // Update roles
        if (auth()->user()->hasRole('superadmin') && !empty($data['role']) && Role::where('name', $data['role'])->exists()) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $this->authorizeAccess($user);

        $user->delete();

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'User deleted successfully.');
    }

    /**
     * Restrict access to users from other companies (non-superadmins).
     */
    protected function authorizeAccess(User $user)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return;
        }

        abort_if($user->company_id !== auth()->user()->company_id, 403, 'Unauthorized access.');
    }
}
