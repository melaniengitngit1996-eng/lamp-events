<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="border-0 m-4 card shadow">
                    <div class="card-body">
                        <table class="border text-center w-full" style="width: 100%">
                            <tr>
                                <td class="border px-2 py-1" colspan="8">Overall Unique Count<br /><small>Physical Attendees</small></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="border px-2 py-1" colspan="2">Cancelled Bookings</td>
                                <td class="border px-2 py-1" colspan="2">Pending Bookings</td>
                                <td class="border px-2 py-1" colspan="3">Attendance <br /><small>Confirmed Bookings</small></td>
                            </tr>
                            <tr>
                                <td class="border px-2 py-1"></td>
                                <td class="border px-2 py-1">Member</td>
                                <td class="border px-2 py-1">Guest</td>
                                <td class="border px-2 py-1">Member</td>
                                <td class="border px-2 py-1">Guest</td>
                                <td class="border px-2 py-1">Member</td>
                                <td class="border px-2 py-1">Guest</td>
                                <td class="border px-2 py-1">Total</td>
                            </tr>
                            <tr v-for="lc in overall">
                                <td class="border px-2 py-1">{{ lc.local_church }}</td>
                                <td class="border px-2 py-1">{{ lc.count.member.cancelled }}</td>
                                <td class="border px-2 py-1">{{ lc.count.guest.cancelled }}</td>
                                <td class="border px-2 py-1">{{ lc.count.member.pending }}</td>
                                <td class="border px-2 py-1">{{ lc.count.guest.pending }}</td>
                                <td class="border px-2 py-1">{{ lc.count.member.attended }} / {{ lc.count.member.total }}</td>
                                <td class="border px-2 py-1">{{ lc.count.guest.attended }} / {{ lc.count.guest.total }}</td>
                                <td class="border px-2 py-1">{{ lc.count.guest.attended + lc.count.member.attended }} / {{ lc.count.guest.total + lc.count.member.total }}</td>
                            </tr>
                            <tr>
                                <td class="border px-2 py-1">Total</td>
                                <td class="border px-2 py-1">{{ overall_total.member.cancelled }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.cancelled }}</td>
                                <td class="border px-2 py-1">{{ overall_total.member.pending }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.pending }}</td>
                                <td class="border px-2 py-1">{{ overall_total.member.attended }} / {{ overall_total.member.total }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.attended }} / {{ overall_total.guest.total }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.attended + overall_total.member.attended }} / {{ overall_total.guest.total + overall_total.member.total }}</td>
                            </tr>

                            <tr style="border-top: 2px solid lightgray;">
                                <td class="border px-2 py-1" style="text-align: right;" colspan="5">Present</td>
                                <td class="border px-2 py-1">{{ overall_total.member.attended }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.attended }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.attended + overall_total.member.attended }}</td>
                            </tr>
                            <tr>
                                <td class="border px-2 py-1" style="text-align: right;" colspan="5">Not Yet Present</td>
                                <td class="border px-2 py-1">{{  overall_total.member.total - overall_total.member.attended }}</td>
                                <td class="border px-2 py-1">{{ overall_total.guest.total - overall_total.guest.attended }}</td>
                                <td class="border px-2 py-1">{{ (overall_total.guest.total + overall_total.member.total) - (overall_total.guest.attended + overall_total.member.attended) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mb-0 text-black-50"></hr>
        <div class="row">
            <template v-for="(item, index) in count">
                <div class="col-md-6">
                    <div class="border-0 m-4 card shadow">
                        <div class="card-body">
                            <table class="border text-center w-full" style="width: 100%">
                                <tr>
                                    <td class="border px-2 py-1" colspan="4">{{ item.event_date }}</td>
                                </tr>
                                <tr>
                                    <td class="border px-2 py-1"></td>
                                    <td class="border px-2 py-1">Member</td>
                                    <td class="border px-2 py-1">Guest</td>
                                    <td class="border px-2 py-1">Total</td>
                                </tr>
                                <tr v-for="lc in item.count">
                                    <td class="border px-2 py-1">{{ lc.local_church }}</td>
                                    <td class="border px-2 py-1">{{ lc.count.member.attended }} / {{ lc.count.member.total }}</td>
                                    <td class="border px-2 py-1">{{ lc.count.guest.attended }} / {{ lc.count.guest.total }}</td>
                                    <td class="border px-2 py-1">{{ lc.count.guest.attended + lc.count.member.attended }} / {{ lc.count.guest.total + lc.count.member.total }}</td>
                                </tr>
                                <tr>
                                    <td class="border px-2 py-1">Total</td>
                                    <td class="border px-2 py-1">{{ item.overall.member.attended }} / {{ item.overall.member.total }}</td>
                                    <td class="border px-2 py-1">{{ item.overall.guest.attended }} / {{ item.overall.guest.total }}</td>
                                    <td class="border px-2 py-1">{{ item.overall.guest.attended + item.overall.member.attended }} / {{ item.overall.guest.total + item.overall.member.total }}</td>
                                </tr>

                                <tr style="border-top: 2px solid lightgray;">
                                    <td class="border px-2 py-1">Present</td>
                                    <td class="border px-2 py-1">{{ item.overall.member.attended }}</td>
                                    <td class="border px-2 py-1">{{ item.overall.guest.attended }}</td>
                                    <td class="border px-2 py-1">{{ item.overall.guest.attended + item.overall.member.attended }}</td>
                                </tr>
                                <tr>
                                    <td class="border px-2 py-1">Not Yet Present</td>
                                    <td class="border px-2 py-1">{{ item.overall.member.total - item.overall.member.attended }}</td>
                                    <td class="border px-2 py-1">{{ item.overall.guest.total - item.overall.guest.attended }}</td>
                                    <td class="border px-2 py-1">{{ (item.overall.guest.total + item.overall.member.total) - (item.overall.guest.attended + item.overall.member.attended) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        count: {
            required: true,
            type: Array
        },
        overall: {
            required: true,
            type: Array
        },
        overall_total: {
            required: true,
            type: Object
        }
    }
}
</script>