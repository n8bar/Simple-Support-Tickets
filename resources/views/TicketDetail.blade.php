@extends('layouts.app')
@inject('Category', 'App\Models\category')
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
                        </label><br />
                        <input type="submit" id="modifyTicket" class="w-auto" style="display: none;" value="Submit" />
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
                        </label>
                        <br /><br />
                    </form>
                </div>
            </div>
            <br /><br />
            <div class="card">
                <div class="card-header">
                    Notes
                </div>
                <form action="{{route('TicketDetail')}}" method="post" class="w-100">
                    @csrf
                    <input type="hidden" name="activity" value="add note" />
                    <input type="hidden" name="ticketId" value="{{$ticket->id}}" />
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
