<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <component ref="myChild" v-bind:is="currentTabComponent" v-bind:data="currentTabData" :slots="slots" @change-step="changeStep" @reset="reset" @submit="submit" :event="event"/>
            </div>
        </div>
        <div class="row justify-content-center mb-5">
            <div class="col-md-6" :style="`--theme-color: ${themeColor}`">
                <el-button v-if="currentStep > 1" plain @click="$refs.myChild.submitForm('back')">Back</el-button>
                <el-button 
                    v-if="
                        (this.currentStep === 1 && this.data.step_1.withAwtaCard === 'yes' && this.data.step_1.attendingOption === 'Online') ||
                        (this.currentStep === 2 && 
                            (this.data.step_1.registrationType === 'Guest' || (this.data.step_1.registrationType === 'Member' && this.data.step_1.attendingOption === 'Online'))) ||
                        this.currentStep === 3 ||
                        this.currentStep === 2 && !event.with_booking
                    "
                    type="theme" 
                    class="el-button--theme"
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

        <Waiver :show="showWaiver" @continue="resume()" />
        <button id="continueBtn" style="display: none" @click="continueClicked">Continue</button>
    </div>
</template>

<script>
    import Waiver from "../../../components/Registration/3/WaiverComponent.vue";
    import Step1 from "../../../components/Registration/3/Step1.vue";
    import Step2 from "../../../components/Registration/3/Step2.vue";
    import Step3 from "../../../components/Registration/3/Step3.vue";

    export default {
        components: {
            Step1,
            Step2,
            Step3,
            Waiver
        },
        props: {
            slots: {
                required: false
            },
            event: {
                required: true
            }
        },
        data() {
            return {
                themeColor: this.event.border_color,
                currentStep: 1,
                currentTabComponent: null,
                currentTabData: null,
                countries: this.$allCountries,
                data: {
                    step_1: {},
                    // step_1: {"registrationType":"Guest","withAwtaCard":"","attendingOption":"Physical","reasonForOnlineAttendance":"","zoomAccessEmail":"","lampIDNumber":"","clusterGroup":"","bookingCode":"","email":"","specificMedicalAssistance":"","canBookDays":0,"found":{},"birthday":null,"camper_category":null,"holy_ghost_seeker":"","inviter_complete_name":"","transportation":"","tshirt_size":""},
                    step_2: {},
                    step_3: {}
                },
                year: window.env.year,
                showWaiver: false,
                continueResolver: null
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

                if (destination === 'step_2' && current === 'step_3') {
                    this.data.step_3 = {}
                }

                this.setTabComponents();
            },
            reset() {
                this.data.step_2 = {};
                this.data.step_3 = {};
            },
            async submit(data) {
                this.showWaiver = true;

                console.log(data);

                await this.waitForContinue();

                this.showWaiver = false;

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

                if (!this.event.with_booking) {
                    this.data.step_3 = {
                        booked: this.data.step_1.registrationType === 'Member' ? this.slots.member.map(item => item.id) : this.slots.guest.map(item => item.id)
                    }
                }

                const loading = this.$loading({
                    lock: true,
                    text: 'Loading',
                    background: 'rgba(0, 0, 0, 0.7)'
                });

                setTimeout(async () => {
                    await axios.post(`/registration/${this.event.slug}`, this.data)
                    .then(async (response) => {
                        loading.close()
                        
                        this.showTicket(response.data.toString())

                        this.$refs[formName].resetFields();
                    });
                }, 1000);
            },
            continueClicked() {
                if (this.continueResolver) {
                    this.continueResolver(); // resume the paused function
                    this.continueResolver = null; // reset
                }
            },
            waitForContinue() {
                return new Promise(resolve => {
                    this.continueResolver = resolve; // save resolve for later
                });
            },
            showTicket(uuid) {
                window.location.href = `/${this.event.slug}/registration/ticket?id=${uuid}`;
            },
            resume() {
                const btn = document.getElementById("continueBtn");
                btn.click();
            },
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