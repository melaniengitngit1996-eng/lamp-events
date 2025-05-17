@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/payment.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-breadcrumb class="mb-4 mx-2" separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/home">All Registrations</a></el-breadcrumb-item>
                <el-breadcrumb-item class="text-highlight">View Details</el-breadcrumb-item>
            </el-breadcrumb>

            <div class="el-tabs el-tabs--top el-tabs--border-card">
                <div class="el-tabs__header is-top">
                    <div class="el-tabs__nav-wrap is-top">
                        <div class="el-tabs__nav-scroll">
                            <div role="tablist" class="el-tabs__nav is-top" style="transform: translateX(0px);">
                                <div id="tab-0" aria-controls="pane-0" role="tab" tabindex="0" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/registration/{{ $registration->id }}/edit">Registration Details</el-link>
                                </div>
                                <div id="tab-1" aria-controls="pane-1" role="tab" tabindex="-1" class="el-tabs__item is-top is-active" aria-selected="true">
                                    <el-link :underline="false" href="/payments/{{ $registration->id }}/create">Payments</el-link>
                                </div>
                                @if (auth()->user()->permissions->can_edit_delegate === 1 && $registration->attending_option === 'Hybrid')
                                <div id="tab-2" aria-controls="pane-2" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/booking/{{ $registration->id }}/edit">Booking</el-link>
                                </div>
                                @endif
                                <div id="tab-3" aria-controls="pane-3" role="tab" tabindex="-1" class="el-tabs__item is-top">
                                    <el-link :underline="false" href="/ticket/{{ $registration->id }}/edit">Ticket</el-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="el-tabs__content">
                    <div class="card mb-3">
                        <div class="card-header">{{ __('Payments') }}</div>

                        <div class="card-body pb-0">
                            <div class="row justify-content-center">
                                <div class="col-md-4 mb-3">
                                    <small>Name</small>
                                    <span class="text-lg font-bold d-block text-uppercase">{{ $registration->firstname }} {{ $registration->lastname }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small>AWTA Card Number</small>
                                    <span class="text-md font-bold d-block">{{ $registration->uuid }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small>Date Registered</small>
                                    <span class="text-lg font-bold d-block">{{ date("M d, Y h:i A", strtotime($registration->created_at)) }}</span>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4 mb-3">
                                    <small>Local Church</small>
                                    <span class="text-md font-bold d-block">{{ $registration->local_church }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small>Registration Type</small>
                                    <span class="text-lg font-bold d-block">{{ $registration->registration_type }}</span>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <small>Country</small>
                                    <span class="text-md font-bold d-block">{{ $registration->country }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <add-payment ref="child" registration="{{ $registration }}" :user="{{ $user }}" :balance="{{ floatval($balance) }}"/>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection