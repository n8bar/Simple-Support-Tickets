<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            [
                'name' => 'new',
                'waiting_on_user' => null, //null = waiting on system or nobody in particular.
            ],
            [
                'name' => 'We\'re on it!', //assigned
                'waiting_on_user' => false, //false = waiting on tech
            ],
            [
                'name' => 'We need more information.',
                'waiting_on_user' => true,
            ],
            [
                'name' => 'Solution Proposed',
                'waiting_on_user' => true,
            ],
            [
                'name' => 'Solution Implemented',
                'waiting_on_user' => true,
            ],
            [
                'name' => 'Resolved',
                'waiting_on_user' => null,
            ],
        ];
        foreach ($statuses as $status) {
            //status::create(array(
            DB::table('statuses')->insert([
               'name'=>$status['name'],
               'waiting_on_user'=>$status['waiting_on_user'],
            ]);
        }
    }
}
