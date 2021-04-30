<?php

namespace App\Http\Controllers;

use App\Models\assignment_change;
use App\Models\status_change;
use App\Models\User;
use App\Models\ticket;
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
        return view('userdash')
            ->with('myTickets', ticket::where('user_id', Auth::id())->get())
            ->with('assignMeTicket', 0)
        ;
	}

	public function store(Request $request) {
        switch ($request->activity) {
            case 'assign to me':
                {//Todo: wrap this in a conditional for tickets with new status only.
                    status_change::create([
                        'ticket_id' => $request->assTicketId,
                        'changed_by_tech_id' => Auth::id(),
                        'status_id' => 2,
                    ]);
                }
                assignment_change::create([
                    'ticket_id'=>$request->assTicketId,
                    'changed_by_tech_id'=>Auth::id(),
                    'new_tech_id'=>Auth::id(),
                ]);
            break;
            default:
                //dd($request->request);
            break;
        }


        return view('userdash');
    }
}
