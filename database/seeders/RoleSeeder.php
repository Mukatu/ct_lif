<script>
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Création des rôles
        $internalComm = Role::create(['name' => 'internal_comm']);
        $hr = Role::create(['name' => 'hr']);
        $employee = Role::create(['name' => 'employee']);

        // Création des permissions
        $permissions = [
            'publish_posts' => Permission::create(['name' => 'publish_posts']),
            'manage_posts' => Permission::create(['name' => 'manage_posts']),
            'comment_posts' => Permission::create(['name' => 'comment_posts']),
            'manage_requests' => Permission::create(['name' => 'manage_requests']),
            'create_requests' => Permission::create(['name' => 'create_requests']),
            'manage_documents' => Permission::create(['name' => 'manage_documents']),
            'view_documents' => Permission::create(['name' => 'view_documents']),
            'manage_trainings' => Permission::create(['name' => 'manage_trainings']),
            'view_trainings' => Permission::create(['name' => 'view_trainings']),
        ];

        // Attribution des permissions aux rôles
        $internalComm->givePermissionTo([
            'publish_posts',
            'manage_posts',
            'comment_posts',
            'view_documents',
            'view_trainings'
        ]);

        $hr->givePermissionTo([
            'comment_posts',
            'manage_requests',
            'manage_documents',
            'manage_trainings',
            'view_documents',
            'view_trainings'
        ]);

        $employee->givePermissionTo([
            'comment_posts',
            'create_requests',
            'view_documents',
            'view_trainings'
        ]);
    }
}
</script>