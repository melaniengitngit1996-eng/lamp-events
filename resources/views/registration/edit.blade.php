@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-breadcrumb class="mx-2 mb-4" separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/{{ $event->slug }}/home">All Registrations</a></el-breadcrumb-item>
                <el-breadcrumb-item class="text-highlight">View Details</el-breadcrumb-item>
            </el-breadcrumb>

            <div class="el-tabs el-tabs--top el-tabs--border-card">
                <div class="el-tabs__header is-top">
                    <div class="el-tabs__nav-wrap is-top">
                        <div class="el-tabs__nav-scroll">
                            <div role="tablist" class="el-tabs__nav is-top" style="transform: translateX(0px);">
                                <div id="tab-0" aria-controls="pane-0" role="tab" tabindex="0" class="el-tabs__item is-top is-active" aria-selected="true">
                                    <el-link :underline="false" href="/{{ $event->slug }}/registration/{{ $registration->id }}/edit">Registration Details</el-link>
                                </div>
                                <div id="tab-1" aria-controls="pane-1" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/payments/{{ $registration->id }}/create">Payments</el-link>
                                </div>
                                @if (auth()->user()->permissions->can_edit_delegate === 1 && $registration->attending_option === 'Hybrid')
                                <div id="tab-2" aria-controls="pane-2" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/booking/{{ $registration->id }}/edit">Booking</el-link>
                                </div>
                                @endif
                                <div id="tab-3" aria-controls="pane-3" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/{{ $event->slug }}/ticket/{{ $registration->id }}/edit">Ticket</el-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="el-tabs__content">
                    <div role="tabpanel" id="pane-0" aria-labelledby="tab-0" class="el-tab-pane" active>
                        <edit-registration-component :registration="{{ $registration }}" :event="{{ $event }}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="justify-content-center my-lg-5 row">
        @if (count($registration->notes) > 0)
        <div class="col-md-3">
            <label class="mb-3 text-secondary">Notes</label>
            @foreach ($registration->notes as $note)
            <el-card class="mb-3" shadow="never">
                <b>{{ $note['user']}}</b>: {{ $note['message'] }}<br />
                <small>{{ $note['timestamp'] }}</small>
            </el-card>
            @endforeach
        </div>
        @endif
        <div @if (count($registration->notes) > 0) class="col-md-6" @else class="col-md-9" @endif>
            @if (count($registration->activities) > 0)
                <label class="mb-3 text-secondary">Activity</label>
                <el-timeline>
                    @foreach ($registration->activities as $activity)
                    <el-timeline-item
                        type="default"
                        size="large"
                        timestamp="{{ $activity['timestamp'] }}">
                        {{ $activity['user'] }} {{ $activity['message'] }}
                    </el-timeline-item>
                    @endforeach
                </el-timeline>
            @else
            <el-empty description="No recorded activity"></el-empty>
            @endif
        </div>
    </div>
</div>
@endsection