<template>
  <div class="row justify-content-center">
    <div class="col-md-24">
      <el-table
        :data="events"
        border
        :show-header="false"
        style="width: 100%"
        size="medium">
          <el-table-column
              prop="name"
              label="Name">
              <template slot-scope="scope">
                  <span class="text-dark text-uppercase">{{ scope.row.name }}</span><br />
                  <small style="
                      font-size: 12px;
                      color: lightslategray;
                  ">{{ scope.row.description }}</small><br v-if="scope.row.description"/>
                  <el-tag v-if="!scope.row.close_registration" size="mini" type="success">Active</el-tag>
                  <el-tag v-else size="mini" type="danger">Closed</el-tag>
                  <el-tag v-if="scope.row.local_church === 'General'" size="mini" effect="plain">General</el-tag>
                  <el-tag v-else size="mini" effect="plain" type="warning">{{ scope.row.local_church }}</el-tag>
              </template>
          </el-table-column>
          <el-table-column
              label="Buttons">
              <template slot-scope="scope">
              <el-row>
                  <a :href="`/${scope.row.slug}/dashboard`" target="_blank"><el-button :disabled="!scope.row.has_access" type="primary" plain size="small"><i class="el-icon-s-data mr-2"></i>&nbsp;&nbsp;Dashboard</el-button></a>
                  <a :href="`/${scope.row.slug}/registration`" target="_blank"><el-button type="success" plain size="small"><i class="el-icon-edit-outline mr-2"></i>&nbsp;&nbsp;Registration</i></el-button></a>
                  <a :href="`/${scope.row.slug}/attendance`" target="_blank"><el-button type="warning" plain size="small"><i class="el-icon-full-screen mr-2"></i>&nbsp;&nbsp;Attendance</i></el-button></a>
                  <a v-if="scope.row.enable_online_checkin" :href="`/${scope.row.slug}/check-in`" target="_blank"><el-button type="info" plain size="small"><i class="el-icon-thumb mr-2"></i>&nbsp;&nbsp;Self Check-In</i></el-button></a>
              </el-row>
              </template>
          </el-table-column>
          <el-table-column
              label="Buttons"
              width="300">
              <template slot-scope="scope">
              <el-row class="text-center">
                <el-button type="danger" :disabled="!scope.row.has_access" plain size="small" @click="manageEvent(scope.row)"><i class="el-icon-s-tools mr-2"></i>&nbsp;&nbsp;Settings</el-button>
                <a :href="`/${scope.row.slug}/home`"><el-button type="primary" :disabled="!scope.row.has_access" plain size="small"><i class="el-icon-s-tools mr-2"></i>&nbsp;&nbsp;Administration</el-button></a>
              </el-row>
              </template>
          </el-table-column>
      </el-table>

      <el-dialog
        title="Event Management"
        :visible.sync="eventDialog"
        width="60%">
          <el-form ref="form" :model="form" label-width="120px" size="mini">
            <el-tabs type="border-card" v-model="selected">
              <el-tab-pane label="Event" name="event">
                <div class="row">
                  <div class="col-md-9">
                    <el-form-item label="Event Name">
                        <el-input v-model="form.name"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-3">
                    <el-form-item label="Slug">
                        <el-input v-model="form.slug"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item label="Description">
                        <el-input v-model="form.description"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-3">
                    <el-form-item label="Local Church">
                      <el-select
                            v-model="form.local_church"
                            placeholder="Choose"
                        >
                          <el-option label="General" value="General"></el-option>
                          <el-option v-for="lc in localChurches" :key="lc" :label="lc" :value="lc"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                  <div class="col-md-3">
                    <el-form-item label="Template ID">
                        <el-input v-model="form.template_id"></el-input>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Registration" name="registration">
                <div class="row">
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Close Registration">
                      <small class="text-sm">When enabled, the registration form will be disabled. New participants will no longer be able to access the form or submit responses.</small>
                      <el-switch class="d-block" v-model="form.close_registration"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Disclosure Prompt">
                      <small class="text-sm">When enabled, the registration disclosure prompt will display.</small>
                      <el-switch class="d-block" v-model="form.display_disclosure_prompt"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Online Check-in">
                      <small class="text-sm">When enabled, participants are allowed to online check-in.</small>
                      <el-switch class="d-block" v-model="form.enable_online_checkin"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Attending Option">
                      <small class="text-sm">When enabled, participants must select their mode of attendance: Physical, Online, or Hybrid.</small>
                      <el-switch class="d-block" v-model="form.show_attending_option"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Under Maintenance">
                      <small class="text-sm">When enabled, a maintenance page will be shown. Registration and booking will be unavailable.</small>
                      <el-switch class="d-block" v-model="form.is_maintenance"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="LAMP Physical ID Issuance">
                      <small class="text-sm">When enabled, a prompt with an option will appear at the end of registration, and a reminder about the LAMP ID issuance will also be included in emails.</small>
                      <el-switch class="d-block" v-model="form.enable_id_issuance"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Available Set IDs">
                      <small class="text-sm">Series of ID numbers reserved for issuance during new member registrations.</small>
                      <el-select v-model="form.available_id_set" placeholder="please select the set of ids">
                        <el-option label="Zone one" value="shanghai"></el-option>
                        <el-option label="Zone two" value="beijing"></el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Booking" name="booking">
                <div class="row">
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Booking">
                      <small class="text-sm">When enabled, participants must select specific dates to book. If disabled, registrants are automatically booked for all event dates.</small>
                      <el-switch class="d-block" v-model="form.with_booking"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Guest Booking">
                      <small class="text-sm">When enabled, users must enter a valid booking code in order to register a guest.</small>
                      <el-switch class="d-block" v-model="form.with_guest_booking_code"></el-switch>
                      <el-input v-model="form.booking_code" style="width: 200px; margin-top: 10px" placeholder="Booking Code"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Booking Limit">
                      <small class="text-sm">Specifies the maximum number of days that can be booked.</small>
                      <div style="display: flex; gap: 10px">
                        <div>
                          <small class="text-sm" style="margin-right: 5px">Guest</small>
                          <el-input-number v-model="form.guest_booking_limit":min="1" :max="10"></el-input-number>
                        </div>
                        <div>
                          <small class="text-sm" style="margin-right: 5px">Member</small>
                          <el-input-number v-model="form.member_booking_limit":min="1" :max="10"></el-input-number>
                        </div>
                      </div>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Attendance" name="attendance">
                <div class="row">
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Active Slot ID">
                      <small class="text-sm">Specifies the selected slot ID to be marked as active for the day. This slot will be used for attendance checking.</small>
                      <div style="display: flex; gap: 10px">
                        <div>
                          <small class="text-sm" style="margin-right: 5px">Guest</small>
                          <el-select v-model="form.active_guest_slot_id" placeholder="Select">
                          <!-- <el-option
                            v-for="item in options"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value"
                            :disabled="item.disabled">
                          </el-option> -->
                        </el-select>
                        </div>
                        <div>
                          <small class="text-sm" style="margin-right: 5px">Member</small>
                          <el-select v-model="form.active_member_slot_id" placeholder="Select">
                            <!-- <el-option
                              v-for="item in options"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value"
                              :disabled="item.disabled">
                            </el-option> -->
                          </el-select>
                        </div>
                      </div>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Socials & Streaming" name="socials">
                <div class="row">
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="FB Group URL">
                      <small class="text-sm">Link to the Facebook group where announcements will be posted. This link will also be included in emails. Leave empty if not applicable.</small>
                      <el-input v-model="form.fb_group_url"></el-input>
                    </el-form-item>
                  </div>
                </div>
                <div class="col-md-12">
                  <el-form-item class="rm-margin" label="Zoom Credentials">
                    <small class="text-sm">Meeting details (e.g., Zoom link, Meeting ID, and Passcode) that participants will use to join sessions. Leave empty if not applicable.</small>
                    <div style="display: flex; gap: 10px">
                      <div style="width: 10%">
                        <small class="text-sm" style="margin-right: 5px">with Zoom?</small>
                        <el-checkbox v-model="form.enable_zoom_registration">Yes</el-checkbox>
                      </div>
                      <div style="width: 50%">
                        <small class="text-sm" style="margin-right: 5px">Link</small>
                        <el-input v-model="form.zoom_url"></el-input>
                      </div>
                      <div style="width: 20%">
                        <small class="text-sm" style="margin-right: 5px">ID</small>
                        <el-input v-model="form.zoom_id"></el-input>
                      </div>
                      <div style="width: 20%">
                        <small class="text-sm" style="margin-right: 5px">Passcode</small>
                        <el-input v-model="form.zoom_password"></el-input>
                      </div>
                    </div>
                  </el-form-item>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Content" name="content">
                <div class="row">
                  <div class="col-md-6">
                    <el-form-item label="Banner File Name">
                        <el-input v-model="form.banner_file_name"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-6">
                    <el-form-item label="Border Color">
                      <el-color-picker v-model="form.border_color"></el-color-picker>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item  class="rm-margin" label="Form Description Block">
                      <small class="text-sm">HTML block on the registration form for event details (schedule, timings, venue, etc.). Leave empty if not needed.</small>
                      <el-input type="textarea" :autosize="{ minRows: 4, maxRows: 100}" v-model="form.form_description_block"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Dates & Timings">
                      <small class="text-sm">Defines the dates and timings shown across emails, registration forms, and other event details</small>
                      <div style="display: flex; gap: 10px">
                        <div style="width: 25%">
                          <small class="text-sm" style="margin-right: 5px">Event Date</small>
                          <el-input v-model="form.event_date"></el-input>
                        </div>
                        <div style="width: 25%">
                          <small class="text-sm" style="margin-right: 5px">Event Timing</small>
                          <el-input v-model="form.event_timing"></el-input>
                        </div>
                        <div style="width: 25%">
                          <small class="text-sm" style="margin-right: 5px">Hybrid Registration Deadline</small>
                          <el-input v-model="form.hybrid_registration_deadline"></el-input>
                        </div>
                        <div style="width: 25%">
                          <small class="text-sm" style="margin-right: 5px">Rebooking Deadline</small>
                          <el-input v-model="form.rebooking_deadline"></el-input>
                        </div>
                      </div>
                    </el-form-item>
                  </div>
                </div>
              </el-tab-pane>
              <el-tab-pane label="Custom Fields" name="customer_fields">
              </el-tab-pane>
              <el-tab-pane label="Event Venues" name="event_venues">
                <div class="row">
                  <div class="col-md-6">
                    <el-form-item class="rm-margin" label="Venue">
                      <small class="text-sm">Primary venue and official location for the event</small>
                      <el-input v-model="form.main_venue"></el-input>
                    </el-form-item>
                  </div>
                  <div class="col-md-12">
                    <el-form-item class="rm-margin" label="Map URL">
                      <small class="text-sm">Paste the URL of the designated main venue map for this event (Google Maps link)</small>
                      <el-input v-model="form.venue_map"></el-input>
                    </el-form-item>
                  </div>
                  <!-- add here the adding of multiple venues & has_multiple_venues selection -->
                </div>
              </el-tab-pane>
              <el-tab-pane label="Rates" name="rates">
              </el-tab-pane>
              <el-tab-pane label="Slots" name="slots">
              </el-tab-pane>
            </el-tabs>
          </el-form>
        </el-dialog>
    </div>
  </div>
  </template>
  
  <script>
    export default {
      props: {
        events: {
            required: true
        }
      },
      data() {
        return {
          eventDialog: false,
          selected: 'content',
          localChurches: Object.keys(window.env.cluster_groups),
          form: {
            name: '',
            slug: '',
            local_church: 'General',
            template_id: 1
          }
        }
      },
      methods: {
        manageEvent(event) {
          this.eventDialog = true
        }
      }
    }
  </script>