<template>
    <div class="row justify-content-center my-4">
        <div v-bind:class="{'col-lg-12 mb-4' : isRebooking, 'col-md-6 col-lg-4 mb-4' : !isRebooking}" v-for="(registration, i) in registrations" :key="i">
            <el-card :id="`capture_${i}`" class="box-card ticket-header">
                <div slot="header" class="clearfix">
                    <span class="text-uppercase">{{ event.name }}</span>

                    <el-button icon="el-icon-download" class="block el-button el-button--primary float-end is-plain md:hidden mx-0 p-1 sm:hidden xs:hidden" type="primary" plain @click.preventDefault="printThis(`capture_${i}`)" />
                </div>
                <div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 div-personal-details">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <small>Name</small>
                                    <span class="text-lg font-bold d-block text-uppercase text-break">{{ registration.firstname }} {{ registration.lastname }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <small>Facebook Name</small>
                                    <span class="text-lg font-bold d-block text-uppercase text-break">{{ registration.facebook_name || '--' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <small>Email Address</small>
                                    <span class="text-md font-bold d-block text-break">{{ registration.email || '--' }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <small>Registration Type</small>
                                    <span class="text-md font-bold d-block">{{ registration.registration_type }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-6 div-qr-code">
                            <barcode-component :uuid="registration.uuid" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-3">
                            <small>Rate</small> <small>({{registration.attending_option}})</small>
                            <span class="text-md font-bold d-block">{{ $func.formatAmount(registration.rate) }}</span>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-3">
                            <small>Local Church</small>
                            <span class="text-md font-bold d-block">{{ registration.local_church }}</span>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-3">
                            <small>Country</small>
                            <span class="text-md font-bold d-block">{{ registration.country }}</span>
                        </div>
                    </div>

                    <div v-if="registration.attending_option != 'Online'" class="row mb-3">
                        <div class="col-md-12">
                            <small class="d-block">Booked Dates</small>
                            <span class="text-md font-bold" v-if="registration.booked_dates.length > 0" v-html="registration.booked_dates.join([separator = ',  '])"></span>
                            <span class="font-bold text-black-50 text-md" v-else>Not yet booked. Please reach out to your local coordinator to book your schedule.</span>
                            <span v-if="registration.booked_dates.length > 0"><el-tag size="mini" effect="dark" :type="registration.booking_status === 'Confirmed' ? 'success' : (registration.booking_status === 'Cancelled' ? 'danger' : 'warning')">{{ registration.booking_status }}</el-tag></span>
                        </div>
                    </div>

                    <div v-if="registration.attending_option != 'Online'" class="row">
                        <div class="col-md-12">
                            <small>***Please screenshot this ticket. This will be your virtual LAMP ID number, in case you opted not to avail the physical card. This will be used in the future LAMP church events and activities.</small>
                        </div>
                    </div>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
import html2canvas from 'html2canvas';

export default {
    props: {
        registrations: {
            required: true,
            type: Array,
            required: true
        },
        isRebooking: {
            default: false,
            type: Boolean,
            required: false
        },
        congratulate: {
            default: false,
            type: Boolean,
            required: false
        },
        event: {
            required: false
        },
    },
    data() {
        return {
            year: null,
            zoom: {
                link: null,
                id: null,
                passcode: null
            }
        }
    },
    mounted() {
        this.year = window.env.year;
        this.zoom.link = this.event.zoom_url;
        this.zoom.id = this.event.zoom_id;
        this.zoom.passcode = this.event.zoom_password;

        if (this.congratulate && this.registrations[0].has_viewed_ticket == null)
            this.open()
    },
    methods: {
        capitalizeString(str) { 
            return str.toLowerCase().split(' ').map(function(word) {
               return word.replace(word[0], word[0].toUpperCase());
           }).join(' ');
        },
        goToRegistration() {
            window.location.href = `/registration`;
        },
        open() {
            var msg = '<strong>Congratulations!</strong> Your registration has been accepted. ';

            if (this.registrations[0].registration_type === 'Guest' && this.registrations[0].attending_option != 'Online' && this.registrations[0].email != '' && this.registrations[0].email != null)
                msg += '<br /><br /><small style="line-height: 0px;">We have sent an email to <i>' + this.registrations[0].email + '</i>. <br />Please check to see the details.</small>';

            if (this.registrations[0].registration_type === 'Member' && this.registrations[0].attending_option != 'Online' && this.registrations[0].rate > 0)
                msg += '<br /><br /><small style="line-height: 0px;">Please settle your balance or at least half of the registration fee to confirm your booking. It will automatically expire after 7 days.<br />For cancellations, please contact your local Registrars for help.</small>';
            
            if (this.registrations[0].attending_option === 'Online') {
                msg += `<br /><br /><small style="line-height: 0px;">To watch the live broadcast, join our FB Group <br/><a href="${this.event.fb_group_url}">${this.event.fb_group_url}</a></small>`

                msg += `<br /><br /><small style="line-height: 0px;">You may also join us via <b>Zoom</b>:<br />
                        <a href="${this.zoom.link}">${this.zoom.link}</a><br /><br />
                        Meeting ID: ${this.zoom.id} <br />
                        Passcode:${this.zoom.passcode}</small> <br /><br />`
            }
            
            if (this.registrations[0].registration_type === 'Member' && this.registrations[0].with_awta_card == 'none')
                msg += '<br /><br /><small style="line-height: 0px;">Note: <i>A new LAMP ID Number is issued for you.</i> If you want to avail the physical card, an additional Php 35.00 will be required. Kindly reach out to your local Registrars for payment and issuance.</small><br/><img width="130" height="80" class="mx-2 mt-3 rounded shadow" src="/images/new_id.jpg"><br/><small style="font-size: 8px;font-style: italic;color: gray;">sample ID only</small><br /><small>Would you like to avail the new LAMP ID?</small>';
            else if (this.registrations[0].registration_type === 'Member' && this.registrations[0].with_awta_card == 'lost')
            msg += '<br /><br/><small style="line-height: 0px;">Note: For payment and issuance, kindly reach out to you local Registrars</small><br/><img width="130" height="80" class="mx-2 mt-3 rounded shadow" src="/images/new_id.jpg"><br/><small style="font-size: 8px;font-style: italic;color: gray;">sample ID only</small><br /><small>Would you like to report your card lost and get <br/>a replacement for PHP 35.00?</small>';

            
            this.$confirm(msg, 'You did it!', {
                confirmButtonText: this.registrations[0].registration_type === 'Member' && (this.registrations[0].with_awta_card == 'none' || this.registrations[0].with_awta_card == 'lost') ? 'Yes' : 'Continue',
                cancelButtonText: 'No',
                showCancelButton: this.registrations[0].registration_type === 'Member' && (this.registrations[0].with_awta_card == 'none' || this.registrations[0].with_awta_card == 'lost'),
                type: 'success',
                showClose: false,
                closeOnPressEscape: false,
                closeOnHashChange: false,
                closeOnClickModal: false,
                center: true,
                dangerouslyUseHTMLString: true
            }).then(async () => {
                if (this.registrations[0].registration_type === 'Member'&& (this.registrations[0].with_awta_card == 'none' || this.registrations[0].with_awta_card == 'lost')) {
                    await axios.post(`/registration/${this.registrations[0].id}/update`, {
                        avail_new_lamp_id: 'yes'
                    })
                } else {
                    await axios.post(`/registration/${this.registrations[0].id}/update`, {
                        mark_as_viewed: true
                    })
                }
            }).catch(async () => {
                await axios.post(`/registration/${this.registrations[0].id}/update`, {
                    avail_new_lamp_id: 'no'
                })
            })
        },
        async printThis(id) {
            const options = {
                type: "dataURL"
            };

            const printCanvas = await html2canvas(document.querySelector("#" + id), options);

            const link = document.createElement("a");
            link.setAttribute("download", "awta-ticket.png");
            link.setAttribute(
                "href",
                printCanvas
                .toDataURL("image/png")
                .replace("image/png", "image/octet-stream")
            );

            link.click();
        }
    },   
}
</script>