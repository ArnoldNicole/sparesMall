@extends('layouts.app')

@section('content')
<admin-app :user="{{auth()->user()}}">
	
</admin-app>
@endsection