@extends('home')

@section('dashboard')
    @auth
        <h5>Welcome, {{ Auth::user()->name }}!</h5>
        @if( !Auth::user()->isTechnician && !Auth::user()->isAdmin)
            <button onclick="window.location='{{route('newTicket')}}'" >Open a new ticket</button>
        @endif

        <hr />

        @if( Auth::user()->isAdmin )  {{-- Admin Dashboard --}}

            <h3>Admin & Technician List:</h3>
            <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                <thead class="thead-light">
                    <th>Username</th>
                    <th>Admin</th>
                    <th>Tech</th>
                    <th>Email</th>

                </thead>
            @foreach(App\Models\User::where('isAdmin', 1)->orwhere('isTechnician', 1)->get() as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->isAdmin ? 'Yes' : 'No'  }}</td>
                        <td>{{ $u->isTechnician ? 'Yes' : 'No'  }}</td>
                        <td>{{ $u->email }}</td>
                    </tr>
            @endforeach
            </table>

        @elseif( Auth::user()->isTechnician)  {{-- Technician Dashboard --}}

            <h3>Assigned To Me:</h3>
            <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                <thead class="thead-light">
                    <th>Title</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                @foreach(App\Models\ticket::all() as $t)
                    @if( $t->assignedTech() == Auth::user() ) {{-- $t->assignedTech()->id == Auth::user()->id ) --}}
                        <tr class="ticket-row" onclick="ticket{{$t->id}}.submit()" >
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->details  }}</td>
                            <td>{{ $t->status()->statusName }}</td>
                            <td>
                                <form name="ticket{{$t->id}}" action="{{route('TicketDetail')}}" method="post" >
                                    @csrf
                                    <input type="hidden" name="id" value="{{$t->id}}" />
                                    <input type="submit" value="Open" />
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <hr style="border-width:10px; border-radius: 5px;" />
            <h3>Unnassigned</h3>
            <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                <thead class="thead-light">
                    <th>Title</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                @foreach(App\Models\ticket::all() as $t)
                    @if($t->assignedTech()==null)
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->details  }}</td>
                            <td>{{ $t->status()->statusName }}</td>
                            <td>
                                <form action="{{route('userdash')}}" method="post">
                                    @csrf
                                    <input name="activity" type="hidden" value="assign to me" />
                                    <input name="assTicketId" type="hidden" value="{{$t->id}}" />
                                    <input name="assBtn" type="submit" value="Assign to me" />
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>

        @elseif( !Auth::user()->isTechnician)  {{-- User Dashboard --}}

            @if($myTicketCount>0)
                <h3>My tickets</h3>
                <table class="table" style="border-radius: 20px; overflow: hidden">
                    <thead class="thead-light">
                        <th>Title</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    @foreach( $myTickets as $t )
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->details  }}</td>
                            <td>{{ $t->status()->statusName }}</td>
                            <td>
                                @if($t->status()->id!=5)
                                    <form action="{{route('userdash')}}" method="post">
                                        @csrf
                                        <input name="activity" type="hidden" value="cancel ticket" />
                                        <input name="canTicketId" type="hidden" value="{{$t->id}}" />
                                        @if($t->status()->id==1)
                                            @php ($btnText="Delete")
                                        @else
                                            @php ($btnText="Close")
                                        @endif
                                        <input type="submit" onclick="return confirm('Really {{strtolower($btnText)}} \'{{$t->title}}\' ?')" value="{{$btnText}}" />
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

        @endif

    @endauth
@endsection
