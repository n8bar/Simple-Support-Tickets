@extends('home')

@section('dashboard')
	Experiencing an issue?<br />
	Please <a href="{{ route('login') }}">sign in</a> or <a href="{{ route('register') }}">create a new account</a> and let's get this resolved.<br />
	We're here to improve your experience. Thank you for the opportunity.
@endsection
