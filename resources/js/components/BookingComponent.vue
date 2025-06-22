<template>
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
        <div class="card">
            <div class="card-body pt-0">
                <div class="row justify-content-center">
                    <el-form-item class="check-dates" :label="`Choose the dates you would like to attend physically. Please select at least 1 day, maximum of ${max} days.`" prop="booked" required>
                        <!-- <el-tag v-if="(ruleForm.booked.length > 0)" class="bg-white border-0"><i class="el-icon-date"></i>&nbsp;&nbsp;You are booked on<span v-for="(value, index) in ruleForm.booked" :key="index"> {{ dates[value-1]['event_date'] }}&nbsp;</span></el-tag> -->
                        <el-checkbox-group v-model="ruleForm.booked" size="small">
                            <div class="row">
                                <div v-for="(date, index) in dates" :key="index" class="col-md-3 text-center">
                                    <el-badge :value="`${date.available} left!`" class="item my-3 c-booking-date" :type="date.available <= 10 ? 'danger' : (date.available <= 100 ? 'warning' : 'success')">
                                        <el-checkbox
                                            :label="date.id"
                                            name="booked"
                                            border
                                            :disabled="((!ruleForm.booked.includes(date.id) && ruleForm.booked.length === max) || (date.available === 0 && !ruleForm.booked.includes(date.id) && !initial.includes(date.id)) || hide_button)"
                                            @change="onChangeProcessed($event,date.id)">
                                            <span v-if="ruleForm.booked.includes(date.id)">&#10003;&nbsp;</span>{{ date.event_date }}
                                        </el-checkbox>
                                    </el-badge>
                                </div>
                            </div>
                        </el-checkbox-group>
                    </el-form-item>
                </div>
            </div>
        </div>

        <el-row class="mt-3" v-if="!hide_button">
            <div class="col-md-12">
                <el-button type="warning" :autofocus="true" @click="submitForm('ruleForm')">Submit</el-button>
            </div>
        </el-row>
    </el-form>
</template>

<script>
export default {
    props: {
        booked_dates: {
            required: false,
            type: Array
        },
        slots: {
            required: false,
            type: Array
        },
        self_redirect: {
            required: true,
            type: Boolean
        },
        hide_button: {
            required: false,
            type: Boolean,
            default: false
        },
        is_admin: {
            default: false,
            type: Boolean,
            required: false
        },
        registration: {
            required: true,
        },
        event: {
            required: false
        }
    },
    data () {
      return {
        dates: [],
        initial: [],
        ruleForm: {
            booked: [],
        },
        rules: {
            booked: [
                {required: true, message: 'Please select atleast one day', trigger: ['blur', 'change']},
            ],
        },
        max: 2
      }
    },
    mounted() {
        this.dates = this.slots.map(({event_date, id, available, seat_count}) => ({event_date, id, available, seat_count}) );
        this.ruleForm.booked = this.booked_dates.map(function (date) { return date.slot.id; });
        this.initial = this.booked_dates.map(function (date) { return date.slot.id; });
        this.max = this.registration.can_book_days
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
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

                    await axios.post(`/${this.event.slug}/booking/${this.registration.id}/update`, {
                        dates: this.ruleForm.booked,
                        is_admin: this.is_admin
                    })
                    .then(async (response) => {
                        loading.close()

                        this.$alert('', 'Successfully Booked!', {
                            confirmButtonText: 'OK',
                            showCancelButton: false,
                            closeOnPressEscape: false,
                            closeOnClickModal: false,
                            showClose: false,
                            center: true,
                            type: 'success',
                            callback: action => {
                                if (this.self_redirect)
                                    window.location.reload();
                                else
                                    window.location.href = `booking/${this.registration.id}`;
                            }
                        });
                    }).catch((error) => {
                        loading.close()

                        this.$alert('', error.response.data.error, {
                            confirmButtonText: 'OK',
                            showCancelButton: false,
                            closeOnPressEscape: false,
                            closeOnClickModal: false,
                            showClose: false,
                            center: true,
                            type: 'error',
                            callback: action => {
                                window.location.reload();
                            }
                        });
                    });
                });
            })
        },
        onChangeProcessed(isChecked, id) {
            var result;
            for (var i = 0, len = this.dates.length; i < len; i++) {
                if (this.dates[i]['id'] === id) {
                    this.dates[i]['available'] += isChecked ? -1 : 1
                    break;
                }
            }
        }
    }
}
</script>