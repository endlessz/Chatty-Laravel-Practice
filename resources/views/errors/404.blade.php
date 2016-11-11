@extends('templates.default')

@section('content')
	<div class="text-center">
		<h2>Opps, that page could not be found.</h2>
		<a href="{{ route('home')}}">Go home</a>
	</div>
@stop