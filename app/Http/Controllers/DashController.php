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
        //$myTickets = Ticket::where('user_id', Auth::id())->get();
        //$myTicketCount = ($myTickets) ? count($myTickets) : 0;
        return view('userdash')
        //    ->with('myTickets', $myTickets)
        //    ->with('myTicketCount', $myTicketCount)
        //    ->with('assignMeTicket', 0)
        ;
	}

	public function store(Request $request) {
        switch ($request->activity) {
            // Admin Activities ------------------------------------------------------------------------------//
            case 'toggle admin':
                if(User::find(Auth::id())->isAdmin) { //verify admin
                    $user = User::find($request->userId);
                    $user->isAdmin = !$user->isAdmin;
                    $user->save();
                }
                return view('userdash')
                    ->with('userQuery',$request->userQuery)
                ;
            break;
            case 'toggle tech':
                if(User::find(Auth::id())->isAdmin) { //verify admin
                    $user = User::find($request->userId);
                    $user->isTechnician = !$user->isTechnician;
                    $user->save();
                }
                return view('userdash')
                    ->with('userQuery',$request->userQuery)
                ;
            break;
            case 'user search':
                $userResults=User::where('isAdmin',false)->where('isTechnician',false)
                    ->where(function($q) use ($request) {
                        $q->where('email','LIKE',"%$request->userQuery%")
                        ->orwhere('name','LIKE',"%$request->userQuery%");
                    })
                    ->get()
                ;
                return view('userdash')
                    ->with('userQuery',$request->userQuery)
                    ->with('userResults',$userResults)
                ;
            case 'delete user':
                if(User::find(Auth::id())->isAdmin) { //verify admin
                    User::destroy($request->userId);
                    return view('userdash')
                        ->with('userQuery',$request->userQuery)
                    ;
                }
            break;

            // Tech Activities -------------------------------------------------------------------------------//
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

            // User Activities -------------------------------------------------------------------------------//
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
