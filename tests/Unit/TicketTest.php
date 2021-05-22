<?php

//namespace Tests\Unit;

use App\Models\StatusChange;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TicketTest extends TestCase
{
    /////*use RefreshDatabase;*/
    //////^That didn't work like I wanted it to. So I manually entered the test data.
    //////Uncommenting will result in deleting all test data including statuses and categories.

    protected $ticket1;
    protected $ticket2;
    protected $ticket3;

    public $user;

    public function setup(): void {
        parent::setUp();

        $this->ticket1=Ticket::find(7);
        $this->ticket2=Ticket::find(8);

        $this->user=User::find(1);
        $this->assertTrue($this->user->isTechnician==1);
    }

    public function test_categories_setup_good() {
        $this->assertEquals('Login Issues',Category::find(1)->categoryName);
        $this->assertEquals('Quick Question',Category::find(2)->categoryName);
        $this->assertEquals('Bug Report',Category::find(3)->categoryName);
        $this->assertEquals('Feature Request',Category::find(4)->categoryName);
        $this->assertEquals('Other',Category::find(5)->categoryName);
    }

    public function testing_user_is_good() {
        $this->assertTrue($this->user->isTechnician==1);
    }

    public function testing_tickets_are_good() {
        $this->assertTrue($this->ticket1->test());
        $this->assertTrue($this->ticket2->test());
    }

    public function test_ticket_assigned_tech_for_unassigned_tickets() {
        $this->assertNull($this->ticket1->assignedTech());
    }

    public function test_ticket_assigned_tech_for_assigned() {
        $this->assertEquals(2, $this->ticket2->assignedTech()->id);
    }

/*
    public function test_ticket_status_new() {
        //This assumes that ticket#1 has had no status changes nor has been set to new (status_id 1)
        $ticket=Ticket::find(1);

        $this->assertEquals(1, $ticket->status()->id);
    }

    public function test_ticket_status_not_new() {
        //This assumes that ticket#14 has had status changes and has not been set to new (status_id 1)
        $ticket=Ticket::find(14);

        $this->assertTrue($ticket->status()->id > 1);
    }


    public function test_ticket_category_other(){
        //This assumes that ticket#1 has category_id 5 in the database.
        $ticket=Ticket::find(1);

        $this->assertEquals(5, $ticket->category()->id);
    }

    public function test_ticket_category_bug_reports(){
        //This assumes that ticket#14 has category_id 3 in the database.
        $ticket=Ticket::find(14);

        $this->assertEquals(3, $ticket->category()->id);
    }


    public function test_ticket_user(){
        //This assumes that ticket#1 is created by user_id 3 in the database.
        $ticket=Ticket::find(1);

        $this->assertEquals(3, $ticket->user()->id);
    }


    public function test_ticket_duration(){
        //This assumes that tecket#6 has been set to resolved (status_id 5)
        $ticket=Ticket::find(6);

        $sc = StatusChange::where('ticket_id', $ticket->id)->
            where('status_id',5)->
            latest()->get()->first();

        $this->assertEquals($ticket->duration(), gmdate($sc->created_at->diffInSeconds($ticket->created_at)) );
    }
/**/
}
