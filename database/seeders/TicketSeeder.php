<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tickets = [
			[
                'user_id' => 3,
                'category_id' => 1,
                'title' => 'Can I purchase more monopoly money?',
                'details' => 'I\'d like to start out with a higher virtual balance and I\'m willing to pay.'
			],
			[
                'user_id' => 3,
                'category_id' => 2,
                'title' => 'I forgot my Password',
                'details' => ''
			],
			[
                'user_id' => 4,
                'category_id' => 2,
                'title' => 'I forgot my Password',
                'details' => ''
			],
			[
                'user_id' => 3,
                'category_id' => 2,
                'title' => 'Help! I\'m locked out!',
                'details' => 'I am unable to log in after about 20 tries. Am I locked out?'
			],
			[
                'user_id' => 3,
                'category_id' => 4,
                'title' => 'Issue buying vXRP',
                'details' => 'When I purchase vXRP using leverage my vUSD and portfolio disappears. I am unable to speculate on this virtual asset.'
			],
			[
                'user_id' => 3,
                'category_id' => 5,
                'title' => 'vLTC is listed after vXRP.',
                'details' => 'I dislike having to scroll past the hideous vXRP to get to my beautiful vLTC.'
			],
			[
                'user_id' => 4,
                'category_id' => 3,
                'title' => 'Report Columns misalignment',
                'details' => 'Whenever data cannot fit into a cell it\'s whole column and subsequent columns become misaligned with the heading'
			],
			[
                'user_id' => 3,
                'category_id' => 4,
                'title' => 'vXRP',
                'details' => 'vXRP exists and I cannot find the delete button.'
			],
		];

		foreach ($tickets as $ticket) {
			//Ticket::create(array(
            DB::table('tickets')->insert(array(
				'user_id'=>$ticket['user_id'],
				'category_id'=>$ticket['category_id'],
				'title'=>$ticket['title'],
				'details'=>$ticket['details'],
			));
		}
    }
}
