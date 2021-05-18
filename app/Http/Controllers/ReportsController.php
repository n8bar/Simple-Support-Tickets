<?php

namespace App\Http\Controllers;
use App\Models\StatusChange;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

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

                $rows=[];
                $columns=[];
                switch ($request['reportId']) {
                    case 'DTR':
                        $title="Daily Ticket Resolution";

                        $rows=DB::table('status_changes')
                            ->select(
                                DB::raw('count(*) as count'),
                                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
                                DB::raw("DATE_FORMAT(MAX(created_at), '%Y-%m-%d %H:%i:%s') as dtime")
                            ) //, 'status_id')
                            ->where('status_id',5)
                            //->orwhere('status_id',4)
                            //->groupBy('status_id','date')
                            ->groupBy('date')
                            ->orderBy('dtime','DESC')
                            //->havingRaw('count > 1 ')
                            ->get();
                        $columns=[
                            'titles'=>['🗓','Resolved Tickets'],
                            'names'=>['dtime','count']
                        ];
                        //dd($columns);
                    break;
                    case 'TRS':
                        $title="Ticket Resolution Speed";

                        foreach (Ticket::all() as $t ) {
                            if ($t->status()->id==5) {
                                $u=$t->user();
                                $sc = StatusChange::where('ticket_id', $t->id)->
                                    where('status_id',5)->
                                    latest()->get()->first();
                                $r=[
                                    'id'=>$t->id,
                                    'duration'=>$t->duration(),
                                    'resolved'=>$sc->created_at,
                                    'user'=>$u->name.', '.$u->email
                                ];
                                $row = new stdClass();
                                foreach ($r as $key => $value){
                                    $row->$key = $value;
                                }
                                array_push($rows,$row);
                            }
                        }

                        //dd($rows);

                        $columns=[
                            'titles'=>['Ticket#', 'Duration', '🗓 Resolved', 'User'],
                            'names'=>['id','duration', 'resolved', 'user']
                        ];
                    break;
                    default:
                        $title="";
                    break;
                }
                return view('Reports')
                    ->with('title', $title)
                    ->with('columns', $columns)
                    ->with('rows', $rows)
                ;
            break;
        }
    }
}
