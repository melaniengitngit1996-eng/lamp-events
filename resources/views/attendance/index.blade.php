@extends('layouts.attendance')

@section('scripts')
<script src="{{ asset('js/attendance.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<attendance-component :count="{{ $count }}" :event="{{ $event }}" />
@endsection

@section('footer')
<footer class="footer shadow">
    <div class="py-2 px-2">
        <span class="text-muted float-end">Today is {{ $member_current_date->format('l') }} {{ $guest_current_date->format('jS \of F Y') }} {{ date('h:i:s A') }}</span>&nbsp;
    </div>
</footer>
@endsection