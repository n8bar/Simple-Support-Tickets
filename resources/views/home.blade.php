@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
					@guest
						Welcome to Playcryptos.com Support!
                    @endguest
					@auth
						{{ __('Dashboard') }}
                    @endauth
				</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

					@yield('dashboard')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
