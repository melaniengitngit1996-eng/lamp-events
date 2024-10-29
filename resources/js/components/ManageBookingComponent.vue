<template>
<div>
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div v-if="! validated" class="row justify-content-center">
                <div class="col-md-6 mb-3">
                    <el-card shadow="always" class="mb-3 p-1" style="border-top: 10px solid rgb(45 122 95); height: 100% !important;">
                        <div class="text-black">
                            <h6 class="fw-bolder text-muted">LAMP WORLDWIDE AWTA {{ year }}</h6>
                            <small>
                            Timeline: {{ event_date }}<br/>
                            Venue: Calamba Tent<br/>
                            Theme: {{ theme }}<br/>
                            <br/>
                            </small>

                            <h6 class="fw-bolder text-muted">GUIDELINES: </h6>
                            <small>
                            Both members and visitors will be able to start booking their seats on October 1 until {{rebooking_deadline}} for Hybrid Attendees.<br/><br/>

                            Hybrid Attendees should book for intended AWTA days only. Visitors will need to coordinate with their cluster local coordinators for their bookings.<br/><br/>

                            Rebooking is until {{rebooking_deadline}} only. <br/><br/>
                            For any booking issues/concerns, kindly reach out to your local AWTA Registrars.<br/><br/>

                            Book now — hurry while seats last!
                            </small>
                        </div>
                    </el-card>
                </div>
                <div class="col-md-6">
                    <el-card shadow="always" class="mb-3 pb-0" style="border-top: 10px solid rgb(45 122 95)">
                        <h3>Manage Booking</h3>
                        <p class="mt-2 c-booking-subheader">Type in your details to manage your booking</p>

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
            <div v-else class="row justify-content-center">
                <div class="col-md-12">
                    <el-tabs v-if="(retrieved.details.bookings.length > 0)" type="border-card" class="p-0">
                        <el-tab-pane label="Ticket">
                            <el-alert
                                class="mb-3"
                                :title="`Congratulations! You are already booked for the AWTA ${year}.`"
                                type="success"
                                description="Please do screenshot this ticket if your AWTA card is lost, this will be your gate pass to the event place."
                                :closable="false"
                                show-icon>
                            </el-alert>
                            <ticket-component :registrations="[retrieved.details]" :isRebooking="true"/>
                        </el-tab-pane>
                        <el-tab-pane label="Booking">
                            <el-alert
                                v-if="(retrieved.details.rebooking_limit === 0)"
                                class="mb-3"
                                title="You already reached your rebooking limit. Delegates can only rebook 3x."
                                type="warning"
                                :closable="false">
                            </el-alert>
                            <booking :booked_dates="retrieved.details.booking_status === 'Cancelled' ? [] : retrieved.details.bookings" :slots="retrieved.slots" :uuid="retrieved.uuid" :can_book_days="retrieved.can_book_days" :self_redirect="false" :hide_button="retrieved.details.rebooking_limit === 0"/>
                        </el-tab-pane>
                    </el-tabs>
                    <booking v-else :booked_dates="retrieved.details.booking_status === 'Cancelled' ? [] : retrieved.details.bookings" :slots="retrieved.slots" :uuid="retrieved.uuid" :can_book_days="retrieved.can_book_days" :self_redirect="false" :hide_button="retrieved.details.rebooking_limit === 0"/>
                </div>
            </div>
        </div>
    </div>

    <div v-if="Object.keys(retrieved.details).length > 0" class="row justify-content-center mb-5">
        <div class="col-md-6">
            <label v-if="retrieved.details.booking_activities.length > 0" class="mb-3 text-secondary">Activity</label>
            <el-timeline v-if="retrieved.details.booking_activities.length > 0">
                <el-timeline-item
                    v-for="(activity, index) in retrieved.details.booking_activities"
                    :key="index"
                    type="default"
                    size="large"
                    :timestamp="activity.timestamp">
                    {{ activity.message }}
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
</div>
</template>

<script>
export default {
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
        event_date: window.env.event_date,
        assignments: window.env.cluster_groups,
        theme: window.env.theme,
        rebooking_deadline: window.env.rebooking_deadline,
        year: window.env.year
      }
    },
    mounted() {
        document.getElementsByClassName('transform-uppercase')[0].getElementsByClassName('el-form-item__content')[0].getElementsByClassName('el-input')[0].getElementsByTagName('input')[0].style = 'text-transform: uppercase !important';
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

                    axios.get("/booking/validate", { params: this.ruleForm })
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
        }
    }
}
</script>

<style>

</style>