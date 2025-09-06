@extends('layouts.registration')

@section('content')
<div class="px-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-link type="primary" class="float-end" href="/{{ $event->slug }}/booking">Book Another Delegate</el-link>
        </div>
    </div>
    
    <div class="row justify-content-center my-4">
        <ticket-component :registrations="{{ json_encode([$registration]) }}" :event="{{ $event }}"/>
    </div>
</div>
@endsection