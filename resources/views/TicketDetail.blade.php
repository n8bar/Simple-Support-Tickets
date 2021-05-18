@extends('layouts.app')
@inject('Category', 'App\Models\Category')
@inject('Status', 'App\Models\Status')
@inject('Note', 'App\Models\Note')
@inject('User', 'App\Models\User')
@inject('Ticket', 'App\Models\Ticket')
@section('content')
    <?php
    use Symfony\Component\Console\Input\Input;

    if ($ticket ?? false) {
        $ticket=$Ticket::find($_REQUEST['id']);
    }
    ?>
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <button onclick="location='{{ url('/') }}';" ><strong><</strong></button> {{ __('Ticket') }}# {{ $ticket->id }}
				</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" name="activity" value="modify ticket" />
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                        <label class="w-100">
                            Ticket Title: <br/>
                            <input class="w-100" type="text" id="ticketTitle" readonly
                                   value="{{ old('ticketTitle') ? old('ticketTitle') :  $ticket->title }}"
                            />
                            @error('ticketTitle')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                        <label class="w-100">
                            Describe the issue you are experiencing:<br />
                            <textarea class="w-100" name="ticketDetails" style="min-height: 200px;" readonly
                                >{{old('ticketDetails') ? old('ticketDetails') : $ticket->details}}</textarea>
                            @error('ticketDetails')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                        <label class="w-100">
                            Ticket Category: <br/>
                            <select name="ticketCategory" class="w-100"
                                    onchange="
                                    $('#removeCategoryOption').remove()
                                    $('#modifyTicket').css('display','inline-block');
                                "
                            >
                                @if(old('ticketCategory')<=0 || !old('ticketCategory'))
                                    <option value="" id="removeCategoryOption" >-</option>
                                @endif
                                @foreach($Category::all() as $category)
                                    <option value="{{$category->id}}"
                                            @if($category->id==old('ticketCategory'))
                                            selected
                                            @elseif( $ticket->category_id == $category->id )
                                            selected
                                        @endif>
                                        {{$category->categoryName}}
                                    </option>
                                @endforeach
                            </select>
                            @error('ticketCategory')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                        <input type="submit" id="modifyTicket" class="w-auto" style="display: none;" value="Update Ticket" />
                    </form>
                    <hr />
                    <h3>Status</h3>
                    <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" name="activity" value="update status" />
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                        <label class="w-100">
                            Current Status:
                            <select name="ticketStatus"
                                    onchange="
                                        $('#removeStatusOption').remove();
                                        $('#updateStatus').css('display','inline-block');
                                    ">
                                @if(old('ticketStatus')<=0 || !old('ticketStatus'))
                                    <option value="" id="removeStatusOption" >-</option>
                                @endif
                                @foreach($Status::all() as $status)
                                    <option value="{{$status->id}}"
                                            @if($status->id==old('ticketStatus'))
                                            selected
                                            @elseif( $ticket->status()->id == $status->id )
                                            selected
                                        @endif>
                                        {{$status->statusName}}
                                    </option>
                                @endforeach
                            </select>
                            @error('ticketStatus')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label><br />
                        <input type="submit" id="updateStatus" class="w-auto" style="display: none;" value="Update Status" />
                        <br /><br />
                    </form>
                    <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                        <thead class="thead-light">
                            <th>📆</th>
                            <th>Status</th>
                            <th>Updated By</th>
                        </thead>
                        @foreach(\App\Models\StatusChange::where('ticket_id',$ticket->id)->latest()->get() as $sc)
                        <tr>
                            <?php $u = \App\Models\User::find($sc->changed_by_tech_id) ?>
                            <?php $s = \App\Models\Status::find($sc->status_id) ?>
                            <td>{{$sc->created_at->format('Y-m-d')}}<br/>{{$sc->created_at->format('H:i:s')}}</td>
                            <td>{{$s->statusName}}</td>
                            <td>{{$u->name}}, {{$u->email}}</td>
                        </tr>
                        @endforeach
                    </table>

                    @if(Auth::user()->isAdmin)
                        <hr />
                        <h3>Assignment</h3>
                            <?php
                                $techName=$ticket->assignedTech()->name ?? 'unassigned';
                                $techEmail=$ticket->assignedTech()->email ?? '';
                            ?>
                            @if($techEmail=='')
                                This ticket is unassigned.
                            @else
                                Assigned to: {{$techName}}, {{$techEmail}}
                            @endif
                        <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                            @csrf
                            <input type="hidden" name="activity" value="update tech" />
                            <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                            <label>
                                {{($techEmail=='' ? "A" : "Rea")}}ssign to:
                                <select name="assToTech" onchange="$('#updateTech').css('display','inline-block'); $('#assTechRemoveOption').remove();" >
                                    <option id="assTechRemoveOption">-</option>
                                    @foreach($User::where('isTechnician',true)->orderby('name')->get() as $t)
                                        <option value="{{$t->id}}">{{$t->name}}, {{$t->email}}</option>
                                    @endforeach
                                </select>
                            </label>
                            <input type="submit" id="updateTech" class="w-auto" style="display: none;" value="Reassign" />
                        </form>
                        <br />
                        <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                            <thead class="thead-light">
                                <th>📆</th>
                                <th>Assigned To</th>
                                <th>Assigned By</th>
                            </thead>

                            @foreach(\App\Models\AssignmentChange::where('ticket_id',$ticket->id)->latest()->get() as $ac)
                            <tr>
                                <?php $nt = \App\Models\User::find($ac->new_tech_id) ?>
                                <?php $u = \App\Models\User::find($ac->changed_by_tech_id) ?>
                                <td>{{$ac->created_at->format('Y-m-d')}}<br/>{{$ac->created_at->format('H:i:s')}}</td>
                                <td>{{$nt->name}}, {{$nt->email}}</td>
                                <td>{{$u->name}}, {{$u->email}}</td>
                            </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>
            <br /><br />
            <div class="card">
                <div class="card-header">
                    Notes
                </div>
                <div class="card-body">
                    <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" name="activity" value="add note" />
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                        <label class="w-100">
                            New note:<br/>
                            <textarea name="newNote" class="w-100"></textarea>
                        </label>
                        @error('newNote')
                            <strong class="invalid-feedback" role="alert">{{ $message }}</strong><br /><br />
                        @enderror
                        <input type="submit" value="Submit" />
                    </form>
                    <hr />
                    <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                        <thead class="thead-light">
                        <th>📆</th>
                        <th>Author</th>
                        <th>Note</th>
                        <th style="width:192px;" colspan="2"></th>
                        </thead>
                        @foreach($Note::where('ticket_id',$ticket->id)->latest()->get() as $note)
                            <tr>
                                <td>{{$note->created_at->format('Y-m-d')}}<br/>{{$note->created_at->format('H:i:s')}}</td>
                                <td>{{$User::find($note->user_id)->name}}</td>
                                <td id="noteCell{{$note->id}}" class="noteCell" >
                                    <form class="noteForm" action="{{route('TicketDetail')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="activity" value="modify note" />
                                        <input type="hidden" name="noteId" value="{{$note->id}}" />
                                        <textarea name="noteNote" id="noteContent{{$note->id}}" class="noteCellText" readonly >{{$note->note}}</textarea>
                                        <input id="submitNoteModify{{$note->id}}" type="hidden" value="Save" />
                                    </form>
                                </td>
                                <td id="editCell{{$note->id}}" style="width:56pt">
                                    <button id="editNote{{$note->id}}" onclick="
                                        $('#noteContent{{$note->id}}').removeAttr('readonly');
                                        $('#noteCell{{$note->id}}').attr('colspan','2');
                                        $('#submitNoteModify{{$note->id}}').prop('type','submit');
                                        $('#noteContent{{$note->id}}').focus();
                                        $('#editCell{{$note->id}}').remove();
                                    ">Edit</button>
                                </td>
                                <td style="width:72pt">
                                    @if($note->user_id == Auth::id() )
                                        <form action="{{route('TicketDetail')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="activity" value="delete note" />
                                            <input type="hidden" name="noteId" value="{{$note->id}}" />
                                            <input type="submit" onclick="return confirm('Really delete this?')" value="Delete" />
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
