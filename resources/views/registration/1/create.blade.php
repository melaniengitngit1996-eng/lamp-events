@extends('layouts.registration')

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
                    <span class="text-muted">Already registered? &nbsp;<el-link type="success" href="/booking">Manage Booking</el-link></span>
                </center>
            </div>
        </footer>
    @endif
@endsection