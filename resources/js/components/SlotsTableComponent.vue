<template>
    <div>
        <el-table
        :data="tableData"
        :span-method="objectSpanMethod"
        border
        style="width: 100%">
            <el-table-column
                prop="registration_type"
                label="Registration Type"
                align="center"
                width="180">
            </el-table-column>
            <el-table-column
                prop="description"
                label="Description"
                align="center"
                width="180">
            </el-table-column>
            <el-table-column
                prop="event_date"
                label="Event Date"
                align="center"
                width="180">
            </el-table-column>
            <el-table-column
                prop="seat_count"
                label="Total Slots"
                align="center"
                width="130">
            </el-table-column>
            <el-table-column
                prop="taken"
                label="Taken"
                align="center"
                width="130">
            </el-table-column>
            <el-table-column
                prop="available"
                label="Remaining"
                align="center"
                width="130">
            </el-table-column>
            <el-table-column
                prop="activities"
                label="Activity"
                width="500">
                <template slot-scope="scope">
                    <p class="m-0" style="font-size: x-small;" v-for="(activity, index) in scope.row.activities" :key="index">
                        {{ activity.timestamp }} - {{activity.user}} - <i>{{activity.message}}</i>
                    </p>
                </template>
            </el-table-column>
            <el-table-column align="center" width="120">
                <template slot-scope="scope">
                <el-button type="primary" plain @click="openModal(scope.row)">Add Slot</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog
            :title="dialogTitle"
            :visible.sync="dialogVisible"
            :width="$func.isMobileView() ? '95%' : '30%'">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px">
                <el-form-item label="Additional Slot Count" prop="number">
                    <el-input v-model="ruleForm.number"></el-input>
                </el-form-item>
                <el-form-item label="Notes" prop="notes">
                    <el-input v-model="ruleForm.notes"></el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="submitForm('ruleForm')">Submit</el-button>
            </span>
        </el-dialog>
    </div>
</template>
  
<script>
export default {
    props: {
        event: {
            required: true,
        },
        slots: {
            required: true,
            type: Array
        },
    },
    data() {
        return {
            tableData: this.slots,
            dialogVisible: false,
            dialogTitle: null,
            selected: null,
            ruleForm: {
                number: null,
                notes: '',
            },
            rules: {
                number: [
                    { required: true, message: 'Please input additional slot count', trigger: 'blur' }
                ],
                notes: [{ required: true, message: 'Please input the reason', trigger: 'blur' }],
            }
        }
    },
    methods: {
        objectSpanMethod({ row, column, rowIndex, columnIndex }) {
            if (columnIndex === 0) {
                if (rowIndex % (this.slots.length/2) === 0) {
                    return {
                        rowspan: (this.slots.length/2),
                        colspan: 1
                    };
                } else {
                    return {
                        rowspan: 0,
                        colspan: 0
                    };
                }
            }
        },
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
                        
                        this.$notify.success({
                            title: 'Slot updated successfully!',
                            message: 'Please refresh the page to see the changes.'
                        });
                    })
                    .catch(error => {
                        this.$notify.error({
                            title: error
                        });
                    });
                } else {
                    return false;
                }
            });
        }
    }
}
</script>