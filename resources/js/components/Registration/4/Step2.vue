<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
                <!-- display bulk registration if registrant is a guest attending physical -->
                <el-card v-if="data.step_1.registrationType === 'Guest'" shadow="always" class="mb-3">
                    <el-form-item prop="guests" class="mb-0" required>
                        <template slot-scope="label">
                            <label class="el-form-item__label"><span class="text-danger">*</span> Please Input the Guest Details. <br/><small class="text-sm">To add more guests, click add row in the bottom right (for a maximum of {{maxBulk}} guests per registration).</small></label>
                        </template>
                    </el-form-item>
                    
                    <div class="w-full" :class="{'mb-4' : i < ruleForm.guests.length-1 || ruleForm.guests.length === 1}" v-for="(guest, i) in ruleForm.guests" :key="i">
                        <div class="border rounded-1 p-2 w-full mb-1">
                            <table class="w-full" style="width: 100%;">
                                <tr>
                                    <td width="33.3%" class="p-1">
                                        <label class="text-sm">First Name</label>
                                        <el-input
                                            size="mini"
                                            placeholder="First Name"
                                            :class="{'has-error' : (errors[i] && errors[i]['firstName']) || (errors[i] && errors[i]['invalid'])}"
                                            v-model="guest.firstName">
                                        </el-input>
                                        <small v-if="errors[i] && (errors[i]['firstName'] || errors[i]['lastName'] || errors[i]['facebookName'])" class="text-error">
                                            <span v-if="errors[i]['firstName']">{{ errors[i]['firstName'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                    <td width="33.3%" class="p-1">
                                        <label class="text-sm">Last Name</label>
                                        <el-input
                                            size="mini"
                                            placeholder="Last Name"
                                            :class="{'has-error' : (errors[i] && errors[i]['lastName']) || (errors[i] && errors[i]['invalid'])}"
                                            v-model="guest.lastName">
                                        </el-input>
                                        <small v-if="errors[i] && (errors[i]['firstName'] || errors[i]['lastName'] || errors[i]['facebookName'])" class="text-error">
                                            <span v-if="errors[i].lastName">{{ errors[i].lastName }}</span>&nbsp;
                                        </small>
                                    </td>
                                    <td width="33.4%" class="p-1">
                                        <label class="text-sm">Facebook Name</label>
                                        <el-input
                                            size="mini"
                                            placeholder='Type "None", if no facebook.'
                                            :class="{'has-error' : (errors[i] && errors[i]['facebookName']) || (errors[i] && errors[i]['invalid'])}"
                                            v-model="guest.facebookName">
                                        </el-input>
                                        <small v-if="errors[i] && (errors[i]['firstName'] || errors[i]['lastName'] || errors[i]['facebookName'])" class="text-error">
                                            <span v-if="errors[i].facebookName">{{ errors[i].facebookName }}</span>&nbsp;
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="p-1">
                                        <label class="text-sm">Email Address</label>
                                        <el-input
                                            size="mini"
                                            placeholder="Email Address"
                                            :class="{'has-error' : (errors[i] && errors[i]['email'])}"
                                            v-model="guest.email">
                                        </el-input>
                                        <small v-if="errors[i] && (errors[i]['email'] || errors[i]['country'])" class="text-error">
                                            <span v-if="errors[i]['email']">{{ errors[i]['email'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                    <td class="p-1">
                                        <label class="text-sm">Country</label>
                                        <el-select 
                                            size="mini" 
                                            v-model="guest.country" 
                                            placeholder="Choose" 
                                            :class="{'has-error' : (errors[i] && errors[i]['country'])}">
                                            <el-option v-for="country in countries" v-bind:key="country" :label="country" :value="country"></el-option>
                                        </el-select>
                                        <small v-if="errors[i] && (errors[i]['email'] || errors[i]['country'])" class="text-error">
                                            <span v-if="errors[i]['country']">{{ errors[i]['country'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1">
                                        <label class="text-sm">Local Church</label>
                                        <el-select 
                                            :class="{'has-error' : (errors[i] && errors[i]['localChurch']) || (errors[i] && errors[i]['invalid'])}" 
                                            size="mini" 
                                            v-model="guest.localChurch" 
                                            placeholder="Local Church"
                                            @change="removeClusterGroup(i)">
                                            <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                                        </el-select>
                                        <small v-if="errors[i] && (errors[i]['localChurch'] || errors[i]['clusterGroup'])" class="text-error">
                                            <span v-if="errors[i]['localChurch']">{{ errors[i]['localChurch'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                    <td class="p-1">
                                        <label class="text-sm">Cluster Group</label>
                                        <el-select 
                                            size="mini"
                                            :class="{'has-error' : (errors[i] && errors[i]['clusterGroup'])}"
                                            v-model="guest.clusterGroup" 
                                            placeholder="Cluster Group">
                                            <el-option v-if="guest.country != ''" label="No Cluster Group" value="No Cluster"></el-option>
                                            <el-option-group
                                            v-for="group in assignments[ruleForm.guests[i].localChurch]"
                                                :key="group.label"
                                                :label="group.label">
                                                <el-option
                                                    v-for="item in group.options"
                                                    :key="item"
                                                    :label="item"
                                                    :value="item">
                                                </el-option>
                                            </el-option-group>
                                        </el-select>
                                        <small v-if="errors[i] && (errors[i]['localChurch'] || errors[i]['clusterGroup'])" class="text-error">
                                            <span v-if="errors[i]['clusterGroup']">{{ errors[i]['clusterGroup'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                </tr>
                                <tr v-if="data.step_1.attendingOption === 'Physical' && event.with_booking">
                                    <td colspan="3" class="p-1">
                                        <label class="text-sm">Select Preferred Dates</label>
                                        <el-checkbox-group v-model="guest.booked" size="mini">
                                            <el-checkbox-button 
                                                v-for="(date, index) in dates" 
                                                :label="date.id" 
                                                :key="date.id" 
                                                @change="onChangeProcessed($event, date.id)"
                                                :disabled="(!guest.booked.includes(date.id) && guest.booked.length === guest_booking_limit) || (date.available === 0 && !guest.booked.includes(date.id))">
                                                    <label class="mb-1">{{ date.event_date }}</label> <br> 
                                                    <span>{{ date.available }} left!</span>
                                            </el-checkbox-button>
                                        </el-checkbox-group>
                                        <small v-if="errors[i] && errors[i]['booked']" class="text-error">
                                            <span v-if="errors[i]['booked']">{{ errors[i]['booked'] }}</span>&nbsp;
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="p-1">
                                        <label class="text-sm">Do you need any medical assistance during the event? If YES, kindly specify below. If NO, leave it empty.</label>
                                        <el-input
                                            size="mini"
                                            placeholder="Please specify..."
                                            v-model="guest.specificMedicalAssistance">
                                        </el-input>
                                    </td>
                                </tr>
                            </table>
                            <small v-if="errors[i] && errors[i]['invalid']" class="text-error mx-1">
                                <span v-if="errors[i]['invalid']">{{ errors[i]['invalid'] }}</span>&nbsp;
                            </small>
                        </div>

                        <el-button v-if="i != 0 || ruleForm.guests.length > 1" class="float-right d-inline mt-2" size="mini" plain @click="removeRow(i)">Remove</el-button>
                        <el-button type="primary" v-if="i === ruleForm.guests.length-1 && maxBulk != i+1" class="float-right d-inline float-end mt-2 mb-3" size="mini" plain @click="addRow()">Add Row</el-button>
                    </div>
                </el-card>
                
                <!-- display if member attending online/physical & guests attending online only. -->
                <el-card v-else shadow="always" class="mb-3">
                    <div class="row">
                        <div v-if="data.step_1.withAwtaCard === 'none'" class="col-md-12">
                            <el-form-item label="Email Address" prop="email">
                                <el-input v-model="ruleForm.email"></el-input>
                            </el-form-item>
                        </div>

                        <div v-if="data.step_1.withAwtaCard === 'none'" class="col-md-6">
                            <el-form-item class="check-name" label="First Name" prop="firstName" required>
                                <el-input v-model="ruleForm.firstName"></el-input>
                            </el-form-item>
                        </div>

                        <div v-if="data.step_1.withAwtaCard === 'none' || ['lost', 'mislaid'].includes(data.step_1.withAwtaCard)" class="col-md-6">
                            <el-form-item class="check-name" label="Last Name" prop="lastName" required>
                                <el-input v-model="ruleForm.lastName"></el-input>
                            </el-form-item>
                        </div>
                        
                        <div v-if="data.step_1.withAwtaCard === 'none'" class="col-md-12">
                            <el-form-item label="Facebook Name" class="rm-margin" prop="facebookName" required>
                                <small class="text-sm">If none, kindly type in "None"</small>
                                <el-input v-model="ruleForm.facebookName" placeholder="You can type in the Facebook name of your event companion"></el-input>
                            </el-form-item>
                        </div>

                        <div v-if="data.step_1.withAwtaCard === 'none' || ['lost', 'mislaid'].includes(data.step_1.withAwtaCard)" class="col-md-6">
                            <el-form-item label="Local Church" prop="localChurch" required>
                                <el-select v-model="ruleForm.localChurch" placeholder="Choose">
                                    <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                                </el-select>
                            </el-form-item>
                        </div>

                        <div v-if="data.step_1.withAwtaCard === 'none'" class="col-md-6">
                            <el-form-item label="Country" prop="country" required>
                                <el-select v-model="ruleForm.country" placeholder="Choose">
                                    <el-option v-for="country in countries" v-bind:key="country" :label="country" :value="country"></el-option>
                                </el-select>
                            </el-form-item>
                        </div>

                        <div class="col-md-12" v-if="['lost', 'mislaid'].includes(data.step_1.withAwtaCard) && ruleForm.lookUp.length > 0">
                            <el-form-item label="Please choose your name (If your name cannot be clicked, you have already registered)" prop="selected" required>
                                <el-radio v-for="data in ruleForm.lookUp" :key="data.id" v-model="ruleForm.selected" :label="data.lamp_id" :disabled="data.is_registered === 1" @change="selectName()" border>
                                    <span>{{ data.firstname }} {{ data.lastname }}</span>
                                </el-radio>
                            </el-form-item>
                        </div>
                    </div>
                </el-card>

                <el-card v-if="(data.step_1.registrationType === 'Member' && ['lost', 'mislaid'].includes(data.step_1.withAwtaCard) && ruleForm.lookUp.length > 0) || data.step_1.withAwtaCard === 'none'" shadow="always" class="mb-3"> 
                    <div class="row">
                        <div class="col-md-12">
                            <el-form-item label="Cluster Group" class="rm-margin" prop="clusterGroup" required>
                                <small v-if="data.step_1.registrationType === 'Member' && ['lost', 'mislaid'].includes(data.step_1.withAwtaCard) && ruleForm.lookUp.length > 0" class="text-sm">Please update if this is not your your active cluster.</small>
                                <small class="text-sm" v-else>Please specify your active cluster. If none, select "No Cluster Group".</small>
                                <el-select v-model="ruleForm.clusterGroup" placeholder="Select">
                                    <el-option v-if="ruleForm.localChurch != ''" label="No Cluster Group" value="No Cluster">
                                    </el-option>
                                    <el-option-group
                                    v-for="group in assignments[ruleForm.localChurch]"
                                    :key="group.label"
                                    :label="group.label">
                                    <el-option
                                        v-for="item in group.options"
                                        :key="item"
                                        :label="item"
                                        :value="item">
                                    </el-option>
                                    </el-option-group>
                                </el-select>
                            </el-form-item>
                        </div>
                    </div>
                </el-card>

                <el-card v-if="data.step_1.registrationType === 'Member' && ['lost', 'mislaid'].includes(data.step_1.withAwtaCard) && ruleForm.lookUp.length > 0" shadow="always" class="mb-3"> 
                    <div class="row">
                        <div class="col-md-12">
                            <el-form-item label="Email Address (Optional)" class="rm-margin" prop="email">
                                <small class="text-sm">Please provide the email address where you would like to receive the confirmation email.</small>
                                <el-input v-model="ruleForm.email" :clearable="true"></el-input>
                            </el-form-item>
                        </div>
                    </div>   
                </el-card>

                <el-card v-if="((data.step_1.registrationType === 'Member' && ['lost', 'mislaid'].includes(data.step_1.withAwtaCard) && ruleForm.lookUp.length > 0) || data.step_1.withAwtaCard === 'none') && data.step_1.attendingOption === 'Physical'" shadow="always" class="mb-3"> 
                    <el-form-item label="Do you need any medical assistance during the event?" class="rm-margin">
                        <small class="text-sm">If YES, kindly specify below. If NO, leave it empty.</small>
                        <el-input v-model="ruleForm.specificMedicalAssistance" placeholder="Please specify..." :clearable="true"></el-input>
                    </el-form-item>
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
                required: false,
                type: Object
            },
            event: {
                required: true
            },
        },
        data() {
            var checkLastname = async (rule, value, callback) => {
                if (['lost', 'mislaid'].includes(this.data.step_1.withAwtaCard) && this.ruleForm.lastName != '' && this.ruleForm.localChurch != '') {
                    this.isLoading = true
                    this.tableData = []

                    if (!value) {
                        this.isLoading = false
                        return callback(new Error('Please input your Last Name'));
                    }

                    await axios.get(`/lookup/validate`, {
                        params: {
                            'lastname': this.ruleForm.lastName,
                            'localChurch': this.ruleForm.localChurch
                        }
                    })
                    .then(async (response) => {
                        this.ruleForm.lookUp = response.data
                        this.isLoading = false
                    }).catch((error) => {
                        this.isLoading = false
                        callback(new Error(error.response.data.error))
                    });
                }
            };
            var checkName = async (rule, value, callback) => {
                var fields = document.querySelectorAll(".check-name");
                for (var i = 0; i < fields.length; i++) {
                    var str = fields[i].classList.remove("is-error")
                }

                const loading = this.$loading({
                            lock: true,
                            text: 'Loading',
                            background: 'rgba(0, 0, 0, 0.7)'
                        });

                await axios.get(`/${this.event.slug}/registration/validate`, {
                    params: {
                        firstName: this.ruleForm.firstName,
                        lastName: this.ruleForm.lastName,
                        localChurch: this.ruleForm.localChurch,
                        isBulk: false
                    }
                })
                .then(async (response) => {
                    loading.close()
                }).catch((error) => {
                    loading.close()

                    var fields = document.querySelectorAll(".check-name");
                    for (var i = 0; i < fields.length; i++) {
                        var str = fields[i].classList.add("is-error")
                    }

                    callback(new Error(error.response.data.error))
                });
            };
            var checkGuests = async (rule, value, callback) => {
                this.errors = [];

                if (this.data.step_1.registrationType === 'Guest') {
                    const loading = this.$loading({
                            lock: true,
                            text: 'Loading',
                            background: 'rgba(0, 0, 0, 0.7)'
                        });

                    await axios.get(`/${this.event.slug}/registration/validate`, {
                        params: {
                            data: this.ruleForm.guests,
                            isBulk: true
                        }
                    })
                    .then(async (response) => {
                        loading.close()
                    }).catch((error) => {
                        loading.close();
                        this.errors = error.response.data.errors;
                        callback(new Error('Please check the details.'));
                    });
                }
            };
            return {
                ruleForm: {
                    email: '',
                    firstName: '',
                    lastName: '',
                    facebookName: '',
                    localChurch: '',
                    country: 'Philippines',
                    category: 'Adult',
                    lookUp: [],
                    selected: '',
                    clusterGroup: '',
                    canBookDays: parseInt(this.event.member_booking_limit || 0),
                    specificMedicalAssistance: '',
                    guests: [{
                        email: '',
                        firstName: '',
                        lastName: '',
                        facebookName: '',
                        clusterGroup: '',
                        localChurch: '',
                        country: 'Philippines',
                        category: 'Free',
                        booked: [],
                        specificMedicalAssistance: '',
                        attendingOption: this.data.step_1.attendingOption
                    }]
                },
                rules: {
                    firstName: [
                        { validator: checkName, trigger: ['submit'] },
                        { required: true, message: 'Please input First Name', trigger: ['blur', 'change']}
                    ],
                    lastName: [
                        { required: true, message: 'Please input Last Name', trigger: ['blur', 'change']},
                        { validator: checkLastname, trigger: ['blur'] },
                    ],
                    localChurch: [
                        { required: true, message: 'Please select Local Church', trigger: 'change'},
                        { validator: checkLastname, trigger: ['change'] },
                    ],
                    facebookName: [
                        { required: true, message: 'Please input Facebook Name', trigger: ['blur', 'change']},
                    ],
                    country: [
                        { required: true, message: 'Please select Country', trigger: 'change'}
                    ],
                    selected: [
                        { required: true, message: 'Please choose your Name', trigger: 'change'}
                    ],
                    guests: [
                        { validator: checkGuests, trigger: ['change'] }
                    ],
                    clusterGroup: [
                        { required: true, message: 'Please select Cluster Group', trigger: 'change'}
                    ]
                },
                countries: this.$allCountries,
                maxBulk: 10,
                errors: [],
                dates: [],
                assignments: window.env.cluster_groups,
                guest_booking_limit: parseInt(this.event.guest_booking_limit || 0)
            }
        },
        watch: {
            'ruleForm.localChurch'(data, old) {
                if (old) this.ruleForm.clusterGroup = ''
            },
            'ruleForm.lastName'(data, old) {
                if (old) {
                    this.ruleForm.lookUp = []
                    this.ruleForm.localChurch = ''
                    this.ruleForm.clusterGroup = ''
                }
            }
        },
        mounted() {
            this.$refs['ruleForm'].validate.lastName
            if (Object.keys(this.data.step_2).length != 0) {
                this.ruleForm = this.data.step_2;
            }

            var booked_dates = [];

            if (this.data.step_1.registrationType === 'Member') {
                this.dates = this.slots.member.map(function(date) {
                    var available = booked_dates.includes(date.id) ? date.available-1 : date.available;
                    return {
                        "event_date": date.event_date,
                        "id": date.id,
                        "available": available,
                        "seat_count": date.seat_count
                    };
                });
            }

            if (this.data.step_1.registrationType === 'Guest') {
                this.dates = this.slots.guest.map(function(date) {
                    var available = booked_dates.includes(date.id) ? date.available-1 : date.available;
                    return {
                        "event_date": date.event_date,
                        "id": date.id,
                        "available": available,
                        "seat_count": date.seat_count
                    };
                });
            }

            this.ruleForm.guests.forEach(element => {
                this.dates = this.dates.map(function (date) {
                    date.available = element.booked.includes(date.id) ? date.available-1 : date.available;

                    return date;
                });
            });

            if (this.event.with_booking) {
                this.ruleForm.guests[0].booked = this.slots.guest.map(item => item.id);
            }
        },
        methods: {
            submitForm(action) {
                if (action == 'back') {
                    this.$emit('change-step', {destination: 'step_1', current: 'step_2', data: this.ruleForm});
                    return false;
                }

                this.$refs['ruleForm'].validate((valid) => {
                    if (valid) {
                        if (this.data.step_1.registrationType === 'Guest' || (this.data.step_1.registrationType === 'Member' && this.data.step_1.attendingOption === "Online") || !this.event.with_booking) {
                            this.$emit('submit', this.ruleForm);
                        } else {
                            this.$emit('change-step', {destination: 'step_3', current: 'step_2', data: this.ruleForm});
                        }
                    } else {
                       console.error('error submission on step 2!');
                       return false;
                    }
                });
            },
            removeRow(index) {
                this.ruleForm.guests.splice(index,1);
            },
            addRow() {
                this.ruleForm.guests.push({
                    email: '',
                    firstName: '',
                    lastName: '',
                    facebookName: '',
                    clusterGroup: '',
                    localChurch: '',
                    country: 'Philippines',
                    category: 'Free',
                    booked: this.event.with_booking ? [] : this.slots.guest.map(item => item.id),
                    specificMedicalAssistance: '',
                    attendingOption: this.data.step_1.attendingOption
                });
            },
            removeClusterGroup(index) {
                this.ruleForm.guests[index].clusterGroup = ''
            },
            onChangeProcessed(isChecked, id) {
                for (var i = 0, len = this.dates.length; i < len; i++) {
                    if (this.dates[i]['id'] === id) {
                        this.dates[i]['available'] += isChecked ? -1 : 1
                        break;
                    }
                }
            },
            selectName() {
                var selected = this.ruleForm.lookUp.filter(function (el) {
                    return el.lamp_id === this.ruleForm.selected;
                }.bind(this));

                this.ruleForm.canBookDays = selected[0].can_book_days;
                this.ruleForm.email = selected[0].email;
                this.ruleForm.clusterGroup = selected[0].cluster_group;
            }
        }
   }
   </script>

   <style scoped>
    small {
        color: #F56C6C;
        font-size: 12px;
        line-height: 1;
        padding-top: 4px;
    }

    .text-sm {
        font-size: 12px;
        color: gray;
        margin-left: 2px;
    }
    </style>