<?php

namespace App\Http\Controllers;

use App\Models\assignment_change;
use App\Models\note;
use App\Models\status_change;
use App\Models\User;
use App\Models\ticket;
use App\Models\category;
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

        $ticket=ticket::where('id',$request['id'])->first();
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

                $ticket=ticket::where('id',$request['ticketId'])->first();
                $ticket->category_id = $request['ticketCategory'];
                $ticket->title= $request['ticketTitle'];
                $ticket->details= $request['ticketDetails'];

                $ticket->save();

                return redirect()->route('userdash');

            break;
            case 'update status':
            break;
            case 'delete note':
                //dd($request);

                $note=note::find($request['noteId']);
                $ticketId=$note->ticket_id;
                $note->delete();

                return redirect()->route('TicketDetail',['id'=>$ticketId])
                    //->with('ticket', ticket::find($request['ticketId']))
                    ;
            break;
            case 'add note':

                $this->validate($request, [
                    'newNote'=>'required',
                ]);

                note::create([
                    'user_id' => Auth::id(),
                    'ticket_id' => $request['ticketId'],
                    'note' => $request['newNote'],
                ]);

                return redirect()->route('TicketDetail',['id'=>$request['ticketId']])
                    //->with('ticket', ticket::find($request['ticketId']))
                    ;
            break;
            default:
                //dd($request->request);
            break;
        }





    }
}
