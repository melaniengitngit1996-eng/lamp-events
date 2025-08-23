<template>
    <div class="row justify-content-center">
       <div class="col-md-12">
           <el-form :model="ruleForm" ref="ruleForm" label-position="right" class="demo-ruleForm">
                <el-card shadow="always" class="mb-3">
                    <div class="row justify-content-center">
                        <el-form-item 
                            v-if="event.has_multiple_venues" v-for="(date, index) in dates" :key="index" 
                            :label="date.event_date"
                            :prop="'booked.' + date.id"
                            :rules="[
                                { required: true, message: `Please select a venue`, trigger: 'change' }
                            ]"
                            required
                        >
                            <el-select v-model="ruleForm.booked[date.id]" placeholder="please select your venue" >
                                <el-option 
                                    v-for="(venue, e) in date.venues" 
                                    :key="e" 
                                    :label="venue.venue" 
                                    :value="venue.venue"
                                    :disabled="isMainVenueDisabled(venue.venue)">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item 
                            v-else 
                            class="check-dates" 
                            :label="`Choose the dates you would like to attend physically. Please select at least 1 day, maximum of ${max} days.`" 
                            :prop="'booked'"
                            :rules="[
                                { required: true, message: `Please select atleast one day`, trigger: ['blur', 'change'] }
                            ]"
                            required>
                            <el-checkbox-group v-model="ruleForm.booked" size="small">
                                <div class="row">
                                    <div v-for="(date, index) in dates" :key="index" class="col-md-3 text-center">
                                        <el-badge :value="`${date.available} left!`" class="item my-3 c-booking-date" :type="date.available <= 10 ? 'danger' : (date.available <= 100 ? 'warning' : 'success')">
                                            <el-checkbox
                                                :label="date.id"
                                                name="booked"
                                                border
                                                :disabled="(!ruleForm.booked.includes(date.id) && ruleForm.booked.length === max) || (date.available === 0 && !ruleForm.booked.includes(date.id))"
                                                @change="onChangeProcessed($event,date.id)">
                                                <span v-if="ruleForm.booked.includes(date.id)">&#10003;&nbsp;</span>{{ date.event_date }}
                                            </el-checkbox>
                                        </el-badge>
                                    </div>
                                </div>
                            </el-checkbox-group>
                        </el-form-item>
                    </div>
                </el-card>
           </el-form>
       </div>
   </div>
   </template>
   
   <script>
   export default {
        props: {
            data: {
                required: true,
                type: Object
            },
            slots: {
                required: false
            },
            event: {
                required: true
            },
        },
        data() {
           return {
                dates: [],
                max: 0,
                ruleForm: {
                    booked: []
                }
           }
        },
        mounted() {
            if (Object.keys(this.data.step_3).length != 0) {
                this.ruleForm.booked = this.data.step_3.booked.map(function (date) { return date; });
            }

            var booked_dates = this.ruleForm.booked;

            if (this.event.has_multiple_venues) {
                this.ruleForm.booked = {};
            }

            this.dates = this.slots.member.map((date) => {

                var available = booked_dates.includes(date.id) ? date.available-1 : date.available;
                var detail = {
                    "event_date": date.event_date,
                    "id": date.id,
                    "available": available,
                    "seat_count": date.seat_count
                };

                if (this.event.has_multiple_venues) {
                    detail['venues'] = this.event.venues

                    this.$set(this.ruleForm.booked, date.id, '');
                }

                return detail;
            });

            if (this.data.step_1.withAwtaCard === 'none') 
                this.max = this.data.step_1.canBookDays
            if (['lost', 'mislaid'].includes(this.data.step_1.withAwtaCard))
                this.max = this.data.step_2.canBookDays
            if (this.data.step_1.withAwtaCard === 'yes')
                this.max = this.data.step_1.found.canBookDays
        },
        methods: {
            submitForm(action) {
                if (action == 'back') {
                    if (Object.keys(this.data.step_1.found).length === 0)
                        this.$emit('change-step', {destination: 'step_2', current: 'step_3', data: this.ruleForm});
                    else
                        this.$emit('change-step', {destination: 'step_1', current: 'step_3', data: this.ruleForm});

                    return false;
                }

                this.$refs['ruleForm'].validate((valid) => {
                    if (valid) {
                        this.$emit('submit', this.ruleForm);
                        console.log('submiting...');
                    } else {
                       console.log('error submit!!');
                       return false;
                    }
                });
            },
            selectBookedDates(dates) {
                this.ruleForm.booked = dates;
            },
            onChangeProcessed(isChecked, id) {
                for (var i = 0, len = this.dates.length; i < len; i++) {
                    if (this.dates[i]['id'] === id) {
                        this.dates[i]['available'] += isChecked ? -1 : 1
                        break;
                    }
                }
            },
            isMainVenueDisabled(venueName) {
                if (venueName !== this.event.main_venue) return false;

                // Count how many times this.event.main_venue is already selected
                const count = Object.values(this.ruleForm.booked)
                    .filter(v => v === this.event.main_venue).length;

                return count >= this.max;
            }
        }
   }
   </script>