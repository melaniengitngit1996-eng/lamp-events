@extends('layouts.app')

@section('scripts')
<script src="{{ asset('js/app.js?time=') }}{{ time() }}" defer></script>
@endsection

@section('content')
<div class="px-4">
    <el-tabs type="border-card">
        <el-tab-pane label="Rates">
            <span slot="label"><span class="el-icon-money"></span>&nbsp;&nbsp;Rate</span>
            <div>
                <div class="el-table el-table--fit el-table--border el-table--enable-row-hover el-table--enable-row-transition" style="width: 100%;">
                    <div class="hidden-columns">
                        <div></div> <div></div> <div></div>
                    </div>
                    <div class="el-table__header-wrapper">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__header" style="width: 819px;">
                            <colgroup>
                                <col name="el-table_26_column_102" width="166.6">
                                <col name="el-table_26_column_103" width="166.6">
                                <col name="el-table_26_column_104" width="166.6">
                                <col name="el-table_26_column_105" width="300">
                                <col name="el-table_26_column_106" width="300">
                                <col name="el-table_26_column_107" width="300">
                            </colgroup>
                            <thead class="">
                                <tr class="">
                                    <th colspan="1" rowspan="1" class="el-table_26_column_102 is-leaf el-table__cell">
                                        <div class="cell text-center">Category</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_103 is-leaf el-table__cell">
                                        <div class="cell text-center">Attending Option</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_104 is-leaf el-table__cell">
                                        <div class="cell text-center">Registration Type</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_105 is-leaf el-table__cell">
                                        <div class="cell">Description</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_106 is-leaf el-table__cell">
                                        <div class="cell text-center">Rate</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_107 is-leaf el-table__cell">
                                        <div class="cell text-center">Can Book Rate</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="el-table__body-wrapper is-scrolling-none">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__body" style="width: 819px;">
                            <colgroup>
                                <col name="el-table_26_column_102" width="166.6">
                                <col name="el-table_26_column_103" width="166.6">
                                <col name="el-table_26_column_104" width="166.6">
                                <col name="el-table_26_column_105" width="300">
                                <col name="el-table_26_column_106" width="300">
                                <col name="el-table_26_column_107" width="300">
                            </colgroup>
                            <tbody>
                                @foreach($rates as $rate)
                                <tr class="el-table__row">
                                    <td rowspan="1" colspan="1" class="el-table_26_column_102 el-table__cell">
                                        <div class="cell text-center">{{ $rate->category }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_103 el-table__cell">
                                        <div class="cell text-center">{{ $rate->attending_option }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_104 el-table__cell">
                                        <div class="cell text-center">{{ $rate->registration_type }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_105 el-table__cell">
                                        <div class="cell">{{ $rate->description }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_106 el-table__cell">
                                        <div class="cell text-center">{{ $rate->rate }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_107 el-table__cell">
                                        <div class="cell text-center">{{ $rate->can_book_rate }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="el-table__column-resize-proxy" style="display: none;">
                    </div>
                </div>
            </div>
        </el-tab-pane>
        <el-tab-pane label="Available IDs">
            <span slot="label"><span class="el-icon-postcard"></span>&nbsp;&nbsp;Available IDs</span>
            <div>
                <div class="el-table el-table--fit el-table--border el-table--enable-row-hover el-table--enable-row-transition" style="width: 100%;">
                    <div class="hidden-columns">
                        <div></div> <div></div> <div></div>
                    </div>
                    <div class="el-table__header-wrapper">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__header" style="width: 819px;">
                            <colgroup>
                                <col name="el-table_26_column_102" width="250">
                                <col name="el-table_26_column_103" width="250">
                                <col name="el-table_26_column_104" width="300">
                                <col name="el-table_26_column_105" width="300">
                                <col name="el-table_26_column_106" width="300">
                            </colgroup>
                            <thead class="">
                                <tr class="">
                                    <th colspan="1" rowspan="1" class="el-table_26_column_102 is-leaf el-table__cell">
                                        <div class="cell text-center">Local Church</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_103 is-leaf el-table__cell">
                                        <div class="cell text-center">Prefix</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_104 is-leaf el-table__cell">
                                        <div class="cell text-center">Start</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_105 is-leaf el-table__cell">
                                        <div class="cell text-center">End</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_106 is-leaf el-table__cell">
                                        <div class="cell text-center">Cursor</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="el-table__body-wrapper is-scrolling-none">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__body" style="width: 819px;">
                            <colgroup>
                                <col name="el-table_26_column_102" width="250">
                                <col name="el-table_26_column_103" width="250">
                                <col name="el-table_26_column_104" width="300">
                                <col name="el-table_26_column_105" width="300">
                                <col name="el-table_26_column_106" width="300">
                            </colgroup>
                            <tbody>
                                @foreach($availables as $available)
                                <tr class="el-table__row">
                                    <td rowspan="1" colspan="1" class="el-table_26_column_102 el-table__cell">
                                        <div class="cell text-center">{{ $available->local_church }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_103 el-table__cell">
                                        <div class="cell text-center">{{ $available->prefix }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_104 el-table__cell">
                                        <div class="cell text-center">{{ $available->start }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_105 el-table__cell">
                                        <div class="cell text-center">{{ $available->end }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_106 el-table__cell">
                                        <div class="cell text-center">{{ $available->cursor }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="el-table__column-resize-proxy" style="display: none;">
                    </div>
                </div>
            </div>
        </el-tab-pane>
        <el-tab-pane label="Slots">
            <span slot="label"><span class="el-icon-date"></span>&nbsp;&nbsp;Slots</span>
            <div>
                <div class="el-table el-table--fit el-table--border el-table--enable-row-hover el-table--enable-row-transition" style="width: 100%;">
                    <div class="hidden-columns">
                        <div></div> <div></div> <div></div>
                    </div>
                    <div class="el-table__header-wrapper">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__header">
                            <colgroup>
                                <col name="el-table_26_column_102" width="250">
                                <col name="el-table_26_column_103" width="250">
                                <col name="el-table_26_column_104" width="250">
                            </colgroup>
                            <thead class="">
                                <tr class="">
                                    <th colspan="1" rowspan="1" class="el-table_26_column_102 is-leaf el-table__cell">
                                        <div class="cell text-center">Event Date</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_103 is-leaf el-table__cell">
                                        <div class="cell text-center">Seat Count</div>
                                    </th>
                                    <th colspan="1" rowspan="1" class="el-table_26_column_104 is-leaf el-table__cell">
                                        <div class="cell text-center">Registration Type</div>
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="el-table__body-wrapper is-scrolling-none">
                        <table cellspacing="0" cellpadding="0" border="0" class="el-table__body">
                            <colgroup>
                                <col name="el-table_26_column_102" width="250">
                                <col name="el-table_26_column_103" width="250">
                                <col name="el-table_26_column_104" width="250">
                            </colgroup>
                            <tbody>
                                @foreach($slots as $slot)
                                <tr class="el-table__row">
                                    <td rowspan="1" colspan="1" class="el-table_26_column_102 el-table__cell">
                                        <div class="cell text-center">{{ $slot->event_date }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_103 el-table__cell">
                                        <div class="cell text-center">{{ $slot->seat_count }}</div>
                                    </td>
                                    <td rowspan="1" colspan="1" class="el-table_26_column_104 el-table__cell">
                                        <div class="cell text-center">{{ $slot->registration_type }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="el-table__column-resize-proxy" style="display: none;">
                    </div>
                </div>
            </div>
        </el-tab-pane>
        {{-- <el-tab-pane label="Users">
            <span slot="label"><span class="el-icon-s-custom"></span>&nbsp;&nbsp;Users</span>
        </el-tab-pane> --}}
      </el-tabs>
</div>
@endsection