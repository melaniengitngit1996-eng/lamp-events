<template>
    <div>
        <el-table
        :data="tableData.data"
        size="mini"
        border
        class="mb-3"
        style="width: 100%">
        <el-table-column>
                <template slot="header" slot-scope="scope">
                    <table class="w-100">
                        <tr style="background-color: #f5f7fa;">
                            <td width="250">
                                <small>Search by Name or LAMP ID</small>
                                <input type="hidden" name="type" value="lookup" />
                                <el-input
                                    clearable
                                    v-model="search.keyword"
                                    size="mini"
                                    name="search"
                                    placeholder="Type to search"/>
                            </td>

                            <td width="120">
                                <small>Reg Type</small>
                                <el-select size="mini" v-model="search.registration_type" clearable placeholder="select">
                                    <el-option label="All" value=""></el-option>
                                    <el-option label="Member" value="Member"></el-option>
                                    <el-option label="Guest" value="Guest"></el-option>
                                </el-select>
                            </td>

                            <td width="120">
                                <small>Local Church</small>
                                <el-select v-model="search.local_church" placeholder="select" size="mini" clearable>
                                    <el-option label="All" value=""></el-option>
                                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                                </el-select>
                            </td>
                            <td>
                                <br />
                                <el-button @click="fetchAttendances()" size="mini" type="primary">Search</el-button>
                                </td>
                            <td>
                                <br />
                                <el-popover
                                placement="top-start"
                                title="History"
                                width="350"
                                trigger="hover">
                                    <template>
                                        <p class="m-0" style="font-size: x-small;" v-for="(activity, index) in history.slice().reverse()" v-if="index < 10" :key="index">
                                            {{ activity.created_at }} - <i>exported by {{activity.user_name}}</i>
                                        </p>
                                        <p v-if="history.length > 10">...</p>
                                    </template>
                                    <a :href="`/${event.slug}/attendances/export`" class="float-end" slot="reference" @click="refreshHistory()">
                                    <el-button type="success" size="mini">Export to Excel&nbsp;<i class="el-icon-download el-icon-right"></i></el-button>
                                    </a>
                                </el-popover>
                            </td>
                        </tr>
                    </table>
                </template>
                <el-table-column
                    prop="count"
                    label="#"
                    fixed="left"
                    align="center"
                    width="50">
                    <template slot-scope="scope">
                        {{ scope.$index + tableData.from }}
                    </template>
                </el-table-column>
                <el-table-column
                    prop="slot.event_date"
                    label="Date Attended"
                    width="180"
                    sortable
                    align="center">
                </el-table-column>
                <el-table-column
                    prop="created_at"
                    label="Date & Time"
                    width="180"
                    sortable
                    align="center">
                    <template slot-scope="scope">
                    {{ $func.formatToDateTime(scope.row.created_at) }}
                    </template>
                </el-table-column>
                <el-table-column
                    prop="registration.uuid"
                    label="LAMP ID"
                    width="180"
                    align="center">
                </el-table-column>
                <el-table-column
                    prop="registration.fullname"
                    label="Complete Name"
                    sortable
                    width="250">
                </el-table-column>
                <el-table-column
                    prop="registration.registration_type"
                    label="Registration Type"
                    sortable
                    align="center">
                    <template slot-scope="scope">
                        <el-tag effect="plain" size="mini" :type="scope.row.registration.registration_type === 'Guest' ? '' : 'warning'">{{ scope.row.registration.registration_type }}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="local_church"
                    sortable
                    label="Local Church"
                    align="center">
                </el-table-column>
                <el-table-column
                    prop="notes"
                    label="Attendance Type"
                    sortable
                    align="center">
                </el-table-column>
            </el-table-column>
        </el-table>
        <pagination 
          v-if="tableData.data.length > 0"
          class="m-0"
          :pagination="tableData"
          @paginate="fetchAttendances(false)"
          :offset="4">
        </pagination>
    </div>
  </template>
  
  <script>
    export default {
        props: {
            event: {
                required: true
            }
        },
        data() {
            return {
                tableData: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1,
                    data: []
                },
                search: {
                    keyword: '',
                    registration_type: '',
                    local_church: ''
                },
                assignments: window.env.cluster_groups,
                history: []
            }
        },
        mounted() {
            this.fetchAttendances();
            this.fetchHistory();
        },
        methods: {
            fetchAttendances(ignore_page = true) {
                if ((this.search.keyword != '' || this.search.registration_type != '' || this.search.local_church != '') && ignore_page)
                this.tableData.current_page = 1;
                
                axios
                    .get(`/${this.event.slug}/attendances`, {
                        params: {
                            search: this.search,
                            page: this.tableData.current_page,
                        }
                    })
                    .then(async response => {
                        this.tableData = response.data;
                    })
                    .catch(error => {
                        console.log(this.tableData)
                        this.$notify.error({
                            title: error
                        });
                    });
            },
            fetchHistory() {
                axios
                .get(`/${this.event.slug}/export/history`, {
                    params: {
                        type: 'attendances'
                }})
                .then(response => {
                    this.history = response.data
                })
            },
            refreshHistory() {
                var root = this;

                setTimeout(function() { root.fetchHistory(); }, 2000);
            }
        }
    }
  </script>