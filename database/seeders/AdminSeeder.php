<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Models\User;

use App\Repositories\UserRepository;

use App\Repositories\RoleRepository;

use App\Repositories\UserprofileRepository;

use App\Repositories\PermissionRepository;


class AdminSeeder extends Seeder
{

    /** @var  UserRepository */
    private $userRepository;

    /** @var  RoleRepository */
    private $roleRepository;

    /** @var  PermissionRepository */
    private $permissionRepository;

    /** @var  UserprofileRepository */
    private $userprofileRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, PermissionRepository $permissionRepository, UserprofileRepository $userprofileRepository)
    {

        $this->userRepository        = $userRepository;

        $this->roleRepository        = $roleRepository;

        $this->permissionRepository  = $permissionRepository;

        $this->userprofileRepository = $userprofileRepository;
    }


    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = $this->userRepository->create([

            'name'    => 'Admin',

            'email'   => 'admin.budgetapp@yopmail.com',

            'password' => '3n(LBuDg3t@Pp@DM!N111',
        ]);

        $this->userprofileRepository->create([

            'first_name'    => 'Admin',

            'user_id'       => $user->id
        ]);

        $role = $this->roleRepository->wherefirst(['id' => 1]);

        if ($role) {
            $user->roles()->attach($role);
        }

        $permission = $this->permissionRepository->wherefirst(['id' => 1]);

        if ($permission) {
            // Assign permissions to roles
            if ($permission && $role && !$role->permissions()->where('name', $permission->name)->exists()) {
                $role->permissions()->attach([$permission->id]);
            }
        }
    }
}
