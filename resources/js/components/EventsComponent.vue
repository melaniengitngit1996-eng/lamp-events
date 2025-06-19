<template>
  <el-table
    :data="events"
    border
    :show-header="false"
    style="width: 100%"
    size="medium">
      <el-table-column
          prop="name"
          label="Name">
          <template slot-scope="scope">
              <span class="text-dark text-uppercase">{{ scope.row.name }}</span><br />
              <small style="
                  font-size: 12px;
                  color: lightslategray;
              ">{{ scope.row.description }}</small><br v-if="scope.row.description"/>
              <el-tag v-if="!scope.row.close_registration" size="mini" type="success">Active</el-tag>
              <el-tag v-else size="mini" type="danger">Inactive</el-tag>
              <el-tag v-if="scope.row.local_church === 'General'" size="mini" effect="plain">General</el-tag>
              <el-tag v-else size="mini" effect="plain" type="warning">{{ scope.row.local_church }}</el-tag>
          </template>
      </el-table-column>
      <el-table-column
          label="Buttons">
          <template slot-scope="scope">
          <el-row class="text-center">
              <a :href="`/${scope.row.slug}/dashboard`" target="_blank"><el-button type="primary" plain size="small"><i class="el-icon-s-data mr-2"></i>&nbsp;&nbsp;Dashboard</el-button></a>
              <a :href="`/${scope.row.slug}/registration`" target="_blank"><el-button type="success" plain size="small"><i class="el-icon-edit-outline mr-2"></i>&nbsp;&nbsp;Registration</i></el-button></a>
              <a :href="`/${scope.row.slug}/attendance`" target="_blank"><el-button type="warning" plain size="small"><i class="el-icon-full-screen mr-2"></i>&nbsp;&nbsp;Attendance</i></el-button></a>
              <a :href="`/${scope.row.slug}/check-in`" target="_blank"><el-button type="info" plain size="small"><i class="el-icon-thumb mr-2"></i>&nbsp;&nbsp;Self Check-In</i></el-button></a>
          </el-row>
          </template>
      </el-table-column>
      <el-table-column
          label="Buttons"
          width="150">
          <template slot-scope="scope">
          <el-row class="text-center">
              <a :href="`/${scope.row.slug}/home`"><el-button type="primary" plain size="small"><i class="el-icon-s-tools mr-2"></i>&nbsp;&nbsp;Manage</el-button></a>
          </el-row>
          </template>
      </el-table-column>
  </el-table>
  </template>
  
  <script>
    export default {
      props: {
        events: {
            required: true
        }
      },
      data() {
        return {
        }
      }
    }
  </script>