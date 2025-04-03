<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Repositories\LookupRepository;

class LookupSeeder extends Seeder
{
    /** @var  LookupRepository */
    private $lookupRepository;

    public function __construct(LookupRepository $lookupRepository)
    {
    
        $this->lookupRepository  =  $lookupRepository;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lookups = [

            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'SALARIES AND FRINGE BENEFITS',

                'sort_order'         => '1',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'CONSULTANTS',

                'sort_order'         => '2',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'LOCAL SUB-GRANTS',

                'sort_order'         => '3',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'TRAVEL (DIRECT TRAVEL COSTS)',

                'sort_order'         => '4',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'MEETINGS',

                'sort_order'         => '5',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'TOTAL OTHER DIRECT COSTS',

                'sort_order'         => '6',

                'other'              => '1'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'TOTAL DIRECT COSTS',

                'sort_order'         => '7',

                'other'              => '0'

            ],
            [

                'look_up_type'       => 'project',
                
                'look_up_field'      => 'project_segment',

                'look_up_value'      => 'INDIRECT COSTS',

                'sort_order'         => '8',

                'other'              => '2'

            ],

        ];


        foreach ($lookups as $lookup) 
        {
            $this->lookupRepository->create($lookup);
        }
    }
}
