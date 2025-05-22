<template>
    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
        <el-card shadow="hover" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <el-form-item label="Email Address" prop="email">
                        <el-input v-model="ruleForm.email"></el-input>
                    </el-form-item>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <el-form-item class="check-name" label="First Name" prop="firstName" required>
                        <el-input v-model="ruleForm.firstName"></el-input>
                    </el-form-item>
                </div>
                <div class="col-md-3">
                    <el-form-item class="check-name" label="Last Name" prop="lastName" required>
                        <el-input v-model="ruleForm.lastName"></el-input>
                    </el-form-item>
                </div>
                <div class="col-md-6">
                    <el-form-item label="Facebook Name" prop="facebookName">
                        <el-input v-model="ruleForm.facebookName" placeholder="If none, kindly type in the Facebook name of your event companion"></el-input>
                    </el-form-item>
                </div>

                <div class="col-md-3">
                    <el-form-item label="Local Church" prop="localChurch" required>
                        <el-select v-model="ruleForm.localChurch" placeholder="Choose">
                            <el-option v-for="(value, local_church) in assignments" :key="local_church" :label="local_church" :value="local_church"></el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="col-md-3">
                    <el-form-item label="Cluster Group" prop="localChurch" required>
                        <el-select 
                            v-model="ruleForm.clusterGroup" 
                            placeholder="Cluster Group">
                            <el-option label="No Cluster Group" value="No Cluster"></el-option>
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
                <div class="col-md-3">
                    <el-form-item label="Country" prop="country" required>
                        <el-select v-model="ruleForm.country" placeholder="Choose">
                            <el-option v-for="country in countries" v-bind:key="country" :label="country" :value="country"></el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="col-md-3">
                    <el-form-item label="Rate Category" prop="category" required>
                        <el-select v-model="ruleForm.category" placeholder="Choose">
                            <el-option label="Adult" value="Adult"></el-option>
                            <el-option label="Kids" value="Kids"></el-option>
                            <el-option label="Free" value="Free"></el-option>
                        </el-select>
                    </el-form-item>
                </div>
            </div>
        </el-card> 

        <el-card shadow="hover" class="mb-3">
            <div class="row">
                <div class="col-md-2">
                    <el-form-item label="Days can book" required>
                        <el-input v-model="ruleForm.canBookDays"></el-input>
                    </el-form-item>
                </div>
            </div>
        </el-card>

        <el-row>
            <div class="col-md-12">
                <el-button v-if="permissions.can_edit_lookup_data" type="warning" @click="submitForm('ruleForm')">Update</el-button>
            </div>
        </el-row>
    </el-form>
</template>

<script>
export default {
    props: {
        lookup: {
            required: true,
            type: Object
        },
        event: {
            required: true
        }
    },
    data() {
        return {
            ruleForm: {
                email: '',
                firstName: '',
                lastName: '',
                facebookName: '',
                registrationType: 'Member',
                localChurch: '',
                country: 'Philippines',
                lampIDNumber: '',
                category: 'Adult',
                canBookDays: parseInt(this.event.member_booking_limit || 0),
                clusterGroup: ''
            },
            rules: {
                firstName: [
                    { required: true, message: 'Please input First Name', trigger: ['blur', 'change']}
                ],
                lastName: [
                    { required: true, message: 'Please input Last Name', trigger: ['blur', 'change']}
                ],
                localChurch: [
                    { required: true, message: 'Please select Local Church', trigger: ['blur', 'change']}
                ],
                country: [
                    { required: true, message: 'Please select Country', trigger: ['blur', 'change']}
                ],
                lampIDNumber: [
                    { required: true, message: 'Please input AWTA Card Number', trigger: ['blur', 'change']},
                ],
                category: [
                    { required: true, message: 'Please select rate', trigger: ['blur', 'change']},
                ],
                canBookDays: [
                    { required: true, message: 'Please input number of days can book', trigger: ['blur', 'change']},
                ]
            },
            countries: this.$allCountries,
            permissions: window.auth_user.permissions,
            assignments: window.env.cluster_groups,
        }
    },
    mounted() {
        this.ruleForm = {
            email: this.lookup.email,
            firstName: this.lookup.firstname,
            lastName: this.lookup.lastname,
            facebookName: this.lookup.facebook_name,
            registrationType: this.lookup.registration_type,
            localChurch: this.lookup.local_church,
            country: this.lookup.country,
            lampIDNumber: this.lookup.lamp_id,
            category: this.lookup.category,
            canBookDays: this.lookup.can_book_days,
            clusterGroup: this.lookup.cluster_group
        }
    },
    watch: {
        'ruleForm.localChurch'(data, old) {
            if (old) this.ruleForm.clusterGroup = ''
        },
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });

                    setTimeout(async () => {
                        await axios.post(`/lookup/${this.lookup.lamp_id}/update`, this.ruleForm)
                        .then(async (response) => {
                            loading.close()

                            this.$alert('', 'Details Successfully Updated!', {
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
                        });
                    }, 1000);
                } else {
                    return false;
                }
            });
        }
    }
}
</script>
