@extends('layouts.booking')

@section('style')
<style>
    .el-link--custom {
        color: {{ $event->border_color }};
    }
    
    .el-link.el-link--custom.is-underline:hover:after, .el-link.el-link--custom:after {
        border-color: {{ $event->border_color }} !important;
    }
</style>
@endsection

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
            <span class="text-muted">Not yet registered? &nbsp;<a href="/{{ $event->slug }}/registration" class="el-link el-link--custom is-underline" style="color: {{ $event->border_color }}; text-decoration-color: {{ $event->border_color }} !important"><!----><span class="el-link--inner">Register</a></span>
        </center>
    </div>
</footer>
@endsection