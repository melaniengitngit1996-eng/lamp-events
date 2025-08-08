<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <el-form
                :model="ruleForm"
                :rules="rules"
                ref="ruleForm"
                label-width="120px"
                class="demo-ruleForm"
            >
                <el-card shadow="always" class="mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <el-form-item
                                label="Are you a guest or a member?"
                                prop="registrationType"
                                required
                            >
                                <el-select
                                    v-model="ruleForm.registrationType"
                                    placeholder="Choose"
                                >
                                    <el-option
                                        label="Member"
                                        value="Member"
                                        :disabled="closeRegForMember"
                                    ></el-option>
                                    <el-option
                                        label="Guest"
                                        value="Guest"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </div>
                        <div
                            v-if="ruleForm.registrationType === 'Member'"
                            class="col-md-6"
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
                    </div>
                </el-card>

                <el-card
                    v-if="ruleForm.registrationType != '' && event.show_attending_option"
                    shadow="always"
                    class="mb-3"
                >
                    <div class="px-2 row">
                        <el-alert
                            :title="`All registration after ${event.hybrid_registration_deadline} is considered online. For further inquiries, please reach out to your local Registrars.`"
                            type="warning"
                            show-icon
                        >
                        </el-alert>
                    </div>
                    <div class="row">
                        <div v-if="event.show_attending_option" class="col-md-6">
                            <el-form-item
                                label="How will you attend the AWTA?"
                                prop="attendingOption"
                                required
                            >
                                <el-select
                                    v-model="ruleForm.attendingOption"
                                    placeholder="Choose"
                                >
                                    <el-option
                                        value="Physical"
                                        label="Physical"
                                    ></el-option>
                                    <el-option
                                        value="Online"
                                        label="Online"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </div>
                        <div
                            class="col-md-6"
                            v-if="
                                ruleForm.attendingOption != 'Online' &&
                                ruleForm.registrationType === 'Guest' &&
                                event.with_guest_booking_code &&
                                event.with_booking
                            "
                        >
                            <el-form-item
                                class="transform-uppercase"
                                label="Booking Code"
                                prop="bookingCode"
                                :required="
                                    ruleForm.attendingOption != 'Online' &&
                                    ruleForm.registrationType === 'Guest' &&
                                    event.with_booking
                                "
                            >
                                <el-input
                                    v-model="ruleForm.bookingCode"
                                    :clearable="true"
                                ></el-input>
                            </el-form-item>
                        </div>
                    </div>
                </el-card>

                <el-card
                    v-if="event.custom_fields.length > 0"
                    shadow="always"
                    class="mb-3"
                >
                    <div class="row">
                        <div v-for="field in event.custom_fields" class="col-md-6">
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
                </el-card>

                <el-card
                    v-if="
                        ruleForm.registrationType === 'Member' &&
                        (ruleForm.withAwtaCard === 'yes' || ruleForm.withAwtaCard === 'old')
                    "
                    shadow="always"
                    class="mb-3"
                >
                    <div class="row">
                        <div
                            class="col-md-6"
                            v-if="(ruleForm.withAwtaCard === 'yes' || ruleForm.withAwtaCard === 'old')"
                        >
                            <el-form-item
                                class="transform-uppercase"
                                label="What is your LAMP ID number?"
                                prop="lampIDNumber"
                                :required="(ruleForm.withAwtaCard === 'yes' || ruleForm.withAwtaCard === 'old')"
                            >
                                <el-input
                                    v-model="ruleForm.lampIDNumber"
                                    @clear="resetData('awta-card')"
                                    :clearable="true"
                                ></el-input>
                            </el-form-item>
                        </div>
                    </div>
                </el-card>

                <el-card
                    v-if="
                        ruleForm.registrationType === 'Member' &&
                        ruleForm.withAwtaCard === 'yes'
                    "
                    shadow="always"
                    class="mb-3"
                >
                    <div class="row">
                        <el-form-item
                            label="Cluster Group"
                            prop="clusterGroup"
                            class="rm-margin"
                            required
                        >
                            <small class="text-sm"
                                >Please update if this is not your your active cluster.</small
                            >
                            <el-select
                                v-model="ruleForm.clusterGroup"
                                placeholder="Select"
                            >
                                <el-option
                                    v-if="options.length > 0"
                                    label="No Cluster Group"
                                    value="No Cluster"
                                >
                                </el-option>
                                <el-option-group
                                    v-for="group in options"
                                    :key="group.label"
                                    :label="group.label"
                                >
                                    <el-option
                                        v-for="item in group.options"
                                        :key="item"
                                        :label="item"
                                        :value="item"
                                    >
                                    </el-option>
                                </el-option-group>
                            </el-select>
                        </el-form-item>
                    </div>
                </el-card>

                <el-card
                    v-if="
                        ruleForm.registrationType === 'Member' &&
                        ruleForm.withAwtaCard === 'yes'
                    "
                    shadow="always"
                    class="mb-3"
                >
                    <div class="row">
                        <div
                            class="col-md-12"
                            v-if="ruleForm.withAwtaCard === 'yes'"
                        >
                            <el-form-item
                                label="Email Address (Optional)"
                                class="rm-margin"
                                prop="email"
                            >
                                <small class="text-sm"
                                    >Please provide the email address where you
                                    would like to receive the confirmation
                                    email.</small
                                >
                                <el-input
                                    v-model="ruleForm.email"
                                    :clearable="true"
                                ></el-input>
                            </el-form-item>
                        </div>
                    </div>
                </el-card>

                <el-card
                    v-if="
                        ruleForm.registrationType === 'Member' &&
                        ruleForm.withAwtaCard === 'yes'
                    "
                    shadow="always"
                    class="mb-3"
                >
                    <el-form-item
                        label="Do you need any medical assistance during the event?"
                        class="rm-margin"
                    >
                        <small class="text-sm"
                            >If YES, kindly specify below. If NO, leave it empty.</small
                        >
                        <el-input
                            v-model="ruleForm.specificMedicalAssistance"
                            placeholder="Please specify..."
                            :clearable="true"
                        ></el-input>
                    </el-form-item>
                </el-card>
            </el-form>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        closeRegForMember: {
            required: true,
            type: Boolean,
        },
        data: {
            required: true,
            type: Object,
        },
        event: {
            required: true
        },
    },
    data() {
        var checklampIDNumber = async (rule, value, callback) => {
            if (value.length === 9) {
                if (value.length > 9)
                    return callback(new Error("Invalid LAMP ID Number"));

                if (!value && this.ruleForm.withAwtaCard === "yes") {
                    return callback(
                        new Error("Please input your LAMP ID Number")
                    );
                }

                await axios
                    .get(`/lookup/${this.event.slug}/${this.ruleForm.lampIDNumber}/find`)
                    .then(async (response) => {
                        callback();
                    })
                    .catch((error) => {
                        callback(new Error(error.response.data.error));
                    });
            }
        };
        var checkBookingCode = async (rule, value, callback) => {
            if (this.ruleForm.bookingCode != this.event.booking_code) {
                callback(new Error("Incorrect booking code"));
            }
        };
        return {
            ruleForm: {
                registrationType: "",
                withAwtaCard: "",
                attendingOption: this.event.with_booking ? "" : "Physical", // set default as hybrid if booking id disabled
                lampIDNumber: "",
                clusterGroup: "",
                bookingCode: "",
                email: "",
                specificMedicalAssistance: "",
                canBookDays: parseInt(this.event.member_booking_limit || 0),
                found: {},
            },
            rules: {
                registrationType: [
                    {
                        required: true,
                        message: "Please select Registration Type",
                        trigger: ["blur", "change"],
                    },
                ],
                withAwtaCard: [
                    {
                        required: true,
                        message: "Please select an answer",
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
                lampIDNumber: [
                    { validator: checklampIDNumber, trigger: ["change"] },
                    {
                        validator: async (rule, value, callback) => {
                            if (
                                this.ruleForm.lampIDNumber.length > 9 ||
                                this.ruleForm.lampIDNumber.length < 9
                            ) {
                                this.options = [];
                                this.ruleForm.clusterGroup = "";
                                return callback(
                                    new Error("Invalid LAMP ID Number")
                                );
                            }
                        },
                        trigger: ["blur"],
                    },
                    {
                        required: true,
                        message: "Please input your LAMP ID Number",
                        trigger: ["blur", "change"],
                    },
                ],
                bookingCode: [
                    {
                        required: true,
                        message: "Please input booking code",
                        trigger: ["blur", "change"],
                    },
                    { validator: checkBookingCode, trigger: ["submit"] },
                ],
                clusterGroup: [
                    {
                        required: true,
                        message: "Please select your cluster group",
                        trigger: ["blur", "change"],
                    },
                ],
            },
            guest_booking_code: this.event.booking_code,
            assignments: window.env.cluster_groups,
            options: [],
            hybrid_registration_deadline: window.env.hybrid_registration_deadline,
        };
    },
    watch: {
        "ruleForm.registrationType"(data, old) {
            if (old) this.resetData("reg-type");
        },
        "ruleForm.withAwtaCard"(data, old) {
            if (old) this.resetData("with-awta-card");
        },
        "ruleForm.attendingOption"(data, old) {
            if (old) this.resetData("attending-option");
        },
        options(data, old) {
            if (old.length > 0) this.ruleForm.clusterGroup = "";
        },
        'ruleForm.lampIDNumber': {
            handler: function (value, old) {
                if (value.length < 9 && old.length == 9) {
                    this.resetData("awta-card");
                }

                if (value.length === 9) {
                    this.resetData("awta-card");

                    this.isLoading = true;
                    
                    axios
                    .get(`/lookup/${this.event.slug}/${this.ruleForm.lampIDNumber}/find`)
                    .then(async (response) => {
                        this.ruleForm.found.email = response.data.email;
                        this.ruleForm.found.firstName = response.data.firstname;
                        this.ruleForm.found.lastName = response.data.lastname;
                        this.ruleForm.found.facebookName = response.data.facebook_name;
                        this.ruleForm.found.registrationType = response.data.registration_type;
                        this.ruleForm.found.country = response.data.country;
                        this.ruleForm.found.lampIDNumber = response.data.lamp_id;
                        this.ruleForm.found.oldlampIDNumber = response.data.old_lamp_card_number;
                        this.ruleForm.found.category = response.data.category;
                        this.ruleForm.found.attendingOption = this.ruleForm.attendingOption;
                        this.ruleForm.found.withAwtaCard = "yes";
                        this.ruleForm.found.localChurch = response.data.local_church;
                        this.ruleForm.found.canBookDays = response.data.can_book_days;
                        this.ruleForm.found.clusterGroup = response.data.cluster_group;
                        
                        this.ruleForm.email = response.data.email;
                        this.ruleForm.clusterGroup = response.data.cluster_group;  
                        this.options = this.assignments[response.data.local_church];

                        if (this.ruleForm.found.localChurch === 'Valenzuela') {
                            this.ruleForm.clusterGroup = '';  
                            this.options = this.assignments['Muntinlupa'];
                        }

                        this.isLoading = false;
                    })
                    .catch((error) => {
                        this.resetData("awta-card");
                        this.isLoading = false;
                    });
                }
            }
        }
    },
    mounted() {
        if (Object.keys(this.data.step_1).length != 0) {
            this.ruleForm = this.data.step_1;
        }

        
    },
    created() {
        this.event.custom_fields.forEach(element => {
            const name = element.name;

            // Make ruleForm reactive
            this.$set(this.ruleForm, name, element.default);

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
        snakeToCamel(str) {
            return str.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase());
        },
        submitForm(action) {
            this.$refs["ruleForm"].validate((valid) => {
                if (valid) {
                    if (this.ruleForm.withAwtaCard === "yes") {
                        this.$confirm(
                            `You are registering for ${this.ruleForm.found.firstName} ${this.ruleForm.found.lastName}. Are you sure you want to proceed?`,
                            "Warning",
                            {
                                confirmButtonText: "Yes",
                                cancelButtonText: "No",
                                type: "warning",
                                customClass: "prompt-message",
                            }
                        ).then(async () => {
                            if (this.ruleForm.attendingOption === "Online" || !this.event.with_booking) {
                                this.$emit("submit", this.ruleForm);
                            } else {
                                this.$emit("change-step", {
                                    destination: "step_3",
                                    current: "step_1",
                                    data: this.ruleForm,
                                });
                            }
                        });
                    } else {
                        this.$emit("change-step", {
                            destination: "step_2",
                            current: "step_1",
                            data: this.ruleForm,
                        });
                    }
                } else {
                    console.error("error submission on step 1!");
                    return false;
                }
            });
        },
        resetData(scope) {
            if (scope === "all") {
                this.$emit("reset");
                this.ruleForm.found = {};
            } else if (scope === "reg-type") {
                this.ruleForm.withAwtaCard = "";
                this.ruleForm.attendingOption = this.event.with_booking ? "" : "Physical";
                this.ruleForm.lampIDNumber = "";
                this.ruleForm.bookingCode = "";
                this.ruleForm.found = {};
                this.$emit("reset");
            } else if (scope === "with-awta-card") {
                this.ruleForm.lampIDNumber = "";
                this.ruleForm.found = {};
                this.$emit("reset");
            } else if (scope === "attending-option") {
                this.$emit("reset");
                this.ruleForm.bookingCode = "";
                this.ruleForm.found = {};
            } else if (scope === "awta-card") {
                this.$emit("reset");
                this.ruleForm.email = "";
                this.ruleForm.bookingCode = "";
                this.ruleForm.found = {};
                this.options = [];
                this.ruleForm.clusterGroup = "";
            }
        },
    },
};
</script>

<style scoped>
.el-input--suffix .el-input__inner {
    text-transform: uppercase !important;
}
</style>
