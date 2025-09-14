<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Créer les 3 rôles s'ils n'existent pas
        $roles = ['superadmin', 'admin', 'client'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // ✅ Créer l'utilisateur Superadmin si non existant
        $user = User::firstOrCreate(
            ['email' => 'admin@phoenixcoded.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'is_active' => true,
            ]
        );

        // ✅ Assigner le rôle "superadmin" à cet utilisateur
        $user->assignRole('superadmin');
    }
}
