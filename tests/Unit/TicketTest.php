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
    }

    public function test_user_is_Technician() {
        echo "Test User: isTechnician\n";
        $this->assertTrue($this->user->isTechnician==1);
    }

    public function test_categories_setup_good() {
        echo "\nTest Category: Login Issues";
        $this->assertEquals('Login Issues',Category::find(1)->categoryName);
        echo "\nTest Category: Quick Question";
        $this->assertEquals('Quick Question',Category::find(2)->categoryName);
        echo "\nTest Category: Bug Report ";
        $this->assertEquals('Bug Report',Category::find(3)->categoryName);
        echo "\nTest Category: Feature Request ";
        $this->assertEquals('Feature Request',Category::find(4)->categoryName);
        echo "\nTest Category: Other ";
        $this->assertEquals('Other',Category::find(5)->categoryName);
    }

    public function testing_tickets_are_good() {
        echo "\nTest Ticket1: exists and is working ";
        $this->assertTrue($this->ticket1->test());
        echo "\nTest Ticket2: exists and is working ";
        $this->assertTrue($this->ticket2->test());
    }

    public function test_ticket_assigned_tech_for_unassigned_tickets() {
        echo "\nTest Ticket1: has not been assigned ";
        $this->assertNull($this->ticket1->assignedTech());
    }

    public function test_ticket_assigned_tech_for_assigned() {
        echo "\nTest Ticket2: has been assigned to user id 2 ";
        $this->assertEquals(2, $this->ticket2->assignedTech()->id);
    }

    public function test_ticket_status_new() {
        //This assumes that ticket1 should have had no status changes nor has been set to new (status_id 1)
        echo "\nTest Ticket Status: new ";
        $this->assertEquals(1, $this->ticket1->status()->id);
    }

    public function test_ticket_status_not_new() {
        //This assumes that ticket2 should have had status changes and has not been set to new (status_id 1)
        echo "\nTest Ticket Status: not new ";
        $this->assertTrue($this->ticket2->status()->id > 1);
    }

    public function test_ticket_category_other(){
        //This assumes that ticket1 should have category_id 5 in the database.
        echo "\nTest Ticket Category: Other ";
        $this->assertEquals(5, $this->ticket1->category()->id);
    }

    public function test_ticket_category_bug_reports(){
        //This assumes that ticket2 should have category_id 3 in the database.
        echo "\nTest Ticket Category: Bug Reports ";
        $this->assertEquals(3, $this->ticket2->category()->id);
    }

    public function test_ticket_user(){
        //This assumes that ticket1 should have been created by user_id 4.
        echo "\nTest Ticket user ";
        $this->assertEquals(4, $this->ticket1->user()->id);
    }

    public function test_ticket_duration(){
        //This assumes that ticket2 should have been set to resolved (status_id 5)
        $sc = StatusChange::where('ticket_id', $this->ticket2->id)->
            where('status_id',5)->
            latest()->get()->first();
        echo "\nTest Ticket duration calculation ";
        $this->assertEquals($this->ticket2->duration(), gmdate('H:i:s',$sc->created_at->diffInSeconds($this->ticket2->created_at)) );
    }
/**/
}
