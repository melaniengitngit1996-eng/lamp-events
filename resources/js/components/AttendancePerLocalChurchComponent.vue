<template>
    <div class="row justify-content-center">
      <div class="col-md-24 px-4">
        <el-table
          ref="filterTable"
          class="mb-3"
          :data="tableData.data"
          border
          size="mini"
          style="width: 100%">
          <el-table-column>
            <template slot="header" slot-scope="scope">
              <table>
                <tr style="background-color: #f5f7fa;">
                <td width="200">
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
                  <el-select size="mini" v-model="search.local_church" placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                  </el-select>
                </td>

                <td width="120">
                  <small>Attendance Status</small>
                  <el-select size="mini" v-model="search.attendance" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option label="Present" value="Present"></el-option>
                    <el-option label="Not Yet Present" value="Not Yet Present"></el-option>
                  </el-select>
                </td>

                <td width="120">
                  <small>AWTA Day</small>
                  <el-select size="mini" v-model="search.awta_day" placeholder="select">
                    <el-option label="Day 1" value="Day 1"></el-option>
                    <el-option label="Day 2" value="Day 2"></el-option>
                    <el-option label="Day 3" value="Day 3"></el-option>
                    <el-option label="Day 4" value="Day 4"></el-option>
                  </el-select>
                </td>
                <td>
                  <br />
                  <el-button @click="fetchAttendances()" size="mini" type="primary">Search</el-button>
                </td>
                </tr>
                </table>
            </template>
            <el-table-column
              prop="count"
              label="Local Church"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                  {{ scope.row.registration.local_church }}
              </template>
            </el-table-column>
            <el-table-column
              prop="registration.uuid"
              label="LAMP ID"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                  {{ scope.row.registration.uuid }}
              </template>
            </el-table-column>
            <el-table-column
              prop="count"
              label="Delegate Name"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                  {{ scope.row.registration.fullname }}
              </template>
            </el-table-column>
            <el-table-column
              prop="count"
              label="Registration Type"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                  {{ scope.row.registration.registration_type }}
              </template>
            </el-table-column>
            <el-table-column
              prop="count"
              label="Cluster Group"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                  {{ scope.row.registration.cluster_group }}
              </template>
            </el-table-column>
            <el-table-column
              prop="attendance"
              label="Attendance"
              fixed="left"
              align="center">
              <template slot-scope="scope">
                <el-tag size="mini" :type="scope.row.attendance_status === 'Pending' ? 'warning' : 'success'">{{ scope.row.attendance_status }}</el-tag>
              </template>
            </el-table-column>
          </el-table-column>
        </el-table>
  
        <pagination 
            v-if="tableData.data.length > 0"
            class="m-0"
            :pagination="tableData"
            @paginate="fetchAttendances(true)"
            :offset="4">
        </pagination>
      </div>
    </div>
  </template>
  
  <script>
    export default {
      props: ['absents'],
      data() {
        return {
          search: {
            keyword: '',
            registration_type: '',
            local_church: '',
            attendance: '',
            awta_day: window.env.awta_day,
          },
          tableData: {
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1,
            data: []
          },
          assignments: window.env.cluster_groups,
        }
      },
      mounted() {
        this.search.local_church = (this.getUrlVars()['local_church']) ? this.getUrlVars()['local_church'] : ''
        this.search.keyword = (this.getUrlVars()['keyword']) ? this.getUrlVars()['keyword'] : ''
        this.search.registration_type = (this.getUrlVars()['registration_type']) ? this.getUrlVars()['registration_type'] : ''
        this.search.local_church = (this.getUrlVars()['local_church']) ? this.getUrlVars()['local_church'] : ''
        this.search.awta_day = (this.getUrlVars()['awta_day']) ? this.getUrlVars()['awta_day'].replace('%20', ' ') : window.env.awta_day
        this.search.attendance = (this.getUrlVars()['attendance']) ? this.getUrlVars()['attendance'].replace('%20Yet%20', ' Yet ') : ''
        this.tableData.current_page = (this.getUrlVars()['page']) ? this.getUrlVars()['page'] : 1
        this.tableData = this.absents
      },
      methods: {
        fetchAttendances(paginate = false) {
          var url = 'attendance';

          var search_str = '?page=' + (paginate ? this.tableData.current_page : 1);

          const keys = Object.keys(this.search);

          keys.map((item, index) => {
            if (this.search[item] != '') {
              search_str += search_str != '' ? '&' : '';
              search_str += item + '=' + this.search[item];
            }
          });

          url += search_str;
            
          window.location.href = url
        },
        getUrlVars() {
          var vars = {};
          var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
          function(m,key,value) {
            vars[key] = value;
          });
          return vars;
        }
      }
    }
  </script>