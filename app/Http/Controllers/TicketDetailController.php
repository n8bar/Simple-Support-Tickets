<?php

namespace App\Http\Controllers;

use App\Models\AssignmentChange;
use App\Models\Note;
use App\Models\StatusChange;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class TicketDetailController extends Controller
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
    public function index(Request $request)
    {
        //dd($request);

        $ticket=Ticket::where('id',$request['id'])->first();
        //dd($ticket->category_id);
        return view('TicketDetail')
            ->with('ticket', $ticket )
        ;
    }

	public function __invoke()
	{
        return view('TicketDetail')
            //->with('assTickets', ticket::where('user_id', Auth::id())->get())
        ;
	}

	/*
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
/**/
    public function getTechnicians($s='') {
        $s=str_replace('@','\\@',$s);
        $Technicians = User::where('isTechnician',true)
            ->where(function($q) use ($s) {
                $q->where('email', 'LIKE', "%$s%")
                    ->orwhere('name', 'LIKE', "%$s%");
            })
            ->limit(10)
            ->get();
        //dd(response()->json($Technicians));
        return response()->json($Technicians);
    }

	public function store(Request $request) {


        switch ($request->activity) {
            case 'modify ticket':
                //$request->validate([
                $this->validate($request, [
                    'ticketId'=>'required',
                    'ticketCategory'=>'required',
                    'ticketTitle'=>'required',
                    'ticketDetails'=>'required',
                ]);

                $ticket=Ticket::find($request['ticketId']);
                $ticket->category_id = $request['ticketCategory'];
                $ticket->title= $request['ticketTitle'];
                $ticket->details= $request['ticketDetails'];

                $ticket->save();

                return redirect()->route('TicketDetail',['id'=>$request['ticketId']]);
            break;
            case 'update status':
                //dd($request);

                StatusChange::create([
                    'ticket_id'=>$request['ticketId'],
                    'changed_by_tech_id'=>Auth::id(),
                    'status_id'=>$request['ticketStatus'],
                ]);

                return redirect()->route('TicketDetail',['id'=>$request['ticketId']]);
            break;
            case 'delete note':
                $note=Note::find($request['noteId']);
                $ticketId=$note->ticket_id;
                $note->delete();

                return redirect()->route('TicketDetail',['id'=>$ticketId]);
            break;
            case 'modify note':
                $this->validate($request, [
                    'noteNote'=>'required',
                ]);

                //dd($request->request);
                $note=Note::find($request['noteId']);
                $ticketId=$note->ticket_id;

                $note->note=$request['noteNote'];
                $note->save();

                return redirect()->route('TicketDetail',['id'=>$ticketId]);
            break;
            case 'add note':

                $this->validate($request, [
                    'newNote'=>'required',
                    'ticketId'=>'required'
                ]);

                Note::create([
                    'user_id' => Auth::id(),
                    'ticket_id' => $request['ticketId'],
                    'note' => $request['newNote'],
                ]);

                return redirect()->route('TicketDetail',['id'=>$request['ticketId']])
                    //->with('ticket', ticket::find($request['ticketId']))
                    ;
            break;
            case 'update tech':
                $this->validate($request,[
                    'assToTech'=>'required',
                    'ticketId'=>'required'
                ]);

                AssignmentChange::create([
                    'ticket_id' => $request['ticketId'],
                    'changed_by_tech_id' => Auth::id(),
                    'new_tech_id' => $request['assToTech'],
                ]);

                if(Ticket::find($request['ticketId'])->status()->id==1) {
                    StatusChange::create([
                        'ticket_id'=>$request['ticketId'],
                        'changed_by_tech_id'=>Auth::id(),
                        'status_id'=>2,
                    ]);
                }

                return redirect()->route('TicketDetail',['id'=>$request['ticketId']]);
            break;
            default:
                //dd($request->request);
            break;
        }





    }
}
