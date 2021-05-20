<?php

namespace Tests\Unit;

use App\Models\StatusChange;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;
    protected $Ticket;
    protected $ticket1;
    protected $ticket6;
    protected $ticket14;

    public function setup(): void {
        parent::setUp();

        $this->Ticket = app()->make('App\Models\Ticket');

        $ticket1=$this->Ticket::find(1);
        $ticket6=$this->Ticket::find(6);
        $ticket14=$this->Ticket::find(14);
    }

    public function test_ticket_assigned_tech_for_unassigned_tickets() {
        //This assumes that ticket#1 in the database is unassigned.

        $this->assertNull($this->ticket1->assignedTech());
    }
/*
    public function test_ticket_assigned_tech_for_assigned() {
        //This assumes that ticket#14 latest new_tech_id is 2 in the assignment_changes table.
        $ticket=Ticket::find(14);

        $this->assertEquals(2, $ticket->assignedTech()->id);
    }


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
