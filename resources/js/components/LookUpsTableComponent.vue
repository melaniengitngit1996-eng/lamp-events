<template>
  <div class="row justify-content-center">
    <div class="col-md-24">
      <el-table
        ref="filterTable"
        class="mb-3"
        :data="tableData.data"
        border
        style="width: 100%"
        size="mini">
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
                  <small>Local Church</small>
                  <el-select size="mini" v-model="search.local_church" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                  </el-select>
                </td>
                <td width="120">
                  <small>Registration Status</small>
                  <el-select size="mini" v-model="search.registration_status" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option :value="1" label="Registered"></el-option>
                    <el-option :value="0" label="Not yet registered"></el-option>
                  </el-select>
                </td>
                <td width="120">
                  <br />
                  <el-button @click="fetchLookUps()" size="mini" type="primary">Search</el-button>
                </td>
                <td>
                  <br />
                  <a class="float-end" v-if="permissions.can_add_lookup_data" :href="`/${event.slug}/lookup/create`"><el-button size="mini" type="info" class="mx-1">Create New</el-button></a>
                  <el-button v-if="permissions.can_add_lookup_data" size="mini" type="success" class="float-end" @click="dialogVisible = true">Upload Excel&nbsp;<i class="el-icon-upload el-icon-right"></i></el-button>
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
          prop="lamp_id"
          label="AWTA Card #"
          fixed="left"
          align="center"
          sortable>
          <template slot-scope="scope">
              {{ scope.row.lamp_id }}
          </template>
        </el-table-column>
        <el-table-column
          prop="fullname"
          label="Complete Name"
          fixed="left"
          align="center"
          sortable>
          <template slot-scope="scope">
              {{ scope.row.fullname }}
          </template>
        </el-table-column>
        <el-table-column
          prop="email"
          label="Email Address"
          fixed="left"
          align="center"
          sortable>
          <template slot-scope="scope">
              <small>{{ scope.row.email }}</small>
          </template>
        </el-table-column>
        <el-table-column
          prop="local_church"
          label="Local Church"
          fixed="left"
          align="center"
          sortable>
          <template slot-scope="scope">
              {{ scope.row.local_church }}
          </template>
        </el-table-column>
        <el-table-column
          prop="is_registered"
          label="Registration Status"
          fixed="left"
          align="center"
          sortable>
          <template slot-scope="scope">
            <el-tag v-if="scope.row.registrations.length > 0">Registered</el-tag>
            <el-tag v-else type="danger">Not yet registered</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="count"
          label="Days can book"
          fixed="left"
          align="center">
          <template slot-scope="scope">
            {{ scope.row.can_book_days }}
          </template>
        </el-table-column>
        <el-table-column
          fixed="right"
          label="Operations"
          align="center"
          width="120">
          <template slot-scope="scope">
            <a :href="`/${event.slug}/lookup/${scope.row.lamp_id}/edit`"><el-button type="text" size="small">View Details</el-button></a>
          </template>
        </el-table-column>
      </el-table-column>
      </el-table>

      <pagination 
          v-if="tableData.data.length > 0"
          class="m-0"
          :pagination="tableData"
          @paginate="fetchLookUps(false)"
          :offset="4">
      </pagination>
    </div>

    <el-dialog
        title="Upload Look-up Data"
        :visible.sync="dialogVisible"
        width="30%">
        
        <span>
        <upload-component />
        </span>
    </el-dialog>
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
        search: '',
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
          local_church: '',
          registration_status: ''
        },
        dialogVisible: false,
        permissions: window.auth_user.permissions,
        assignments: window.env.cluster_groups,
      }
    },
    mounted() {
      this.fetchLookUps();
    },
    methods: {
        fetchLookUps(ignore_page = true) {
          if ((this.search.keyword != '' || this.search.local_church != '' || this.search.registration_type != '') && ignore_page)
            this.tableData.current_page = 1;
            
          axios
              .get(`/${this.event.slug}/lookup`, {
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
      }
}
</script>