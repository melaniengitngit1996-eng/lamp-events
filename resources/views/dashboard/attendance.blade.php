@extends('layouts.dashboard')

@section('content')
<dashboard-attendance-component :absents="{{ json_encode($absents) }}" :event="{{ $event }}" :days="{{ $days }}"/>
@endsection