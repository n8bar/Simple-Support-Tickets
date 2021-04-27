<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
			[//1
				'name'=>'Quick Question',
				'description'=>'I just need a quick answer that\'s not in the FAQ\'s.'
			],
			[//2
				'name'=>'Login Issues',
				'description'=>'I need help with logging in or to request a password reset.'
			],
			[//3
				'name'=>'Bug Report',
				'description'=>'There\'s something awfully screwy going on around here.'
			],
            [//4
                'name'=>'Technical Issues',
                'description'=>'I need support with something.'
            ],
            [//5
                'name'=>'Other',
                'description'=>'Something else'
            ]
		];

		foreach ($categories as $category) {
			//Category::create(array(
            DB::table('categories')->insert(array(
				'name' => $category['name'],
				'description' => $category['description']
            ));
		}
    }
}
