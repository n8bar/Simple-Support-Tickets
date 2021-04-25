@extends('home')

@section('dashboard')
	Welcome, {{ Auth::user()->name }}.<br />
	<table  >
		
	</table>
@endsection
