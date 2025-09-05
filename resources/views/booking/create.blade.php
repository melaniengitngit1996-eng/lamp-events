@extends('layouts.booking')

@section('content')
<div class="px-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <img width="100%" class="mb-3 rounded shadow" src="/images/banners/{{ $event->banner_file_name }}">
        </div>
    </div>
    
    <manage-booking :event="{{ $event }}" />
</div>
@endsection

@section('footer')
<footer class="footer shadow">
    <div class="container py-2">
        <center>
            <span class="text-muted">Not yet registered? &nbsp;<el-link href="/registration" type="success">Register</el-link></span>
        </center>
    </div>
</footer>
@endsection