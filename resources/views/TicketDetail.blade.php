@extends('layouts.app')
@inject('Category', 'App\Models\Category')
@inject('Status', 'App\Models\Status')
@inject('Note', 'App\Models\Note')
@inject('User', 'App\Models\User')
@inject('Ticket', 'App\Models\Ticket')
@section('content')
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
                            Ticket Category: <br/>
                            <select name="ticketCategory" class="w-100"
                                onchange="
                                    if($('#removeCategoryOption')) $('#removeCategoryOption').remove();
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
                        <br /><br />
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
                        <br /><br />
                        <label class="w-100">
                            Describe the issue you are experiencing:<br />
                            <textarea class="w-100" name="ticketDetails" style="min-height: 200px;" readonly
                                >{{old('ticketDetails') ? old('ticketDetails') : $ticket->details}}</textarea>
                            @error('ticketDetails')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label><br />
                        <input type="submit" id="modifyTicket" class="w-auto" style="display: none;" value="Submit" />
                        <br /><br />
                    </form>
                    <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" name="activity" value="update status" />
                        <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                        <label class="w-100">
                            Ticket Category: <br/>
                            <select name="ticketStatus" class="w-100"
                                    onchange="
                                    if($('#removeCategoryOption')) $('#removeCategoryOption').remove();
                                    $('#updateStatus').css('display','inline-block');
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
                        </label>><br />
                        <input type="submit" id="updateStatus" class="w-auto" style="display: none;" value="Submit" />
                        <br /><br />
                    </form>
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
                        </label><br />
                        @error('newNote')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span><br />
                        @enderror
                        <br />
                        <input type="submit" value="Submit" />
                    </form>
                    <hr />
                    <table class="table table-dark" style="border-radius:20px; overflow: hidden;">
                        <thead class="thead-light">
                        <th>📆</th>
                        <th>Tech</th>
                        <th>Note</th>
                        <th style="width:96px;"></th>
                        </thead>
                        @foreach($Note::where('ticket_id',$ticket->id)->latest()->get() as $note)
                            <tr>
                                <td>{{$note->created_at->format('Y-m-d')}}<br/>{{$note->created_at->format('H:i:s')}}</td>
                                <td>{{$User::find($note->user_id)->name}}</td>
                                <td>{{$note->note}}</td>
                                <td>
                                    @if($note->user_id == Auth::id() )
                                        <form name="note{{$note->id}}" action="{{route('TicketDetail')}}" method="post">
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
