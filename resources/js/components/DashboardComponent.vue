<template>
    <div class="container" style="max-width: 100% !important; margin: 0">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">Overall Attendance</div>

                    <div class="card-body">
                        <Bar
                            id="my-chart-id"
                            :options="chartOptions"
                            :data="allData"
                        />
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Members Attendance</div>

                    <div class="card-body">
                        <Bar
                            id="my-chart-id"
                            :options="chartOptions"
                            :data="memberData"
                        />
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Guests Attendance</div>

                    <div class="card-body">
                        <Bar
                            id="my-chart-id"
                            :options="chartOptions"
                            :data="guestData"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">
                        Attendance Progress Per Local Church
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div
                                class="col-md-6 mb-4"
                                v-for="data in progressData"
                            >
                                <small>{{ data.local_church }} Church</small>
                                <br />
                                <el-progress
                                    class="d-inline"
                                    :text-inside="true"
                                    :stroke-width="15"
                                    :color="colors"
                                    :percentage="parseInt(data.percentage)"
                                ></el-progress
                                >&nbsp;&nbsp;<small
                                    >{{ data.actual_attendance }} out of
                                    {{ data.expected_attendance }} is present
                                    today &nbsp;<el-link
                                        style="font-size: 0.875em"
                                        type="primary"
                                        :underline="true"
                                        @click="
                                            view_attendance(data.local_church)
                                        "
                                        >View Details</el-link
                                    ></small
                                >
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-5">
                        <div class="card mb-3">
                            <div class="card-header">Attendance Trend</div>

                            <div class="card-body">
                                <LineChartGenerator
                                    :data="trendData"
                                    :options="chartOptions"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="card mb-3">
                            <div class="card-header">
                            Received the Holy Ghost
                            </div>

                            <div class="card-body">
                                <el-table
                                    size="mini"
                                    border
                                    :data="received_hg"
                                    show-summary
                                    :summary-method="getSummaries"
                                    style="width: 100%">
                                        <el-table-column type="expand" align="center">
                                            <template slot-scope="props">
                                                <el-table
                                                    :data="props.row.local_churches"
                                                    border
                                                    size="mini"
                                                    class="mx-2"
                                                    style="width: 97%"
                                                    cell-style="padding: 0; font-size: 10px">
                                                        <el-table-column
                                                        prop="local_church"
                                                        label="Local Church"
                                                        width="180">
                                                        </el-table-column>
                                                        <el-table-column label="Guest" prop="guest.count" align="center" sortable>
                                                            <template slot-scope="scope">
                                                                {{ scope.row.guest.count }}
                                                            </template>
                                                        </el-table-column>
                                                        <el-table-column label="Member" prop="member.count" align="center" sortable>
                                                            <template slot-scope="scope">
                                                                {{ scope.row.member.count }}
                                                            </template>
                                                        </el-table-column>
                                                </el-table>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Day" align="center">
                                            <template slot-scope="scope">
                                                {{ scope.row.day }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Guest" prop="guest.count" align="center"></el-table-column>
                                        <el-table-column label="Member" prop="member.count" align="center"></el-table-column>
                                        <el-table-column align="center" width="100">
                                            <template slot-scope="scope">
                                            <small>
                                                <el-link
                                                    style="font-size: 11px"
                                                    type="primary"
                                                    @click="
                                                        view_received_hg(scope.row.day)
                                                    "
                                                    >View Details
                                                </el-link>
                                            </small>
                                        </template>
                                        </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { Bar } from "vue-chartjs";
import { Line as LineChartGenerator } from "vue-chartjs";

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement
);

export default {
    props: {
        all: {
            type: Object,
            default: false,
        },
        members: {
            type: Object,
            default: false,
        },
        guests: {
            type: Object,
            default: false,
        },
        trend: {
            type: Object,
            default: false,
        },
        progress: {
            type: Object,
            defaults: false,
        },
        received_hg: {
            type: Array,
            defaults: false,
        }
    },
    name: "BarChart",
    components: { Bar, LineChartGenerator },
    data() {
        return {
            allData: this.all.data,
            memberData: this.members.data,
            guestData: this.guests.data,
            trendData: this.trend,
            progressData: this.progress,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: true,
            },
            colors: [
                { color: "rgb(255, 73, 73)", percentage: 25 },
                { color: "rgb(230, 162, 60)", percentage: 50 },
                { color: "rgb(32, 160, 255)", percentage: 75 },
                { color: "rgb(19, 206, 102)", percentage: 90 },
                { color: "rgb(19, 206, 102)", percentage: 100 },
            ],
        };
    },
    methods: {
        format(percentage) {
            return `${percentage}%`;
        },
        view_attendance(local_church) {
            window.open(
                `dashboard/attendance?local_church=${local_church}&awta_day=${window.env.awta_day}`,
                "mywindow",
                "menubar=1,resizable=1,width=1200,height=800"
            );
        },
        view_received_hg(awta_day) {
            window.open(
                `dashboard/received-hg?awta_day=${awta_day}`,
                "mywindow",
                "menubar=1,resizable=1,width=1200,height=800"
            );
        },
        getSummaries(param) {
            const { columns, data } = param;
            const sums = [];
            columns.forEach((column, index) => {
                if (index === 0 || index === 4 || index === 1) {
                    sums[index] = index === 1 ? 'Total' : '';
                    return;
                }
                
                var col = index === 2 ? 'guest' : 'member';
                const values = data.map(item => item[col]['count']);
                sums[index] = values.reduce((prev, curr) => {
                    const value = Number(curr);
                    if (!isNaN(value)) {
                        return prev + curr;
                    } else {
                        return prev;
                    }
                }, 0);
            });

            return sums;
        }
    },
};
</script>
