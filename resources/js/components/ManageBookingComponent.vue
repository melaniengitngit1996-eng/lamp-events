<template>
<div>
    <!-- revisit code -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div v-if="! validated" class="row justify-content-center">
                <div class="col-md-6">
                    <el-card shadow="always" class="mb-3 p-1" :style="`border-top: 10px solid ${event.border_color}; height: 100% !important;`">
                        <div class="text-black">
                            <main class="container p-0" role="main">
                                <header class="mb-3">
                                    <h6 class="fw-bolder text-muted text-uppercase" style="line-height: inherit;">{{ event.name }} GUIDELINES</h6>
                                    <small>Please read the following guidelines carefully before rebooking.</small>
                                </header>


                                <section aria-labelledby="general">
                                    <h6 class="fw-bolder text-muted">Calamba Tent Attendance</h6>
                                    <small>
                                        <ul>
                                            <li>Members may book up to <strong>4 days of attendance</strong> (first come, first served).</li>
                                            <li>Slots available per day: <strong>1,000 for members</strong> and <strong>200 for guests</strong>.</li>
                                            <li>Guests may attend for a maximum of <strong>2 days only</strong>.</li>
                                            <li>Registration period: <strong>September 1 – November 30</strong> or until slots are filled.</li>
                                            <li>Registration fee: <strong>₱950</strong>.</li>
                                        </ul>
                                    </small>
                                </section>


                                <section aria-labelledby="satellite">
                                    <h6 class="fw-bolder text-muted">Satellite Attendance</h6>
                                    <small>
                                        <ul>
                                            <li>Attend at your <strong>local church location (Satellite)</strong>.</li>
                                            <li>All satellite locations will be open for <strong>4 days</strong>.</li>
                                            <li>Satellite registration period: <strong>November 1 – December 14, 2025</strong>.</li>
                                            <li>Satellite attendance fee: <strong>₱100</strong>.</li>
                                        </ul>
                                    </small>
                                </section>


                                <section class="mb-3" aria-labelledby="help">
                                    <h6 class="fw-bolder text-muted">Assistance</h6>
                                    <small class="note">For any booking issues or concerns, kindly reach out to your local Registrars.</small>
                                </section>


                                <footer>
                                    <small class="note"><strong>Book now — hurry while slots last!</strong></small>
                                </footer>
                            </main>
                        </div>
                    </el-card>
                </div>
                <div class="col-md-6">
                    <el-card shadow="always" class="mb-3 pb-0" :style="`border-top: 10px solid ${event.border_color}`">
                        <h3>Manage Booking</h3>
                        <p class="mt-2 c-booking-subheader">Type in your details to manage your booking</p>

                        <div class="px-2 row">
                            <el-alert
                                v-if="disabled"
                                title="Members' booking & rebooking is already closed. For other concerns, please reach out to your local Registrars."
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

                    <div class="row" :style="`--theme-color: ${themeColor}`">
                        <div class="col-md-12">
                            <el-button :loading="isLoading" :autofocus="true" type="theme" class="el-button--theme" @click="validateDelegate('ruleForm')" :disabled="disabled">Continue</el-button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="row justify-content-center">
                <div class="col-md-12">
                    <el-tabs v-if="(retrieved.details.bookings.length > 0)" type="border-card" class="p-0">
                        <el-tab-pane label="Booking">
                            <el-alert
                                v-if="(retrieved.details.rebooking_limit === 0)"
                                class="mb-3"
                                title="You already reached your rebooking limit. Delegates can only rebook 3x."
                                type="warning"
                                :closable="false">
                            </el-alert>
                            <booking :booked_dates="retrieved.details.booking_status === 'Cancelled' ? [] : retrieved.details.bookings" :slots="retrieved.slots" :uuid="retrieved.uuid" :can_book_days="retrieved.can_book_days" :self_redirect="false" :hide_button="retrieved.details.rebooking_limit === 0" :registration="retrieved.registration" :event="event" />
                        </el-tab-pane>
                        <el-tab-pane label="Ticket">
                            <el-alert
                                class="mb-3"
                                :title="`Congratulations! You are already booked for the ${event.name}.`"
                                type="success"
                                description="Please do screenshot this ticket if your LAMP ID is lost, this will be your gate pass to the event place."
                                :closable="false"
                                show-icon>
                            </el-alert>
                            <ticket-component :registrations="[retrieved.details]" :isRebooking="true" :event="event"/>
                        </el-tab-pane>
                    </el-tabs>
                    <booking v-else :booked_dates="retrieved.details.booking_status === 'Cancelled' ? [] : retrieved.details.bookings" :slots="retrieved.slots" :uuid="retrieved.uuid" :can_book_days="retrieved.can_book_days" :self_redirect="false" :hide_button="retrieved.details.rebooking_limit === 0" :registration="retrieved.registration" :event="event" />
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
                    :timestamp="activity.timestamp" v-html="activity.message">
                </el-timeline-item>
            </el-timeline>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        event: {
            required: false
        }
    },
    data () {
      return {
        themeColor: this.event.border_color,
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
            can_book_days: null,
            registration: {}
        },
        assignments: window.env.cluster_groups,
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

                    axios.get(`/${this.event.slug}/booking/validate`, { params: this.ruleForm })
                    .then(async (response) => {
                        loading.close()

                        this.validated = true

                        var data = response.data

                        this.retrieved = {
                            slots: data.slots,
                            uuid: data.delegate.uuid,
                            details: data.delegate,
                            can_book_days: data.delegate.can_book_days,
                            registration: data.delegate
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

<style scoped>
.el-button--theme {
    color: #FFF !important;
    background-color: var(--theme-color) !important;
    border-color: var(--theme-color) !important;
}

.el-link.el-link--theme:hover {
    color: var(--theme-color) !important;
}

.el-link.el-link--theme {
    color: var(--theme-color) !important;
    text-decoration: none !important;
}
</style>