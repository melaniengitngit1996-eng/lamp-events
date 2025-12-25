<template>
<div class="container">
  <div class="row justify-content-center">
    <div v-if="!retrieved" class="col-md-8">
      <div class="row">
        <div class="col-md-5">
          <div class="border-0 card shadow">
            <div class="card-body">
                  <div class="row justify-content-center">
                    <div class="col-md-12">
                      <p class="mb-0 text-black-50 text-center mt-2 text-xxs">Align the QR Code within the <br/>reader to scan</p>
                    </div>
                    <div class="col-md-6">
                      <center>
                      <img width="80%" class="m-3" src="/images/qr-code-scan.png">
                      </center>
                    </div>
                  </div>
                <div class="row" style="border-top: 2px dashed #dee2e6;">
                  <div class="col-md-12">
                    <p class="mb-0 text-black-50 text-center mt-2 text-xxs">Or enter your code below</p>
                    <el-input id="qrcode_value" placeholder="Your code" class="mt-2 eme" @change="submit()" v-model="input" :autofocus="true">
                      <template style="cursor: pointer" type="primary" plain slot="append" @click="submit()">Validate</template>
                    </el-input>
                    <p v-if="error" class="text-danger">{{ error }}</p>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="border-0  card shadow">
              <div class="card-body">
                  <template v-for="(item, index) in count">
                    <table class="border text-center w-full" style="width: 100%">
                      <tr class="text-uppercase" :style="`background-color: ${item.ticket_color}; color: white`">
                        <td class="border px-2 py-1" colspan="4"><b>{{ venue }} - {{ item.event_date }}</b></td>
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
                        <td class="border px-2 py-1">{{ totals.member.attended }} / {{ totals.member.total }}</td>
                        <td class="border px-2 py-1">{{ totals.guest.attended }} / {{ totals.guest.total }}</td>
                        <td class="border px-2 py-1">{{ totals.member.attended + totals.guest.attended }} / {{ totals.member.total + totals.guest.total }}</td>
                      </tr>

                      <tr style="border-top: 2px solid lightgray;">
                          <td class="border px-2 py-1">Present</td>
                          <td class="border px-2 py-1">{{ totals.member.attended }}</td>
                          <td class="border px-2 py-1">{{ totals.guest.attended }}</td>
                          <td class="border px-2 py-1">{{ totals.guest.attended + totals.member.attended }}</td>
                      </tr>
                      <tr>
                          <td class="border px-2 py-1">Not Yet Present</td>
                          <td class="border px-2 py-1">{{ totals.member.total - totals.member.attended }}</td>
                          <td class="border px-2 py-1">{{ totals.guest.total - totals.guest.attended }}</td>
                          <td class="border px-2 py-1">{{ (totals.guest.total + totals.member.total) - (totals.guest.attended + totals.member.attended) }}</td>
                      </tr>
                    </table>
                  </template>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="col-md-5">
      <div class="border-0 card shadow mb-3">
          <div class="card-body">
            <div class="row text-center">
                <div class="col-md-12">
                  <div class="row mb-3 pt-4">
                      <div class="col-md-12">
                          <h4 class="text-lg mb-0 font-bold d-block text-uppercase">{{ retrieved.delegate.firstname }} {{ retrieved.delegate.lastname }}</h4>
                      </div>

                      <div class="col-md-12">
                          <span class="text-description d-block">{{ retrieved.delegate.local_church }}, {{ retrieved.delegate.country }}</span>
                          <small class="text-uppercase font-bold d-block">{{ retrieved.delegate.registration_type }}</small>
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                    <barcode-component :uuid="retrieved.delegate.uuid" />
                </div>
                <div class="col-md-12 mb-3">
                  <small class="text-description d-block mb-2 text-uppercase">BOOKED DATES - {{ retrieved.delegate.attending_option }}</small>
                    <el-tag v-for="(date, index) in retrieved.booked_dates" :key="index" class="mx-2 mb-3">{{ date }}</el-tag>
                </div>
                <div class="col-md-12 mb-3">
                  <small class="text-description d-block mb-2 text-uppercase">BOOKED VENUE TODAY</small>
                  <h5 class="d-block mb-0 text-uppercase">{{ retrieved.booking_today.venue }}</h5>
                </div>
                <div class="col-md-12 pt-3">
                  <small class="text-description d-block mb-2">ATTENDANCE STATUS (TODAY)</small>
                  <h5 class="text-success" v-if="retrieved.attended">PRESENT</h5>
                  <h5 class="text-danger" v-else>NOT YET PRESENT</h5>
                </div>
            </div>
          </div>
      </div>

      <el-button id="btn-continue" :autofocus="true" type="primary" @click="attendance()">{{ retrieved.attended ? 'Continue' : 'MARK AS PRESENT' }}</el-button>
    </div>
  </div>


  <el-dialog
    :visible.sync="dialogVisible"
    width="350px"
    :show-close="false">
    <span slot="title">
      <!--  -->
      <h5 class="text-success text-center mt-4"> <i class="el-icon-success"></i> Attendance Confirmed!</h5>

      <label class="px-4 text-black-50 text-center">Congratulations! Your attendance is now confirmed. For your convenience, please screenshot this confirmation and present as your gate pass. </label>
    </span>
    <span>
      <div class="row">
          <div class="col-md-12" v-if="retrieved">
            <table class="border text-center w-full" style="width: 100%">
                <tr class="border">
                    <td class="px-2 py-1"><i class="el-icon-user"></i></td>
                    <td class="px-2 py-1 text-start" width="100px">Name</td>
                    <td class="px-2 py-1 text-start">{{ retrieved.delegate.firstname }} {{ retrieved.delegate.lastname }}</td>
                </tr>
                <tr class="border">
                    <td class="px-2 py-1"><i class="el-icon-date"></i></td>
                    <td class="px-2 py-1 text-start">Date</td>
                    <td class="px-2 py-1 text-start">{{ appDate }}</td>
                </tr>
                <tr class="border">
                    <td class="px-2 py-1"><i class="el-icon-place"></i></td>
                    <td class="px-2 py-1 text-start">Local Church</td>
                    <td class="px-2 py-1 text-start">{{ retrieved.delegate.local_church }}, {{ retrieved.delegate.country }}</td>
                </tr>
            </table>
          </div>
      </div>
    </span>
    <span slot="footer" class="dialog-footer">
      <el-button type="primary" id="btn-confirm" @click="reload()">OK</el-button>
    </span>
  </el-dialog>
</div>
</template>

<script>
export default {
  props: {
    count: {
        required: true,
        type: Array
    },
    event: {
        required: true
    },
    allowed: {
      required: true,
      type: String
    },
    venue: {
      required: true,
      type: String
    }
  },
  data() {
    return {
      appDate: null,
      input: '',
      loading: false,
      error: null,
      retrieved: null,
      dialogVisible: false,
      totals: {
        member: {
          total: 0,
          attended: 0
        },
        guest: {
          total: 0,
          attended: 0
        }
      }
    }
  }, 
  watch: {
    input(val) {
      if (val.length >= 9) {
        this.submit();
      }
    },
    dialogVisible(val) {
      if (val) {
        this.autoFocus();
      }
    }
  },
  mounted() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;

    this.appDate = today;

    this.count[0].count.forEach(item => {
      this.totals.member.attended = parseFloat(this.totals.member.attended) + parseFloat(item.count.member.attended)
      this.totals.member.total = parseFloat(this.totals.member.total) + parseFloat(item.count.member.total)
      this.totals.guest.attended = parseFloat(this.totals.guest.attended) + parseFloat(item.count.guest.attended)
      this.totals.guest.total = parseFloat(this.totals.guest.total) + parseFloat(item.count.guest.total)
    });
  }, 
  methods: {
    async submit() {
      this.error = null;
      this.loading = true;
      this.retrieved = null

      if (! this.input)
        this.error = 'Please enter LAMP ID/Guest number.'

      await axios.get(`/attendance/${this.event.id}/${this.input}?allowed=${this.allowed}&venue=${this.venue}`)
      .then(async (response) => {
        this.loading = false;
        this.retrieved = response.data
        setTimeout(() => document.getElementById('btn-continue').focus(), 500);
        
      }).catch((error) => {
        this.loading = false;
        this.error = error.response.data.error;
      });
    },
    async attendance() {
      if (! this.retrieved.attended) {
        await axios.post(`/attendance/${this.event.id}`, {
          details: this.retrieved.delegate
        })
        .then(async (response) => {
          this.dialogVisible = true
        });
      } else {
        this.dialogVisible = true
      }
    },
    isMobile() {
      if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        return true
      } else {
        return false
      }
    },
    reload() {
      this.retrieved = null
      this.dialogVisible = false
      this.input = ''
      setTimeout(() => document.getElementById("qrcode_value").focus(), 500);
    },
    autoFocus() {
      setTimeout(() => document.getElementById('btn-confirm').focus(), 500);
    }
  }
}
</script>