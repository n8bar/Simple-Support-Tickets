@extends('home')
@inject('Ticket', 'App\Models\Ticket')
@section('dashboard')
    @auth
        <h5>Welcome, {{ Auth::user()->name }}! <button id="btnReports" onclick="window.location='{{url('/Reports')}}'">Reports</button></h5>
        @if( !Auth::user()->isTechnician && !Auth::user()->isAdmin)
            <button onclick="window.location='{{route('newTicket')}}'" >Open a new ticket</button>
        @endif

        <hr />

        {{------------------------------------------------------------------------------------------------------}}
        @if( Auth::user()->isAdmin )  {{-- Admin Dashboard --}}

            <h3>Admin & Technician List:</h3>
            <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                <thead class="thead-light">
                    <th>Username</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th style="width:96pt"></th>
                    <th>Tech</th>
                    <th style="width:96pt"></th>
                </thead>
            @foreach(App\Models\User::where('isAdmin', 1)->orwhere('isTechnician', 1)->get() as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->isAdmin ? 'Yes' : 'No'  }}</td>
                        <td>
                            @if($u->id == Auth::id()/**/)
                                This is me.
                            @else
                                <form action="{{route('userdash')}}" method="post">
                                    @csrf
                                    <input name="activity" type="hidden" value="toggle admin" />
                                    <input name="userId" type="hidden" value="{{$u->id}}" />
                                    <input name="userQuery" type="hidden" value="{{$userQuery ?? ''}}" />
                                    <input type="submit" value="{{$u->isAdmin ? "Demote" : "Promote"}}" />
                                </form>
                            @endif
                        </td>
                        <td>{{ $u->isTechnician ? 'Yes' : 'No'  }}</td>
                        <td>
                            <form action="{{route('userdash')}}" method="post">
                                @csrf
                                <input name="activity" type="hidden" value="toggle tech" />
                                <input name="userId" type="hidden" value="{{$u->id}}" />
                                <input name="userQuery" type="hidden" value="{{$userQuery ?? ''}}" />
                                <input type="submit" value="{{$u->isTechnician ? "Deactivate" : "Activate"}}" />
                            </form>
                        </td>
                    </tr>
            @endforeach
            </table>

            <hr />

            <h3>User Management</h3>
            <form action="{{route('userdash')}}" method="post">
                @csrf
                <input name="activity" type="hidden" value="user search" />
                <label>
                    Search:
                    <input name="query" type="search" value="{{$userQuery ?? ''}}" placeholder="name or email" onkeyup="if(event.keyCode===13) $('#sUserSubmit').click()" />
                </label>
                <input id="sUserSubmit" type="submit" value="🔍" />
            </form>

            @php($Null=null)
            <table id="userResults" class="table table-dark" style="border-radius:20px; overflow: hidden;" >
                <thead class="thead-light">
                    <th>Username</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </thead>
                @if($userResults ?? $Null !== null)
                @foreach($userResults ?? '' as $u)
                    <tr>
                        <td>{{$u->name}}</td>
                        <td>{{$u->email}}</td>
                        <td>
                            <form action="{{route('userdash')}}" method="post">
                                @csrf
                                <input name="activity" type="hidden" value="toggle admin" />
                                <input name="userId" type="hidden" value="{{$u->id}}" />
                                <input name="userQuery" type="hidden" value="{{$userQuery}}" />
                                <input type="submit" onclick="return confirm('You are converting user {{$u->name}} to a Support Admin!') && confirm('Not a tech but an ADMIN!! For real?') " value="Add Admin" />
                            </form>
                        </td>
                        <td>
                            <form action="{{route('userdash')}}" method="post">
                                @csrf
                                <input name="activity" type="hidden" value="toggle tech" />
                                <input name="userId" type="hidden" value="{{$u->id}}" />
                                <input name="userQuery" type="hidden" value="{{$userQuery}}" />
                                <input type="submit" onclick="return confirm('Converting user {{$u->name}} to a Support Technician!')" value="Add Technician" />
                            </form>
                        </td>
                        <td>
                            <form action="{{route('userdash')}}" method="post">
                                @csrf
                                <input name="activity" type="hidden" value="delete user" />
                                <input name="userId" type="hidden" value="{{$u->id}}" />
                                <input name="userQuery" type="hidden" value="{{$userQuery}}" />
                                <input type="submit"  value="❌ Delete user"
                                       onclick="
                                           var bigWarning ='Deleting user {{$u->name}}! \n Please note that user deletion should be performed only as a last resort and only by administrators who understand the potential repercussions.';
                                           var certainty ='Type \'Delete\' into the box and {{$u->name}} will be gone!';
                                           return confirm(bigWarning) && input(certainty)==='Delete'"
                                />
                            </form>
                        </td>
                    </tr>
                @endforeach
                @endif
            </table>

            @if( Auth::user()->isTechnician) {{-- if this Admin is also a technician let's nest a tech dashboard in here --}}
                <br/><hr /><br/>
                <div class="card"> {{-- new card --}}
                    <div class="card-header"> {{ __('Technician Dashboard') }} </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
            @endif
        @endif
        {{------------------------------------------------------------------------------------------------------}}
        @if( Auth::user()->isTechnician)  {{-- Technician Dashboard --}}

            <h3>Assigned To Me:</h3>
            <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                <thead class="thead-light">
                    <th>Title</th>
                    <th>Category</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                @foreach(App\Models\Ticket::all() as $t)
                    @if( $t->assignedTech() == Auth::user() ) {{-- $t->assignedTech()->id == Auth::user()->id ) --}}
                        <tr class="ticket-row" onclick="ticket{{$t->id}}.submit()" >
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->category()->categoryName  }}</td>
                            <td>{{ $t->details  }}</td>
                            <td>{{ $t->status()->statusName }}</td>
                            <td>
                                <form name="ticket{{$t->id}}" action="{{route('TicketDetail')}}" method="get" >
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
                    <th>Category</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </thead>
                @foreach(App\Models\Ticket::all() as $t)
                    @if($t->assignedTech()==null)
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->category()->categoryName  }}</td>
                            <td>{{ $t->details  }}</td>
                            <td>{{ $t->status()->statusName }}</td>
                            <td>
                                @if(Auth::user()->isAdmin)
                                    <form name="ticket{{$t->id}}" action="{{route('TicketDetail')}}" method="get" >
                                        @csrf
                                        <input type="hidden" name="id" value="{{$t->id}}" />
                                        <input type="submit" value="Open" />
                                    </form>
                                @else
                                    <form action="{{route('userdash')}}" method="post">
                                        @csrf
                                        <input name="activity" type="hidden" value="assign to me" />
                                        <input name="assTicketId" type="hidden" value="{{$t->id}}" />
                                        <input name="assBtn" type="submit" value="Assign to me" />
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>

            @if(Auth::user()->isAdmin)
                <hr />
                <h3>Assigned</h3>
                <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                    <thead class="thead-light">
                    <th>Title</th>
                    <th>Category</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                    </thead>
                    @foreach(App\Models\Ticket::all() as $t)
                        @if($t->assignedTech()!=null )
                            <tr>
                                <td>{{ $t->title }}</td>
                                <td>{{ $t->category()->categoryName  }}</td>
                                <td>{{ $t->details  }}</td>
                                <td>{{ $t->status()->statusName }}</td>
                                <td>
                                    <form name="ticket{{$t->id}}" action="{{route('TicketDetail')}}" method="get" >
                                        @csrf
                                        <input type="hidden" name="id" value="{{$t->id}}" />
                                        <input type="submit" value="Open" />
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            @endif

        {{------------------------------------------------------------------------------------------------------}}
        @elseif( !Auth::user()->isTechnician)  {{-- User Dashboard --}}
            <?php
                $myTickets = $Ticket::where('user_id', Auth::id())->get();
            ?>
            @if(count($myTickets)>0)
                <h3>My tickets</h3>
                <table class="table" style="border-radius: 20px; overflow: hidden">
                    <thead class="thead-light">
                        <th>Title</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th></th>
                    </thead>
                    @foreach( $myTickets as $t )
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->category()->categoryName  }}</td>
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
