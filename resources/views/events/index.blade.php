@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="mx-4">
    <el-tabs type="border-card">
        <el-tab-pane label="Events">
            <events-component :events="{{ $events }}" :event_ids="{{ $event_ids }}" />
        </el-tab-pane>
        <el-tab-pane label="Users">
            <users-component :events="{{ $events }}" />
        </el-tab-pane>
    </el-tabs>
</div>
@endsection