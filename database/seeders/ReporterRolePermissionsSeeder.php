<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ReporterRolePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Reporter role if not exists
        $reporterRole = Role::firstOrCreate(['name' => 'Reporter']);

        // Reporter permissions
        $permissions = [
            // Post permissions
            'post.view',
            'post.create',
            'post.update',
            'post.delete',
            
            // Category/Tag view only
            'category.view',
            'tag.view',
            
            // Media permissions
            'media.view',
            'media.create',
            'media.update',
            'media.delete',
            
            // Own profile
            'profile.view',
            'profile.update',
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $reporterRole->givePermissionTo($perm);
        }

        echo "Reporter role and permissions created successfully!\n";
    }
}
