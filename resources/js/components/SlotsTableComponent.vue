<template>
    <div>
        <el-table v-loading="loading" :data="tableData" :span-method="objectSpanMethod" size="mini" border
            style="width: 100%">
            <el-table-column prop="registration_type" label="Registration Type" align="center" width="180" />

            <el-table-column prop="description" label="Description" align="center" width="100" />

            <el-table-column prop="event_date" label="Event Date" align="center" width="100" />

            <el-table-column prop="seat_count" label="Total Slots" align="center" width="80" />

            <el-table-column prop="taken" label="Taken" width="80" align="center" />

            <el-table-column label="Available" width="80" align="center">
                <template slot-scope="scope">
                    {{ scope.row.seat_count - scope.row.taken }}
                </template>
            </el-table-column>

            <!-- Local Church Allocation -->
            <el-table-column label="Local Church Allocation" min-width="250">
                <template slot-scope="scope">
                    <div v-if="scope.row.local_church_slots && scope.row.local_church_slots.length">
                        <div v-for="church in scope.row.local_church_slots" :key="church.id"
                            class="d-flex justify-content-between align-items-center mb-1">
                            <span>
                                {{ church.local_church }}
                            </span>

                            <span>
                                <strong>{{ church.seat_count }}</strong>
                                <small class="text-muted">
                                    ({{ church.taken }} taken /
                                    {{ church.available }} available)
                                </small>
                            </span>
                        </div>
                    </div>

                    <span v-else class="text-muted">
                        No allocation
                    </span>
                </template>
            </el-table-column>

            <el-table-column prop="activities" label="Activity" min-width="300">
                <template slot-scope="scope">
                    <p class="m-0" style="font-size: x-small;" v-for="(activity, index) in scope.row.activities"
                        :key="index">
                        {{ activity.timestamp }} -
                        {{ activity.user }} -
                        <i>{{ activity.message }}</i>
                    </p>
                </template>
            </el-table-column>

            <!-- Local Church Breakdown -->
            <el-table-column v-if="permissions.can_edit_lookup_data" label="Local Church Slots" align="center"
                width="160">
                <template slot-scope="scope">
                    <el-button type="success" size="mini" plain @click="openChurchSlots(scope.row)">
                        Manage
                    </el-button>
                </template>
            </el-table-column>

            <!-- Existing Add Slot -->
            <el-table-column v-if="permissions.can_add_slots" align="center" width="120">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" plain @click="openModal(scope.row)">
                        Add Slot
                    </el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- Existing Add Slot Dialog -->
        <el-dialog :title="dialogTitle" :visible.sync="dialogVisible" :width="$func.isMobileView() ? '95%' : '30%'">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px">
                <el-form-item label="Additional Slot Count" prop="number">
                    <el-input v-model="ruleForm.number"></el-input>
                </el-form-item>

                <el-form-item label="Notes" prop="notes">
                    <el-input v-model="ruleForm.notes"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="submitForm('ruleForm')">
                    Submit
                </el-button>
            </span>
        </el-dialog>


        <!-- Local Church Slots Dialog -->
        <el-dialog :title="churchSlotsTitle" :visible.sync="churchSlotsDialogVisible"
            :width="$func.isMobileView() ? '95%' : '65%'">
            <div v-if="selectedSlot">

                <!-- Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <el-card shadow="never">
                            <div class="text-muted">
                                Total Slots
                            </div>
                            <h3 class="mb-0">
                                {{ selectedSlot.seat_count }}
                            </h3>
                        </el-card>
                    </div>

                    <div class="col-md-4">
                        <el-card shadow="never">
                            <div class="text-muted">
                                Allocated
                            </div>
                            <h3 class="mb-0">
                                {{ allocatedSlots }}
                            </h3>
                        </el-card>
                    </div>

                    <div class="col-md-4">
                        <el-card shadow="never">
                            <div class="text-muted">
                                Unallocated
                            </div>
                            <h3 class="mb-0">
                                {{ unallocatedSlots }}
                            </h3>
                        </el-card>
                    </div>
                </div>

                <!-- Breakdown -->
                <el-table :data="churchSlotRows" border size="mini">
                    <el-table-column label="Local Church" min-width="250">
                        <template slot-scope="scope">
                            <el-select v-model="scope.row.local_church" placeholder="Choose Local Church"
                                style="width: 100%" :disabled="scope.row.saved">
                                <el-option v-for="church in localChurches" :key="church" :label="church"
                                    :value="church" />
                            </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column label="Slots" width="180" align="center">
                        <template slot-scope="scope">
                            <el-input-number v-model="scope.row.seat_count" :min="scope.row.taken"
                                :max="selectedSlot.seat_count" size="small" />
                        </template>
                    </el-table-column>
                </el-table>

                <div class="my-3">
                    <el-button type="success" size="small" plain @click="addChurchSlot">
                        + Add Local Church
                    </el-button>
                </div>

                <span slot="footer" class="dialog-footer">
                    <el-button @click="churchSlotsDialogVisible = false">
                        Cancel
                    </el-button>

                    <el-button type="primary" @click="saveChurchSlots">
                        Save Changes
                    </el-button>
                </span>
            </div>
        </el-dialog>
    </div>
</template>

<script>
export default {
    props: {
        event: {
            required: true,
        },
    },

    data() {
        return {
            tableData: [],
            loading: false,

            // Existing Add Slot dialog
            dialogVisible: false,
            dialogTitle: null,
            selected: null,

            ruleForm: {
                number: null,
                notes: '',
            },

            rules: {
                number: [
                    {
                        required: true,
                        message: 'Please input additional slot count',
                        trigger: 'blur'
                    }
                ],
                notes: [
                    {
                        required: true,
                        message: 'Please input the reason',
                        trigger: 'blur'
                    }
                ],
            },

            // Local Church Slots
            churchSlotsDialogVisible: false,
            churchSlotsTitle: null,
            selectedSlot: null,

            churchSlotRows: [],

            permissions: window.auth_user.permissions,

            localChurches: [
                'Bacolod',
                'Binan',
                'Canlubang',
                'Dasmarinas',
                'Granada',
                'Hinigaran',
                'Isabela',
                'Muntinlupa',
                'Pateros',
                'Tarlac',
            ],
        }
    },

    mounted() {
        this.fetchSlots();
    },

    computed: {
        allocatedSlots() {
            return this.churchSlotRows.reduce((total, row) => {
                return total + (parseInt(row.slot_count) || 0);
            }, 0);
        },

        unallocatedSlots() {
            if (!this.selectedSlot) {
                return 0;
            }

            return Math.max(
                0,
                parseInt(this.selectedSlot.seat_count) - this.allocatedSlots
            );
        },
    },

    methods: {
        async fetchSlots() {
            this.loading = true;

            try {
                const response = await axios.get(
                    `/${this.event.slug}/slots`
                );

                this.tableData = response.data;

            } catch (error) {
                this.$notify.error({
                    title: 'Unable to load slots.'
                });
            } finally {
                this.loading = false;
            }
        },
        objectSpanMethod({ row, columnIndex }) {
            if (columnIndex !== 0) {
                return;
            }

            const rows = this.tableData.filter(
                item => item.registration_type === row.registration_type
            );

            const firstIndex = this.tableData.findIndex(
                item => item.registration_type === row.registration_type
            );

            const currentIndex = this.tableData.findIndex(
                item => item.id === row.id
            );

            if (currentIndex === firstIndex) {
                return {
                    rowspan: rows.length,
                    colspan: 1
                };
            }

            return {
                rowspan: 0,
                colspan: 0
            };
        },

        // Existing Add Slot
        openModal(row) {
            this.dialogVisible = true
            this.selected = row
            this.dialogTitle = `${row.registration_type} - ${row.event_date}`;
        },

        submitForm(formName) {
            this.$refs[formName].validate(async (valid) => {
                if (valid) {
                    await axios.post(`/${this.event.slug}/slots`, {
                        selected: this.selected,
                        additional_count: this.ruleForm.number,
                        notes: this.ruleForm.notes
                    })
                        .then(async (response) => {

                            this.dialogVisible = false;
                            this.dialogTitle = null;
                            this.selected = null;

                            this.ruleForm = {
                                number: null,
                                notes: '',
                            }

                            await this.fetchSlots();

                            this.$notify.success({
                                title: 'Slot updated successfully!'
                            });
                        })
                        .catch(error => {
                            this.$notify.error({
                                title: error
                            });
                        });
                }
            });
        },

        // ==========================================
        // LOCAL CHURCH SLOTS
        // ==========================================

        openChurchSlots(row) {
            this.selectedSlot = row;

            this.churchSlotsTitle =
                `${row.registration_type} - ${row.description} - ${row.event_date}`;

            this.churchSlotRows = (row.local_church_slots || []).map(church => ({
                id: church.id,
                slot_id: church.slot_id,
                local_church: church.local_church,
                seat_count: parseInt(church.seat_count || 0),
                taken: parseInt(church.taken || 0),
                available: parseInt(church.available || 0),
            }));

            this.churchSlotsDialogVisible = true;
        },

        addChurchSlot() {
            this.churchSlotRows.push({
                id: null,
                slot_id: this.selectedSlot.id,
                local_church: '',
                seat_count: 0,
                taken: 0,
                available: 0,
                saved: false
            });
        },

        async saveChurchSlots() {
            if (!this.churchSlotRows.length) {
                this.$message.error('Please add at least one Local Church.');
                return;
            }

            // Make sure every row has a church
            const hasEmptyChurch = this.churchSlotRows.some(row => !row.local_church);

            if (hasEmptyChurch) {
                this.$message.error('Please select a Local Church for all rows.');
                return;
            }

            // Prevent duplicate churches
            const churches = this.churchSlotRows.map(row => row.local_church);
            const hasDuplicate = churches.some(
                (church, index) => churches.indexOf(church) !== index
            );

            if (hasDuplicate) {
                this.$message.error('A Local Church cannot be added more than once.');
                return;
            }

            // Validate total allocation
            if (this.allocatedSlots > parseInt(this.selectedSlot.seat_count)) {
                this.$message.error(
                    'The allocated slots cannot exceed the total slots.'
                );
                return;
            }

            // Validate against already taken slots
            const invalidRow = this.churchSlotRows.find(row => {
                return parseInt(row.seat_count || 0) < parseInt(row.taken || 0);
            });

            if (invalidRow) {
                this.$message.error(
                    `${invalidRow.local_church} cannot have fewer slots than the number already taken.`
                );
                return;
            }

            try {
                await axios.post(
                    `/${this.event.slug}/slots/${this.selectedSlot.id}/local-church`,
                    {
                        local_church_slots: this.churchSlotRows.map(row => ({
                            id: row.id,
                            local_church: row.local_church,
                            seat_count: row.seat_count
                        }))
                    }
                );

                this.churchSlotsDialogVisible = false;

                await this.fetchSlots();

                this.churchSlotsDialogVisible = false;

                this.$notify.success({
                    title: 'Local Church slots updated successfully!'
                });
            } catch (error) {
                this.$notify.error({
                    title: 'Unable to update Local Church slots.'
                });
            }
        },

        availableChurches(row) {
            const selectedChurches = this.churchSlotRows
                .filter(item => item !== row)
                .map(item => item.local_church)
                .filter(Boolean);

            return this.localChurches.filter(church => {
                return !selectedChurches.includes(church);
            });
        },

        async removeChurchSlot(row) {

            if (!row.id) {
                const index = this.churchSlotRows.indexOf(row);

                if (index !== -1) {
                    this.churchSlotRows.splice(index, 1);
                }

                return;
            }

            try {
                await axios.delete(
                    `/${this.event.slug}/slots/${this.selectedSlot.id}/local-church/${row.id}`
                );

                const index = this.churchSlotRows.indexOf(row);

                if (index !== -1) {
                    this.churchSlotRows.splice(index, 1);
                }

                this.$notify.success({
                    title: 'Local Church slot removed successfully!'
                });

            } catch (error) {
                this.$notify.error({
                    title: 'Unable to remove Local Church slot.'
                });
            }
        }
    }
}
</script>