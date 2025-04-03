<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Repositories\RoleRepository;

class RoleSeeder extends Seeder
{

    /** @var  RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
    
        $this->roleRepository  =  $roleRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->roleRepository->create([

            'title'         => 'Admin',

            'name'          => 'admin',
            
            'guard_name'    => 'web'
        ]);

        $this->roleRepository->create([

            'title'         => 'Donor',

            'name'          => 'donor',
            
            'guard_name'    => 'web'
        ]);


        $this->roleRepository->create([

            'title'         => 'Employee',

            'name'          => 'employee',
            
            'guard_name'    => 'web'
        ]);

        $this->roleRepository->create([

            'title'         => 'Sub-grantee',

            'name'          => 'Sub-grantee',
            
            'guard_name'    => 'web'
        ]);

        $this->roleRepository->create([

            'title'         => 'Consultant',

            'name'          => 'consultant',
            
            'guard_name'    => 'web'
        ]);
    }
}
