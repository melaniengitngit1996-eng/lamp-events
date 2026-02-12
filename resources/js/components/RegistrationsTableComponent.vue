<template>
  <div class="row justify-content-center">
    <div class="col-md-24">
      <el-table
        ref="filterTable"
        class="mb-3"
        size="mini"
        :data="tableData.data"
        border
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
                  <small>Payment Status</small>
                  <el-select size="mini" v-model="search.payment_status" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option label="Unsettled" value="Unsettled"></el-option>
                    <el-option label="Partial" value="Partial"></el-option>
                    <el-option label="Paid" value="Paid"></el-option>
                  </el-select>
                </td>

                <td width="120">
                  <small>Booking Status</small>
                  <el-select size="mini" v-model="search.booking_status" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option label="Pending" value="Pending Payment"></el-option>
                    <el-option label="Cancelled" value="Cancelled"></el-option>
                    <el-option label="Confirmed" value="Confirmed"></el-option>
                  </el-select>
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
                  <small>Attending Option</small>
                  <el-select size="mini" v-model="search.attending_option" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option
                        v-for="option in event.attending_options"
                        :key="option.label"
                        :value="option.label"
                        :label="option.label"
                    ></el-option>
                  </el-select>
                </td>

                <td width="120">
                  <small>Rate Category</small>
                  <el-select size="mini" v-model="search.category" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option label="Adult" value="Adult"></el-option>
                    <el-option label="Kids" value="Kids"></el-option>
                    <el-option label="Free" value="Free"></el-option>
                  </el-select>
                </td>

                <td width="120">
                  <small>Local Church</small>
                  <el-select size="mini" v-model="search.local_church" clearable placeholder="select">
                    <el-option label="All" value=""></el-option>
                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                  </el-select>
                </td>
                <td>
                  <br />
                  <el-button @click="fetchRegistrations()" size="mini" type="primary">Search</el-button>
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
                    <a :href="`/${event.slug}/registrations/export`" slot="reference" @click="refreshHistory()" class="float-end">
                    <el-button v-if="permissions.can_export_data" type="success" size="mini">Export to Excel&nbsp;<i class="el-icon-download el-icon-right"></i></el-button>
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
            label="Personal Details"
            width="325">
            <template slot-scope="scope">
              <el-descriptions class="margin-top" :labelStyle="{'width': '120px'}" :contentStyle="{'width': '170px'}" :column="1" size="mini" border>
                <el-descriptions-item>
                  <template slot="label">
                    <i class="el-icon-user"></i>
                    Generated ID
                  </template>
                  {{ scope.row.uuid }}
                </el-descriptions-item>
                <el-descriptions-item label="Complete Name">{{ scope.row.firstname }} {{ scope.row.lastname }}</el-descriptions-item>
                <el-descriptions-item label="Facebook Name">{{ scope.row.facebook_name || '--' }}</el-descriptions-item>
                <el-descriptions-item label="Registration Type">
                  <el-tag effect="plain" size="mini" :type="scope.row.registration_type === 'Guest' ? '' : 'warning'">{{ scope.row.registration_type }}</el-tag>
                </el-descriptions-item>
                <el-descriptions-item label="Local Church">{{ scope.row.local_church }}</el-descriptions-item>
                <el-descriptions-item label="Date Received HG"><span v-if="scope.row.is_received_hg != null">{{ scope.row.is_received_hg }}</span><span v-else>--</span><span v-if="scope.row.is_received_hg != null">&nbsp;<img class="mb-1" height="13" width="15" src="/images/flame.png" /></span></el-descriptions-item>
                <el-descriptions-item label="Date Registered">{{ $func.formatToDateTime(scope.row.created_at) }}</el-descriptions-item>
              </el-descriptions>
            </template>
          </el-table-column>
          <el-table-column
            label="Other Details"      
            width="305">
            <template slot-scope="scope">
              <el-descriptions :labelStyle="{'width': '170px'}" :contentStyle="{'width': '70px'}" class="margin-top" :column="1" size="mini" border>
                <el-descriptions-item label="Category" contentClassName="text-center"><i>{{ scope.row.category }}</i></el-descriptions-item>
                <el-descriptions-item label="Attending Option" contentClassName="text-center">{{ scope.row.attending_option }}</el-descriptions-item>
                <el-descriptions-item label="Availed New LAMP ID" contentClassName="text-center"><i><span v-if="scope.row.avail_new_lamp_id == 'yes'">Yes</span><span v-else>No</span></i></el-descriptions-item>
                <el-descriptions-item label="Rate" contentClassName="text-center">{{ $func.formatAmount(scope.row.rate) }}</el-descriptions-item>
                <el-descriptions-item label="Booking Confirmation Rate" contentClassName="text-center">{{ $func.formatAmount(scope.row.can_book_rate) }}</el-descriptions-item>
                <el-descriptions-item label="Total Paid" contentClassName="text-center">{{ $func.formatAmount(scope.row.payments_sum_amount || 0) }}</el-descriptions-item>
                <el-descriptions-item label="Payment Status" contentClassName="text-center">
                  <el-tag size="mini" effect="dark" :type="scope.row.payment_status === 'Paid' ? 'success' : (scope.row.payment_status === 'Free' ? 'info' : 'warning')">{{ scope.row.payment_status }}</el-tag>
                </el-descriptions-item>
              </el-descriptions>
            </template>
          </el-table-column>
          <el-table-column
            label="Booked dates"
            align="center"
            width="305">
            <template slot-scope="scope">
              <el-alert
                  v-if="scope.row.is_booking_bypassed"
                  class="py-1 text-xs d-inline"
                  title="All Days Pass"
                  type="info"
                  :closable="false">
              </el-alert>

              <el-descriptions :labelStyle="{'width': '120px'}" v-else-if="scope.row.booked_dates.length > 0" class="margin-top" :column="1" size="mini" border>
                <el-descriptions-item label="Date Booked" contentClassName="text-center">{{ $func.formatToDateTime(scope.row.booked_date) }}</el-descriptions-item>

                <el-descriptions-item label="Booking Status" contentClassName="text-center">
                  <el-tag size="mini" :type="scope.row.booking_status === 'Confirmed' ? 'success' : (scope.row.booking_status === 'Cancelled' ? 'danger' : 'warning')">{{ scope.row.booking_status }}</el-tag>  
                </el-descriptions-item>

                <el-descriptions-item v-for="(dates, index) in scope.row.booked_dates" :key="index" :label="index === 0 ? 'Booked Dates' : ''" contentClassName="text-center">{{ dates }}</el-descriptions-item>
              </el-descriptions>

              <span v-else>--</span>
            </template>
          </el-table-column>
          <el-table-column
            label="Dates Attended"
            align="center"
            fixed="right"
            width="284">
            <template slot-scope="scope">
              <el-descriptions :labelStyle="{'width': '120px'}" v-if="scope.row.attended_dates.length > 0" class="margin-top" :column="1" size="mini" border>
                <el-descriptions-item v-for="(dates, index) in scope.row.attended_dates" :key="index" :label="index === 0 ? 'Dates Attended' : ''" contentClassName="text-center" labelClassName="text-center">{{ dates }}</el-descriptions-item>
              </el-descriptions>

              <span v-else>--</span>
            </template>
          </el-table-column>
          <el-table-column
            fixed="right"
            label="Operations"
            align="center"
            width="110">
            <template slot-scope="scope">
              <a :href="`/${event.slug}/registration/${scope.row.id}/edit${search.last_search ? `?search=${search.last_search}` : ''}`">
                <el-button type="text" size="small">View Details</el-button>
              </a>
              <el-button v-if="scope.row.email" type="text" size="small" @click="resendMail(scope.row.id)">Resend Mail</el-button>
              <el-button v-if="permissions.can_delete_delegate" class="text-danger" type="text" size="small" @click="deleteRegistration(scope.row.id)">Delete</el-button>
            </template>
          </el-table-column>
        </el-table-column>
      </el-table>

      <pagination 
          v-if="tableData.data.length > 0"
          class="m-0"
          :pagination="tableData"
          @paginate="fetchRegistrations(false)"
          :offset="4">
      </pagination>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
        event: {
            required: true
        },
    },
    data() {
      return {
        search: {
          keyword: '',
          payment_status: '',
          booking_status: '',
          registration_type: '',
          attending_option: '',
          category: '',
          local_church: '',
          last_search: ''
        },
        tableData: {
          total: 0,
          per_page: 2,
          from: 1,
          to: 0,
          current_page: 1,
          data: []
        },
        permissions: window.auth_user.permissions,
        assignments: window.env.cluster_groups,
        history: [],
      }
    },
    mounted() {
      const params = new URLSearchParams(window.location.search)
      const search = params.get('search')

      this.search.keyword = search || '';
      this.search.last_search = search || '';
      this.fetchRegistrations();
      this.fetchHistory();
    },
    methods: {
      fetchRegistrations(ignore_page = true) {
        if ((this.search.keyword != '' || this.search.payment_status != '' || this.search.booking_status != '' || this.search.registration_type != '' || this.search.attending_option != '' || this.search.category != '' || this.search.local_church != '') && ignore_page)
          this.tableData.current_page = 1;
          this.search.last_search = this.search.keyword;
          
        const loading = this.$loading({
          lock: true,
          text: 'Loading',
          background: 'rgba(0, 0, 0, 0.7)'
        });

        axios
            .get(`/${this.event.slug}/registration/all`, {
                params: {
                    search: this.search,
                    page: this.tableData.current_page,
                }
            })
            .then(async response => {
              loading.close();
              this.tableData = response.data;
            })
            .catch(error => {
                loading.close();
                this.$notify.error({
                    title: error
                });
            });
      },
      deleteRegistration(id) {
        this.$confirm(`Are you sure you want to delete this registration?`, 'Warning', {
            customClass: 'prompt-message',
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            type: 'warning'
        }).then(async () => {
          const loading = this.$loading({
              lock: true,
              text: 'Loading',
              background: 'rgba(0, 0, 0, 0.7)'
          });

          setTimeout(async () => {
            await axios.delete(`/${this.event.slug}/registration/${id}/delete`)
            .then(async (response) => {
              loading.close()
              
              this.$alert('', 'Registration Successfully Deleted!', {
                  confirmButtonText: 'OK',
                  showCancelButton: false,
                  closeOnPressEscape: false,
                  closeOnClickModal: false,
                  showClose: false,
                  center: true,
                  type: 'success',
                  callback: action => {
                      window.location.reload();
                  }
              });
            })
          }, 1000);
        })
      },
      transformDates(dates, withStatus = false, status = null) {
        var arr = typeof dates === 'object' ? dates : dates.split(", ")
        var html = "";

        arr.forEach(element => {
          // if (withStatus) {
          //   if (status === 'Confirmed')
          //     html += "<i class='el-icon-s-flag' style='color: green'></i> ";

          //   if (status === 'Pending Payment')
          //     html += "<i class='el-icon-time' style='color: orange'></i> ";

          //   if (status === 'Cancelled')
          //     html += "<i class='el-icon-s-flag' style='color: red'></i> ";
          // }

          html += element;
        });

        return html;
      },
      resendMail(id) {
        const loading = this.$loading({
            lock: true,
            text: 'Loading',
            background: 'rgba(0, 0, 0, 0.7)'
        });

        axios
            .get(`/${this.event.slug}/registration/${id}/resend-mail`)
            .then(async response => {
              loading.close()

              this.$notify.success({
                title: 'Email resent successfully.'
              });
            })
      },
      fetchHistory() {
        axios
          .get(`/${this.event.slug}/export/history`, {
            params: {
              type: 'registrations'
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