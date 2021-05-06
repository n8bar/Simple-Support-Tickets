<?php

namespace App\Http\Controllers;

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
        //return view('home');
        //return view('userdash');
        //return $this->__invoke();

        dd($request);

        return view('TicketDetail')
            ->with('request', $request)
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

        $r=$request->request;
        dd($r);
        //$request->validate([
        $this->validate($request, [
            'ticketCategory'=>'required',
            'ticketTitle'=>'required',
            'ticketDetails'=>'required',
        ]);

        ticket::create([
            'user_id' => Auth::id(),
            'category_id' => $request['ticketCategory'],
            'title' => $request['ticketTitle'],
            'details' => $request['ticketDetails'],
        ]);

        return redirect()->route('userdash');
    }
}
