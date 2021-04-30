<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ticket;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class NewTicketController extends Controller
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
        return view('newTicket')
            //->with('myTickets', ticket::where('user_id', Auth::id())->get())
            //->with('assTickets', ticket::where('user_id', Auth::id())->get())
        ;
	}

	public function store() {

    }
}
