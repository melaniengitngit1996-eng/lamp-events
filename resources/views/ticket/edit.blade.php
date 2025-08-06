@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-breadcrumb class="mb-4 mx-2" separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/{{ $event->slug }}/home">All Registrations</a></el-breadcrumb-item>
                <el-breadcrumb-item class="text-highlight">View Details</el-breadcrumb-item>
            </el-breadcrumb>

            <div class="el-tabs el-tabs--top el-tabs--border-card">
                <div class="el-tabs__header is-top">
                    <div class="el-tabs__nav-wrap is-top">
                        <div class="el-tabs__nav-scroll">
                            <div role="tablist" class="el-tabs__nav is-top" style="transform: translateX(0px);">
                                <div id="tab-0" aria-controls="pane-0" role="tab" tabindex="0" class="el-tabs__item is-top" aria-selected="true">
                                    <el-link :underline="false" href="/{{ $event->slug }}/registration/{{ $registration->id }}/edit">Registration Details</el-link>
                                </div>
                                <div id="tab-1" aria-controls="pane-1" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/payments/{{ $registration->id }}/create">Payments</el-link>
                                </div>
                                @if (auth()->user()->permissions->can_edit_delegate === 1 && $registration->attending_option != 'Online')
                                <div id="tab-2" aria-controls="pane-2" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/booking/{{ $registration->id }}/edit">Booking</el-link>
                                </div>
                                @endif
                                <div id="tab-3" aria-controls="pane-3" role="tab" tabindex="-1" class="el-tabs__item is-active is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/ticket/{{ $registration->id }}/edit">Ticket</el-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="el-tabs__content">
                    <ticket-component :registrations="{{ json_encode([$registration]) }}" :event="{{ $event }}"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection