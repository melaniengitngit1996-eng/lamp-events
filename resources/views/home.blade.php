@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/registration.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="mx-4">
    <el-breadcrumb class="mb-3 mx-2" separator-class="el-icon-arrow-right">
        <el-breadcrumb-item :to="{ path: '/events' }"><a href="/events">Events</a></el-breadcrumb-item>
        <el-breadcrumb-item><i class="el-icon-date"></i>&nbsp;&nbsp;{{ $event->name }}</el-breadcrumb-item>
    </el-breadcrumb>

    <el-tabs type="border-card" value="{{ $tab }}">
        {{-- Registration --}}
        <el-tab-pane label="Registrations">
            <registration-table :event="{{ $event }}"/>                        
        </el-tab-pane>

        {{-- Look Up --}}
        <el-tab-pane label="Look Up">
            <lookups-table :event="{{ $event }}"/>
        </el-tab-pane>

        <el-tab-pane label="Attendance">
            <attendances-table :event="{{ $event }}"/>
        </el-tab-pane>

        {{-- Bookings --}}
        <el-tab-pane label="Bookings">
            <!-- {{json_encode($slots)}} -->
            <booking-table :slots="{{ json_encode($slots) }}" />
        </el-tab-pane>

        {{-- Attendance --}}
        <el-tab-pane label="Attendance Count">
            <attendance-table :count="{{ $count }}" :overall="{{ $overall }}" :overall_total="{{ $overall_total }}" />
        </el-tab-pane>

        @if (auth()->user()->permissions->can_add_slots === 1)
            <el-tab-pane label="Slots">
                <slots-table :slots="{{ $slots_list }}" :event="{{ $event }}" />
            </el-tab-pane>
        @endif

        <el-tab-pane label="Received HG">
            <received-hg-table :event="{{ $event }}" />
        </el-tab-pane>
    </el-tabs>
</div>
@endsection
