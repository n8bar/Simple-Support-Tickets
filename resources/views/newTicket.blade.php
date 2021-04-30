@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('New Ticket') }}
				</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{route('newTicket')}}" method="post">
                        @csrf
                        <label>

                        </label>


                        <input type="submit" value="Submit" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
