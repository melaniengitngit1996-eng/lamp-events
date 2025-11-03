<template>
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
        <div class="card">
            <div class="card-body pt-0">
                <div class="row justify-content-center">
                    <label class="mt-3" v-if="event.has_multiple_venues">Please choose a venue for the dates you’d like to attend in person. You may select up to {{max}} days.</label>
                    <el-form-item 
                        v-if="event.has_multiple_venues" 
                        v-for="(date, index) in dates" :key="index" 
                        :prop="'booked.' + date.id"
                        :rules="[
                            { validator: validateBooked, trigger: 'change' }
                        ]"
                        class="check-dates" 
                    >
                        <template slot="label">
                            {{date.event_date}} <el-tag size="mini" :type="date.available <= 10 ? 'danger' : (date.available <= 100 ? 'warning' : 'success')">{{date.available}} left for {{event.main_venue}}!</el-tag>
                        </template>
                        <el-select v-model="ruleForm.booked[date.id]" @change="onChangeProcessedMulti($event,date.id)" @visible-change="onSelectOpen($event, date.id)" placeholder="please select your venue" >
                            <el-option 
                                label="--">
                            </el-option>
                            <el-option 
                                v-for="(venue, e) in date.venues" 
                                :key="e" 
                                :label="venue.venue" 
                                :value="venue.venue"
                                :disabled="isMainVenueDisabled(venue.venue)">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item v-else class="check-dates" :label="`Choose the dates you would like to attend physically. Please select at least 1 day, maximum of ${max} days.`" prop="booked" required>
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
            required: true
        },
        hide_button: {
            required: false,
            type: Boolean,
            default: false
        },
        is_admin: {
            default: false,
            required: false
        },
        registration: {
            required: false,
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
        max: 2,
        previous_values: {}
      }
    },
    mounted() {
        this.ruleForm.booked = this.booked_dates.map(function (date) { return date.slot.id; });
        this.initial = this.booked_dates.map(function (date) { return date.slot.id; });
        this.max = this.registration.can_book_days
        var booked_dates = this.ruleForm.booked;

        if (this.event.has_multiple_venues) {
            this.ruleForm.booked = Object.fromEntries(this.booked_dates.map(item => [item.slot.id, item.venue]));
        }

        this.dates = this.slots.map((date) => {
            var available = date.available;
            var detail = {
                "event_date": date.event_date,
                "id": date.id,
                "available": available,
                "seat_count": date.seat_count
            };

            if (this.event.has_multiple_venues) {
                detail['venues'] = this.event.venues
            }

            return detail;
        });
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

                    console.log(this.ruleForm.booked);

                    var booked = this.ruleForm.booked;

                    if (this.event.has_multiple_venues) {
                        booked = Object.fromEntries(
                            Object.entries(this.ruleForm.booked).filter(([_, v]) => v !== null && v !== "")
                        );
                    }

                    await axios.post(`/${this.event.slug}/booking/${this.registration.id}/update`, {
                        dates: booked,
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
                                    window.location.href = `booking/${this.registration.id}/view`;
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
        },
        onChangeProcessedMulti(newValue, dateId) {
            const prev = this.previous_values[`_${dateId}`] || "";

            for (let i = 0, len = this.dates.length; i < len; i++) {
                if (this.dates[i].id === dateId) {
                    if (newValue === this.event.main_venue) {
                        // only minus if prev had some value
                        this.dates[i].available -= 1;
                    } else {
                        if (prev) {
                            this.dates[i].available += 1;
                        }
                    }
                    break;
                }
            }
        },
        onSelectOpen(open, dateId) {
            if (open) {
                // store the current value before it changes
                this.previous_values[`_${dateId}`] = this.ruleForm.booked[dateId];
            }
        },
        isMainVenueDisabled(venueName) {
            if (venueName !== this.event.main_venue) return false;

            // Count how many times this.event.main_venue is already selected
            const count = Object.values(this.ruleForm.booked)
                .filter(v => v === this.event.main_venue).length;

            return count >= this.max;
        },
        validateBooked(rule, value, callback) {
            const allEmpty = Object.values(this.ruleForm.booked).every(v => v === "");

            if (allEmpty) {
                callback(new Error("Please select the venue"));
            } else {
                callback();
            }
        },
    }
}
</script>