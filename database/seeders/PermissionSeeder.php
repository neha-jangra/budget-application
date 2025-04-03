<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Models\Permission;

use App\Repositories\PermissionRepository;

use App\Repositories\RoleRepository;

class PermissionSeeder extends Seeder
{

    /** @var  PermissionRepository */
    private $permissionRepository;

    /** @var  RoleRepository */
    private $roleRepository;

    public function __construct(PermissionRepository $permissionRepository, RoleRepository $roleRepository)
    {

        $this->permissionRepository  = $permissionRepository;

        $this->roleRepository        = $roleRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'project',

                'display_name' => 'Projects',

                'guard_name' => 'api',
            ],
            [
                'name' => 'donor',

                'display_name' => 'Donors',

                'guard_name' => 'api',
            ],
            [
                'name' => 'line_item',

                'display_name' => 'Line items',

                'guard_name' => 'api',
            ],
            [
                'name' => 'user',

                'display_name' => 'Users',

                'guard_name' => 'api',
            ],
            [
                'name' => 'role_management',

                'display_name' => 'Role Management',

                'guard_name' => 'api',
            ],
            [
                'name' => 'reports',

                'display_name' => 'Reports',

                'guard_name' => 'api',
            ],
            [
                'name' => 'indirect_costs_budget',

                'display_name' => 'Indirect Costs Budget',

                'guard_name' => 'api',
            ],
        ];

        foreach ($permissions as $permissionData) {
            // Use firstOrCreate on the Permission model
            $permission = Permission::firstOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
                ['display_name' => $permissionData['display_name']]
            );

            // Attach permission to a role (assuming you have a $roleId variable defined)
            $role = $this->roleRepository->find(1);

            if ($role && !$role->permissions()->where('permission_id', $permission->id)->exists()) {
                $role->permissions()->attach($permission);
            }
        }
    }
}
