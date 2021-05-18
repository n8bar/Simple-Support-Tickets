<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return $this->__invoke();
    }

    public function __invoke()
    {
        return view('Reports');
    }


    public function data(Request $request) {
        $this->validate($request,[
           'activity'=>'required'
        ]);

        switch ($request['activity']) {
            case 'choose report':
                $this->validate($request,[
                    'reportId'=>'required'
                ]);

                switch ($request['reportId']) {
                    case 'DTR':
                        $title="Daily Ticket Resolution";
                    break;
                    case 'TART':
                        $title="Ticket Average Resolution Time";
                    break;
                }
                return view('Reports')
                    ->with('title', $title)
                ;
            break;
        }
    }
}
