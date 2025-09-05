@extends('layouts.registration')

@section('content')
<div class="px-4">
    <registration-component :slots="{{ json_encode($slots) }}"/>
</div>
@endsection

@section('footer')
<footer class="footer shadow">
    <div class="container py-2">
        <center>
            <span class="text-muted">Already registered? &nbsp;<el-link type="success" href="/{{ $event->slug }}/booking">Manage Booking</el-link></span>
        </center>
    </div>
</footer>
@endsection