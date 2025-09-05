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
    @if ($event->with_booking)
        <footer class="footer shadow">
            <div class="container py-2">
                <center>
                    <span class="text-muted">Already registered? &nbsp;<el-link type="success" href="/{{ $event->slug }}/booking">Manage Booking</el-link></span>
                </center>
            </div>
        </footer>
    @endif
@endsection