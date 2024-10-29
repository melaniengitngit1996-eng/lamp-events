<template>
<div>
    <div v-if="!validated" class="row justify-content-center">
        <div class="col-md-3">
            <img width="100%" class="mb-3 rounded shadow" src="/images/2024_banner_B.jpeg">
        </div>
    </div>

    <div v-if="validated" class="row justify-content-center mb-4">
        <div class="col-md-4">
            <h3>My Bookings</h3>
            <el-alert
                class="mb-3"
                :title="`Hey ${retrieved.details.firstname}! 👋🏻`"
                type="warning"
                description="Please select the date you like to check-in.">
            </el-alert>
            <el-card shadow="always" class="mb-3 pb-0" :style="!date.is_happening && date.attendance_status === 'Pending' ? 'opacity: 0.5;' : ''" v-for="date in retrieved.details.bookings" :key="date.id">
                <div class="clearfix">
                    <span><i class="el-icon-date"></i>&nbsp;&nbsp;{{ date.slot.event_date }}</span>
                    <el-button v-if="date.status === 'Confirmed' && date.attendance_status === 'Pending' && date.is_happening == true" style="float: right;" @click="check_in(date.id)" size="mini" type="success">Check in!</el-button>
                    <el-button v-else-if="date.attendance_status != 'Pending'" style="float: right;" size="mini" type="primary" plain @click="viewPass(date.attendance.id)">View Pass</el-button>
                </div>
            </el-card>
        </div>
    </div>

    <div v-else class="row justify-content-center mb-4">
        <div class="col-md-3">
            <el-card shadow="always" class="mb-3 pb-0" style="border-top: 10px solid rgb(45 122 95)">
                <h3>Online Check In</h3>
                <p class="mt-2 c-booking-subheader">Type in your details to check in.</p>

                <div class="px-2 row">
                    <el-alert
                        v-if="disabled"
                        title="Members' booking & rebooking is already closed. For other concerns, please reach out to your local AWTA Registrars."
                        type="warning"
                        :closable="false"
                        show-icon>
                    </el-alert>
                </div>

                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
                    <div class="row mb-1">
                        <div class="col-md-12">
                            <el-form-item label="Last Name" prop="lastName" required :error="fieldErrors">
                                <el-input :disabled="disabled" v-model="ruleForm.lastName"></el-input>
                            </el-form-item>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12">
                            <el-form-item label="Local Church" prop="localChurch" required :error="fieldErrors">
                                <el-select :disabled="disabled" v-model="ruleForm.localChurch" placeholder="Choose">
                                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                                </el-select>
                            </el-form-item>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12">
                            <el-form-item class="transform-uppercase" label="LAMP ID / Guest Number" prop="referenceNumber" required :error="fieldErrors">
                                <el-input :disabled="disabled" v-model="ruleForm.referenceNumber"></el-input>
                            </el-form-item>
                        </div>
                    </div>

                    <div v-if="error" class="row">
                        <div class="col-md-12">
                            <div style="color: #F56C6C;font-size: 12px;">{{ error }}</div>
                        </div>
                    </div>
                </el-form>
            </el-card>

            <div class="row">
                <div class="col-md-12">
                    <el-button :loading="isLoading" :autofocus="true" type="theme" @click="validateDelegate('ruleForm')" :disabled="disabled">Continue</el-button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        loc: {
            type: String,
            default: false,
        }
    },
    data () {
      return {
        ruleForm: {
            'lastName': '',
            'localChurch': '',
            'referenceNumber': ''
        },
        disabled: false,
        rules: {
            lastName: [
                { required: true, message: 'Please input Last Name', trigger: ['blur', 'change']}
            ],
            localChurch: [
                { required: true, message: 'Please select Local Church', trigger: 'change'}
            ],
            referenceNumber: [
                { required: true, message: 'Please input Reference Number', trigger: ['blur', 'change']},
            ],
        },
        validated: false,
        error: null,
        isLoading: false,
        fieldErrors: null,
        retrieved: {
            slots: [],
            uuid: null,
            details: {},
            can_book_days: null
        },
        assignments: window.env.cluster_groups,
      }
    },
    methods: {
        validateDelegate(formName) {
            this.validated = false;

            this.retrieved = {
                slots: [],
                uuid: null,
                details: {}
            }

            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });

                    this.fieldErrors, this.error = null

                    axios.get("/check-in/validate", { params: this.ruleForm })
                    .then(async (response) => {
                        loading.close()

                        this.validated = true

                        var data = response.data

                        this.retrieved = {
                            slots: data.slots,
                            uuid: data.delegate.uuid,
                            details: data.delegate,
                            can_book_days: data.delegate.can_book_days
                        }
                    })
                    .catch((error) => {
                        loading.close()
                        this.error = new Error(error.response.data.error)
                        this.fieldErrors = '    '
                });;
                }
            })
        },
        check_in(id) {
            this.$confirm(`Are you sure you want to continue?`, 'Warning', {
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

                await axios.post(`/check-in/${this.retrieved.details.uuid}`, {
                    booking: id,
                    loc: this.loc
                })
                .then(async (response) => {
                    var data = response.data[response.data.length-1];
                    window.location.href = `check-in/passes?id=${data.id}`;
                });
            });
        },
        viewPass(id) {
            window.open(
                `check-in/passes?id=${id}`,
                "mywindow",
                "menubar=1,resizable=1,width=800,height=800"
            );
        }
    }
}
</script>