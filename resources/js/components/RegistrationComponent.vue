<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <img width="100%" class="mb-3 rounded shadow" src="/images/2024_banner_B.jpeg">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <banner-component />
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <component ref="myChild" v-bind:is="currentTabComponent" v-bind:data="currentTabData" :slots="slots" @change-step="changeStep" :closeRegForMember="false" @reset="reset" @submit="submit"/>
            </div>
        </div>
        <div class="row justify-content-center mb-5">
            <div class="col-md-6">
                <el-button v-if="currentStep > 1" plain @click="$refs.myChild.submitForm('back')">Back</el-button>
                <el-button 
                    v-if="
                        (this.currentStep === 1 && this.data.step_1.withAwtaCard === 'yes' && this.data.step_1.attendingOption === 'Online') ||
                        (this.currentStep === 2 && 
                            (this.data.step_1.registrationType === 'Guest' || (this.data.step_1.registrationType === 'Member' && this.data.step_1.attendingOption === 'Online'))) ||
                        this.currentStep === 3
                    "
                    type="theme" 
                    @click="$refs.myChild.submitForm('next')">
                    Submit
                </el-button>
                <el-button 
                    v-else
                    plain 
                    @click="$refs.myChild.submitForm('next')">
                    Next
                </el-button>
                <el-progress :stroke-width="5" style="width:fit-content;display: inline;" define-back-color="#595353" class="m-2 float-end" :color="customColorMethod" :percentage="(100 * currentStep) / 3" :format="format"></el-progress>
                <!-- <el-button v-bind:type="(currentStep === 3 || (currentStep === 2 && data.step_1.registrationType === 'Guest')) ? 'primary' : ''" v-bind:plain="currentStep < 3" @click="$refs.myChild.submitForm('next')">{{ (currentStep === 3 || (currentStep === 2 && data.step_1.registrationType === 'Guest')) ? 'Submit' : 'Next' }}</el-button> -->
            </div>
        </div>

        <el-dialog
        :visible.sync="dialogVisible"
        custom-class="responsive-dialog"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
        :destroy-on-close="false">
        <span class="content">
            <div class="m-0">
                <el-checkbox v-model="isAllowing"/>&nbsp;&nbsp;I authorize LAMP Church to collect, use, and retain the personal information in this form for the purpose of attending AWTA {{year}}  (Annual Worship and Thanksgiving Assembly) and in the pursuit of any legal interests of the church.
                <span v-if="display" class="error">
                    Please check if you want to continue
                </span>
            </div>
        </span>
        <span slot="footer" class="dialog-footer">
            <el-button @click="validateAuthorization()">Continue</el-button>
        </span>
        </el-dialog>
    </div>
</template>

<script>
    import Step1 from "../components/Registration/Step1.vue";
    import Step2 from "../components/Registration/Step2.vue";
    import Step3 from "../components/Registration/Step3.vue";

    export default {
        components: {
            Step1,
            Step2,
            Step3
        },
        props: {
            slots: {
                required: false
            },
        },
        data() {
            return {
                currentStep: 1,
                currentTabComponent: null,
                currentTabData: null,
                countries: this.$allCountries,
                data: {
                    step_1: {},
                    step_2: {},
                    step_3: {}
                },
                isAllowing: false,
                dialogVisible: window.env.display_disclosure_prompt === 'yes',
                display: false,
                year: window.env.year
            }
        },
        created() {
            this.setTabComponents();
        },
        methods: {
            customColorMethod(percentage) {
                if (percentage == 100) {
                    return '#67c23a';
                } else {
                    return '#409eff';
                }
            },
            setTabComponents() {
                if (this.currentStep === 1)
                    this.currentTabComponent = Step1;
                else if (this.currentStep === 2)
                    this.currentTabComponent = Step2;
                else if (this.currentStep === 3)
                    this.currentTabComponent = Step3;

                this.currentTabData = this.data
            },
            format() {
                return `Page ${this.currentStep} of 3`;
            },
            async changeStep({destination, current, data}) {
                if (destination === 'step_1') this.currentStep = 1
                if (destination === 'step_2') this.currentStep = 2
                if (destination === 'step_3') this.currentStep = 3

                if (current === 'step_1') this.data.step_1 = data;
                if (current === 'step_2') this.data.step_2 = data;
                if (current === 'step_3') this.data.step_3 = data;

                this.setTabComponents();
            },
            reset() {
                this.data.step_2 = {};
                this.data.step_3 = {};
            },
            submit(data) {
                if (this.currentStep === 3) {
                    this.data.step_3 = data
                }

                if (this.currentStep === 2) {
                    this.data.step_2 = data;
                    this.data.step_3 = {};
                }

                if (this.currentStep === 1) {
                    this.data.step_1 = data;
                    this.data.step_2 = {};
                    this.data.step_3 = {};
                }

                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });

                setTimeout(async () => {
                    await axios.post("/registration", this.data)
                    .then(async (response) => {
                        loading.close()
                        
                        this.showTicket(response.data.toString())

                        this.$refs[formName].resetFields();
                    });
                }, 1000);
            },
            showTicket(uuid) {
                window.location.href = `registration/ticket?id=${uuid}`;
            },
            validateAuthorization() {
                if (!this.isAllowing)
                    this.display = true
                else
                    this.dialogVisible = false
            }
        }
    }
</script>
