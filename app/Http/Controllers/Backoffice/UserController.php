<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
        $query = User::query()->with('roles');

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
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        if (!auth()->user()->hasRole('superadmin')) {
            $data['company_id'] = auth()->user()->company_id;
        }

        $user = User::create($data);
        $user->assignRole($data['role']);

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorizeAccess($user);

        $roles = Role::pluck('name', 'name');
        $companies = [];

        if (auth()->user()->hasRole('superadmin')) {
            $companies = Company::pluck('name', 'id');
        }

        return view('backoffice.users.edit', compact('user', 'roles', 'companies'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorizeAccess($user);

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (!auth()->user()->hasRole('superadmin')) {
            unset($data['company_id']);
        }

        $user->update($data);
        $user->syncRoles([$data['role']]);

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorizeAccess($user);

        $user->delete();

        return redirect()->route('backoffice.users.index')
                         ->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Prevent access to users from other companies (for non-superadmins).
     */
    protected function authorizeAccess(User $user)
    {
        if (auth()->user()->hasRole('superadmin')) {
            return;
        }

        abort_if(
            $user->company_id !== auth()->user()->company_id,
            403,
            'Accès non autorisé.'
        );
    }
}
