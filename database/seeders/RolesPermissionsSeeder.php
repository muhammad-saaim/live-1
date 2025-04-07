<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = [
            'read',
            'write',
            'create',
            'delete',
        ];

        $permissions_by_role = [
            'admin' => [
                'user management',
                'content management',
                'financial management',
                'reporting',
                'api controls',
                'database management',
                'payroll',
                'transaction',
                'group management',
            ],
            'editor' => [
                'content management',
                'financial management',
                'reporting',
            ],
            'developer' => [
                'api controls',
                'database management',
            ],
            'accountant' => [
                'content management',
                'financial management',
                'reporting',
                'payroll',
                'transaction',
            ],
            'support' => [
                'reporting',
            ],
            'user' => [
                'group management',
            ],
        ];

        foreach ($permissions_by_role['admin'] as $permission) {
            foreach ($abilities as $ability) {
                Permission::create(['name' => $ability . ' ' . $permission]);
            }
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];
            foreach ($abilities as $ability) {
                foreach ($permissions as $permission) {
                    $full_permissions_list[] = $ability . ' ' . $permission;
                }
            }
            Role::create(['name' => $role])->syncPermissions($full_permissions_list);
        }

        //User::find(1)->assignRole('admin');
    }
}
