@extends('layouts.registration')

@section('content')
<div class="px-4">
    <div class="row justify-content-center my-4">
        <ticket-component :registrations="{{ json_encode([$registration]) }}" :event="{{ $event }}"/>
    </div>
</div>
@endsection