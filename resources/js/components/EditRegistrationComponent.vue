<template>
    <el-form
        :model="ruleForm"
        :rules="rules"
        ref="ruleForm"
        label-width="160px"
    >
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
                    <el-form-item
                        class="check-name"
                        label="First Name"
                        prop="firstName"
                        required
                    >
                        <el-input v-model="ruleForm.firstName"></el-input>
                    </el-form-item>
                </div>
                <div class="col-md-3">
                    <el-form-item
                        class="check-name"
                        label="Last Name"
                        prop="lastName"
                        required
                    >
                        <el-input v-model="ruleForm.lastName"></el-input>
                    </el-form-item>
                </div>
                <div class="col-md-6">
                    <el-form-item label="Facebook Name" prop="facebookName">
                        <el-input
                            v-model="ruleForm.facebookName"
                            placeholder="If none, kindly type in the Facebook name of your event companion"
                        ></el-input>
                    </el-form-item>
                </div>

                <div class="col-md-3">
                    <el-form-item
                        label="Local Church"
                        prop="localChurch"
                        required
                    >
                        <el-select
                            v-model="ruleForm.localChurch"
                            placeholder="Choose"
                        >
                            <el-option v-for="lc in localChurches" :key="lc" :label="lc" :value="lc"></el-option>
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
                        <el-select
                            v-model="ruleForm.country"
                            placeholder="Choose"
                        >
                            <el-option
                                v-for="country in countries"
                                v-bind:key="country"
                                :label="country"
                                :value="country"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </div>
                <div class="col-md-3">
                    <el-form-item
                        label="Rate Category"
                        prop="category"
                        required
                    >
                        <el-select
                            v-model="ruleForm.category"
                            placeholder="Choose"
                        >
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
                <div
                    v-if="ruleForm.registrationType === 'Member'"
                    class="col-md-3"
                >
                    <el-form-item
                        label="Do you have LAMP ID?"
                        prop="withAwtaCard"
                        required
                    >
                        <el-select
                            v-model="ruleForm.withAwtaCard"
                            placeholder="Choose"
                        >
                        <el-option
                            label="None, I have never been issued one."
                            value="none"
                        ></el-option>
                        <el-option
                            label="Yes, but I lost it."
                            value="lost"
                        ></el-option>
                        <el-option
                            label="Yes, but I don't have it at the moment."
                            value="mislaid"
                        ></el-option>
                        <el-option
                            label="Yes, I still have it."
                            value="yes"
                        ></el-option>
                        </el-select>
                    </el-form-item>
                </div>

                <div class="col-md-3">
                    <el-form-item
                        label="How will you attend the event?"
                        prop="attendingOption"
                        :required="ruleForm.registrationType === 'Member'"
                    >
                        <el-select
                            v-model="ruleForm.attendingOption"
                            placeholder="Choose"
                        >
                            <el-option
                                v-for="option in event.attending_options"
                                :key="option.label"
                                :value="option.label"
                                :label="option.label"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </div>
            </div>
        </el-card>

        <el-card
            v-if="permissions.can_edit_delegate_config"
            shadow="hover"
            class="mb-3"
        >
            <div class="row">
                <div class="col-md-2">
                    <el-form-item label="Days can book" required>
                        <el-input v-model="ruleForm.canBookDays"></el-input>
                    </el-form-item>
                </div>
                <div class="col-md-2">
                    <el-form-item label="Rebooking Limit" required>
                        <el-tooltip
                            content="Only the administrator can edit this"
                            placement="top"
                        >
                            <el-input
                                v-model="ruleForm.rebookingLimit"
                                disabled
                            ></el-input>
                        </el-tooltip>
                    </el-form-item>
                </div>
                <div class="col-md-2">
                    <el-form-item label="Booking Rate" required>
                        <el-input v-model="ruleForm.bookingRate"></el-input>
                    </el-form-item>
                </div>
                <div
                    class="col-md-2"
                    v-if="permissions.can_edit_delegate_config"
                >
                    <el-form-item label="Rate" required>
                        <el-input v-model="ruleForm.rate"></el-input>
                    </el-form-item>
                </div>
            </div>
        </el-card>

        <el-card class="mb-3" v-if="ruleForm.registrationType === 'Member'">
            <div class="col-md-3">
                <el-form-item label="Will avail new LAMP ID?">
                    <el-select
                        v-model="ruleForm.availNewLAMPID"
                        placeholder="Choose"
                    >
                        <el-option value="yes" label="yes"></el-option>
                        <el-option value="no" label="no"></el-option>
                    </el-select>
                </el-form-item>
            </div>
        </el-card>

        <el-card
            v-if="
                ruleForm.registrationType == 'Guest' &&
                permissions.can_edit_delegate_config
            "
            shadow="hover"
            class="mb-3"
        >
            <div class="row">
                <div class="col-md-12">
                    <el-form-item
                        label='Specify the date baptized to tag this delegate as "Visitor to Member"'
                        prop="visitorToMember"
                    >
                        <el-date-picker
                            v-model="ruleForm.visitorToMember"
                            type="date"
                            placeholder="Pick a day"
                            :picker-options="pickerOptions"
                        >
                        </el-date-picker>
                    </el-form-item>
                </div>

                <div class="col-md-12">
                    <el-form-item label="Notes">
                        <el-input
                            type="textarea"
                            v-model="ruleForm.notes"
                        ></el-input>
                    </el-form-item>
                </div>
            </div>
        </el-card>

        <el-card class="mb-3" v-if="event.custom_fields.length > 0 && hasVisibleCustomFields">
            <div class="row">
                <div class="col-md-3" v-for="field in event.custom_fields" v-if="evaluateRule(field.visibility_rule)">
                    <el-form-item
                        :prop="field.name"
                        :required="evaluateRule(field.mandatory_rule)"
                    >
                        <template slot="label">
                            {{field.label}}
                        </template>
                        <el-select
                            v-if="field.type === 'select'"
                            v-model="ruleForm[field.name]"
                            placeholder="Choose"
                        >
                            <el-option
                                v-for="field in field.options.split(',')"
                                :key="field"
                                :value="field"
                                :label="field"
                            ></el-option>
                        </el-select>

                        <el-input
                            v-if="field.type === 'text'"
                            v-model="ruleForm[field.name]"
                            :clearable="true"
                        ></el-input>
                    </el-form-item>
                </div>
            </div>
        </el-card>

        <!-- <el-card class="mb-3" v-if="event.custom_fields.length > 0">
            <div class="row">
                <div class="col-md-3" v-for="field in event.custom_fields">
                    <el-form-item
                        :label="field.label"
                        :prop="field.name"
                        required
                    >
                        <el-select
                            v-model="ruleForm[field.name]"
                            placeholder="Choose"
                        >
                            <el-option
                                v-for="field in field.options.split(',')"
                                :key="field"
                                :value="field"
                                :label="field"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </div>
            </div>
        </el-card> -->

        <el-row>
            <div class="col-md-12">
                <el-button
                    v-if="permissions.can_edit_delegate"
                    type="warning"
                    @click="submitForm('ruleForm')"
                    >Update</el-button
                >
            </div>
        </el-row>
    </el-form>
</template>

<script>
export default {
    props: {
        registration: {
            required: true,
            type: Object,
        },
        event: {
            required: false
        },
    },
    computed: {
        hasVisibleCustomFields() {
            return this.event.custom_fields.some(field =>
                this.evaluateRule(field.visibility_rule)
            );
        }
    },
    data() {
        return {
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                },
                shortcuts: [
                    {
                        text: "Today",
                        onClick(picker) {
                            picker.$emit("pick", new Date());
                        },
                    },
                    {
                        text: "Yesterday",
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24);
                            picker.$emit("pick", date);
                        },
                    },
                    {
                        text: "A week ago",
                        onClick(picker) {
                            const date = new Date();
                            date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                            picker.$emit("pick", date);
                        },
                    },
                ],
            },
            ruleForm: {
                email: "",
                firstName: "",
                lastName: "",
                facebookName: "",
                registrationType: "Member",
                localChurch: "",
                country: "Philippines",
                lampIDNumber: "",
                category: "Adult",
                attendingOption: "",
                withAwtaCard: "",
                canBookDays: 0,
                rebookingLimit: 0,
                category: "",
                canBook: false,
                bookingRate: 0,
                rate: 0,
                visitorToMember: "",
                availNewLAMPID: "",
                clusterGroup: ""
            },
            rules: {
                firstName: [
                    {
                        required: true,
                        message: "Please input First Name",
                        trigger: ["blur", "change"],
                    },
                ],
                lastName: [
                    {
                        required: true,
                        message: "Please input Last Name",
                        trigger: ["blur", "change"],
                    },
                ],
                localChurch: [
                    {
                        required: true,
                        message: "Please select Local Church",
                        trigger: ["blur", "change"],
                    },
                ],
                country: [
                    {
                        required: true,
                        message: "Please select Country",
                        trigger: ["blur", "change"],
                    },
                ],
                lampIDNumber: [
                    {
                        required: true,
                        message: "Please input LAMP ID Number",
                        trigger: ["blur", "change"],
                    },
                ],
                attendingOption: [
                    {
                        required: true,
                        message: "Please select your attending option",
                        trigger: ["blur", "change"],
                    },
                ],
                rate: [
                    {
                        required: true,
                        message: "Please input rate",
                        trigger: ["blur", "change"],
                    },
                ],
            },
            countries: this.$allCountries,
            permissions: window.auth_user.permissions,
            localChurches: Object.keys(window.env.cluster_groups),
            assignments: window.env.cluster_groups
        };
    },
    watch: {
        "ruleForm.withAwtaCard"(newData, oldData) {
            if (oldData != newData && oldData != "" && newData != "") {
                this.ruleForm.attendingOption = "";
            }
        },
        'ruleForm.localChurch'(data, old) {
            if (old) this.ruleForm.clusterGroup = "";
        },
    },
    mounted() {
        this.ruleForm = {
            email: this.registration.email,
            firstName: this.registration.firstname,
            lastName: this.registration.lastname,
            facebookName: this.registration.facebook_name,
            registrationType: this.registration.registration_type,
            localChurch: this.registration.local_church,
            country: this.registration.country,
            category: this.registration.category,
            attendingOption: this.registration.attending_option,
            withAwtaCard: this.registration.with_awta_card,
            category: this.registration.category,
            canBookDays: this.registration.can_book_days,
            rebookingLimit: this.registration.rebooking_limit,
            bookingRate: this.registration.can_book_rate,
            rate: this.registration.rate,
            visitorToMember: this.registration.visitor_to_member,
            availNewLAMPID: this.registration.lookup ? this.registration.lookup.avail_new_lamp_id : '',
            clusterGroup: this.registration.cluster_group
        };

        this.event.custom_fields.forEach(element => {
            const name = element.name;

            // Make ruleForm reactive
            this.$set(this.ruleForm, name, this.registration.custom_fields[name]);

            // Make rules reactive
            this.$set(this.rules, name, [
                {
                    required: true,
                    message: element.rule_message,
                    trigger: ["blur", "change"],
                },
            ]);
        });
    },
    methods: {
        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: "Loading",
                        background: "rgba(0, 0, 0, 0.7)",
                    });

                    setTimeout(async () => {
                        await axios
                            .post(
                                `/${this.event.slug}/registration/${this.registration.id}/update`,
                                this.ruleForm
                            )
                            .then(async (response) => {
                                loading.close();

                                this.$alert(
                                    "",
                                    "Details Successfully Updated!",
                                    {
                                        confirmButtonText: "OK",
                                        showCancelButton: false,
                                        closeOnPressEscape: false,
                                        closeOnClickModal: false,
                                        showClose: false,
                                        center: true,
                                        type: "success",
                                        callback: (action) => {
                                            window.location.reload();
                                        },
                                    }
                                );
                            });
                    }, 1000);
                } else {
                    return false;
                }
            });
        },
        evaluateRule(rule) {
            if (typeof rule === 'boolean') return rule;
            if (typeof rule === 'function') return rule();

            // If it's a string condition like "ruleForm.attendingOption === 'Online'"
            try {
                return new Function('ruleForm', `return ${rule}`)(this.ruleForm);
            } catch (e) {
                console.warn('Invalid mandatory_rule condition:', rule);
                return false;
            }
        }
    },
};
</script>
