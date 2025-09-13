@extends('layouts.registration')

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
    <registration-component :step-folder="'{{ $event->template_id }}'" :slots="{{ json_encode($slots) }}" :event="{{ $event }}" />
</div>
@endsection

@section('footer')
    @if ($event->with_booking && $event->slug != 7382159074)
        <footer class="footer shadow">
            <div class="container py-2">
                <center>
                    <span class="text-muted">Already registered? &nbsp;<a href="/{{ $event->slug }}/booking" class="el-link el-link--custom is-underline" style="color: {{ $event->border_color }}; text-decoration-color: {{ $event->border_color }} !important"><!----><span class="el-link--inner">Manage Booking</span><!----></a>
                </center>
            </div>
        </footer>
    @endif
@endsection