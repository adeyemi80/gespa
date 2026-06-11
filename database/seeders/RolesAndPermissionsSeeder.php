<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Nettoyage du cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 🔐 Permissions globales
        $permissions = [
            // Élèves
            'eleves.view',
            'eleves.create',
            'eleves.edit',
            'eleves.delete',

            // Notes
            'notes.import',
            'notes.edit',
            'notes.view',

            // Parents
            'parents.import',
            'parents.view',

            // Finances
            'paiements.view',
            'paiements.create',

            // Bulletins
            'bulletins.view',
            'bulletins.generate',

            // Administration
            'users.manage',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 🎓 Rôles + permissions
        $roles = [
            'admin' => Permission::all(),

            'directeur' => [
                'eleves.view',
                'notes.view',
                'bulletins.view',
                'bulletins.generate',
                'paiements.view',
            ],

            'censeur' => [
                'eleves.view',
                'notes.view',
                'notes.edit',
            ],

            'enseignant' => [
                'notes.view',
                'notes.edit',
            ],

            'surveillant' => [
                'eleves.view',
            ],

            'comptable' => [
                'paiements.view',
                'paiements.create',
            ],

            'secretaire' => [
                'eleves.view',
                'eleves.create',
                'parents.import',
            ],

            'parent' => [
                'bulletins.view',
                'notes.view',
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($perms);
        }
    }
}
