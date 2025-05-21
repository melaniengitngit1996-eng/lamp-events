@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/dashboard.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="mx-4">
    <el-breadcrumb class="mb-3 mx-2" separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/events' }"><a href="/events">Events</a></el-breadcrumb-item>
        <el-breadcrumb-item><i class="el-icon-date"></i>&nbsp;&nbsp;{{ $event->name }}</el-breadcrumb-item>
      </el-breadcrumb>

    <dashboard-component 
        :all="{{ json_encode($all) }}"
        :members="{{ json_encode($members) }}" 
        :guests="{{ json_encode($guests) }}"
        :trend="{{ json_encode($trend) }}"
        :progress="{{ json_encode($progress) }}"
        :received_hg="{{ json_encode($received_hg) }}"
        :event="{{ $event }}" />
</div>
@endsection

@section('footer')
<footer class="footer shadow">
    <div class="py-2 px-2">
        <span class="text-muted float-end">Dashboard as of {{ $member_current_date->format('l') }} {{ $guest_current_date->format('jS \of F Y') }} {{ date('h:i:s A') }}</span>&nbsp;
    </div>
</footer>
@endsection