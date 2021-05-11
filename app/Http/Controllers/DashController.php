<?php

namespace App\Http\Controllers;

use App\Models\AssignmentChange;
use App\Models\Note;
use App\Models\StatusChange;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DashController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        //return view('home');
        //return view('userdash');
        return $this->__invoke();
    }

	public function __invoke()
	{
        $myTickets = Ticket::where('user_id', Auth::id())->get();
        $myTicketCount = ($myTickets) ? count($myTickets) : 0;
        return view('userdash')
            ->with('myTickets', $myTickets)
            ->with('myTicketCount', $myTicketCount)
            ->with('assignMeTicket', 0)
        ;
	}

	public function store(Request $request) {
        switch ($request->activity) {
            case 'assign to me':
                {//Todo: wrap this in a conditional for tickets with new status only.
                    StatusChange::create([
                        'ticket_id' => $request->assTicketId,
                        'changed_by_tech_id' => Auth::id(),
                        'status_id' => 2,
                    ]);
                }
                AssignmentChange::create([
                    'ticket_id'=>$request->assTicketId,
                    'changed_by_tech_id'=>Auth::id(),
                    'new_tech_id'=>Auth::id(),
                ]);
            break;
            case 'cancel ticket':
                //dd($request->request);
                $ticket = ticket::find($request->canTicketId);
                //dd($ticket->status()->id);
                if($ticket->status()->id==1) {
                    $ticket->delete();
                } else {
                    Note::create([
                        'ticket_id'=>$request->canTicketId,
                        'note'=>'Cancelled by user',
                        'user_id'=> Auth::id() //$ticket->assignedTech()->id
                    ]);
                    StatusChange::create([
                        'ticket_id' => $request->canTicketId,
                        'changed_by_tech_id' => $ticket->assignedTech()->id,
                        'status_id' => 5,
                    ]);

                }
            break;
            default:
                //dd($request->request);
            break;
        }

        return $this->__invoke();
        //return view('userdash');
    }
}
