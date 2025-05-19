@extends('layouts.dashboard')

@section('content')
    <dashboard-received-hg-component :data="{{ json_encode($data) }}" :event="{{ $event }}" :days="{{ $days }}"/>
@endsection