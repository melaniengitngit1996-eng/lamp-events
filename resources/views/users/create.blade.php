@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-breadcrumb class="mx-2 mb-4" separator-class="el-icon-arrow-right">
                <el-breadcrumb-item><a href="/users">All Users</a></el-breadcrumb-item>
                <el-breadcrumb-item class="text-highlight">New</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="col-md-12">
            <el-card class="box-card">
                <create-user-component />
              </el-card>
        </div>
    </div>
</div>
@endsection