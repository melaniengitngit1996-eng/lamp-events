@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-breadcrumb class="mb-4 mx-2" separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/{{ $event->slug }}/home?type=lookup">Look Up List</a></el-breadcrumb-item>
                <el-breadcrumb-item class="text-highlight">Create Details</el-breadcrumb-item>
            </el-breadcrumb>

            <create-lookup-component :event="{{ $event }}" />
        </div>
    </div>
</div>
@endsection