@extends('home')

@section('dashboard')
    @auth
	    Welcome, {{ Auth::user()->name }}.<br />
        @if( Auth::user()->isAdmin )
            Technician List:
            <table  >

            </table>
        @elseif( Auth::user()->isTechnician)
            Assigned To Me:
            <table>

            </table>
        @elseif( !Auth::user()->isTechnician)
            My open tickets
            <table>

            </table>
        @endif
    @endauth
@endsection
