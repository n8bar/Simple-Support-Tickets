@extends('layouts.app')
@inject('Category', 'App\Models\Category')
@inject('Status', 'App\Models\Status')
@inject('Note', 'App\Models\Note')
@inject('User', 'App\Models\User')
@inject('Ticket', 'App\Models\Ticket')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @guest
                            Welcome to Playcryptos.com Support!
                        @endguest
                        @auth
                            {{ __('Reports') }}
                        @endauth
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(!$User::find(Auth::id())->isAdmin)
                            <br/>You must be logged in as an administrator to view these reports.
                        @endif
                        @auth
                            <form action="{{route('Reports')}}" method="post">
                                @csrf
                                <input type=hidden name="activity" value="choose report" />
                                <select name="reportId" >
                                    <option>- Please select a report -</option>
                                    <option value="DTR">Daily Ticket Resolution</option>
                                    <option value="TART">Ticket Average Resolution Time</option>
                                </select>
                                <input type="submit" value="Run" />
                            </form>

                            @if($title ?? '' != '')
                                <hr />
                                <h3>{{$title}}</h3>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
