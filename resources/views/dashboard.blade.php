@extends('layouts.main')

@section('content')
<div class="mx-4">
    <dashboard-component 
        :all="{{ json_encode($all) }}"
        :members="{{ json_encode($members) }}" 
        :guests="{{ json_encode($guests) }}"
        :trend="{{ json_encode($trend) }}"
        :progress="{{ json_encode($progress) }}"
        :received_hg="{{ json_encode($received_hg) }}" />
</div>
@endsection

@section('footer')
{{-- <footer class="footer shadow">
    <div class="py-2 px-2">
        <span class="text-muted float-end">Dashboard as of {{ $member_current_date->format('l') }} {{ $guest_current_date->format('jS \of F Y') }} {{ date('h:i:s A') }}</span>&nbsp;
    </div>
</footer> --}}
@endsection