@extends('layouts.registration')

@section('content')
<div class="px-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-link type="primary" class="float-end" href="/registration">Register Another Delegate</el-link>
        </div>
    </div>
    
    <div class="row justify-content-center mb-4">
        <ticket-component :registrations="{{ json_encode($registration) }}" :event="{{ $event }}" :congratulate="true"/>
    </div>
</div>
@endsection