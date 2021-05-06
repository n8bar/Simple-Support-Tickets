@extends('layouts.app')
@inject('Category', 'App\Models\category')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <button onclick="location='{{ url('/') }}';" ><strong><</strong></button> {{ __('Ticket') }}
				</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('newTicket')}}" method="post" class="w-100">
                        @csrf
                        <label class="w-100">
                            Ticket Category: <br/>
                            <select name="ticketCategory" class="w-100" onchange=" if($('#removeCategoryOption')) $('#removeCategoryOption').remove();  " >
                                @if(old('ticketCategory')<=0 || !old('ticketCategory'))
                                    <option value="" id="removeCategoryOption" >-</option>
                                @endif
                                @foreach($Category::all() as $category)
                                    <option value="{{$category->id}}" @if($category->id==old('ticketCategory')) selected @endif>{{$category->name}}</option>
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
                            <input class="w-100" type="text" name="ticketTitle" value="{{ old('ticketTitle') }}" />
                            @error('ticketTitle')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                        <br /><br />
                        <label class="w-100">
                            Describe the issue you are experiencing:<br />
                            <textarea class="w-100" name="ticketDetails" style="min-height: 200px;" placeholder="Include as much relevant information as you can." >{{ old('ticketDetails') }}</textarea>
                            @error('ticketDetails')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                        <br /><br />
                        <input type="submit" class="w-auto" value="Submit" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
