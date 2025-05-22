<template>
<div class="col-md-12">
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <small>Payment Status</small>
                    <el-alert
                        class="py-1 text-xs d-inline d-block"
                        style="width: fit-content;"
                        :title="data.payment_status"
                        :type="data.payment_status === 'Paid' || data.payment_status === 'Free' ? 'success' : 'warning'"
                        :closable="false">
                    </el-alert>
                </div>
                <div class="col-md-4 mb-3">
                    <small>Rate</small>
                    <span class="text-lg font-bold d-block">{{ $func.formatAmount(data['rate']) }}</span>
                </div>
                <div class="col-md-4 mb-3">
                    <small>Balance</small>
                    <span class="text-md font-bold d-block">{{ $func.formatAmount(balance) }}</span>
                </div>
            </div>

            <payments-table :registration="data" />
        </div>

        <div v-if="data.payment_status != 'Paid' && data.payment_status != 'Free'" class="card-body border-top pb-0">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <el-form-item label="Payment Date" prop="date" required>
                            <el-date-picker v-model="ruleForm.date" type="date" placeholder="Pick a day" :picker-options="pickerOptions"></el-date-picker>
                        </el-form-item>
                    </div>
                    <div class="col-md-4 mb-3">
                        <el-form-item label="Amount Paid" prop="amount" required>
                            <el-input v-model="ruleForm.amount"></el-input>
                        </el-form-item>
                    </div>
                    <div class="col-md-4 mb-3">
                        <el-form-item label="Local Coordinator" prop="user">
                            <el-input v-model="user.name" readonly></el-input>
                        </el-form-item>
                    </div>
                </div>
            </el-form>
        </div>
    </div>

    <el-button v-if="data.payment_status != 'Paid' && data.payment_status != 'Free'" type="primary" @click="formSubmit()">Save Payment</el-button>
</div>
</template>

<script>
export default {
    props: {
        registration: {
            required: true,
            type: String
        },
        user: {
            required: false,
            type: Object
        },
        balance: {
            required: true,
            type: Number
        },
        event: {
            required: true,
        }
    },
    data() {
        var checkAmount = (rule, value, callback) => {
            if (!value) {
                return callback(new Error('Please input the amount'));
            }

            if (isNaN(parseFloat(value))) {
                callback(new Error('Please input digits'));
            }

            if (parseFloat(value) <= 0) {
                callback(new Error('The amount is invalid'));
            }

            if (value > this.balance) {
                callback(new Error('The amount is greater than the balance.'));
            }

            setTimeout(() => {
                callback();
            }, 1000);
        };
        return {
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                },
                shortcuts: [{
                    text: 'Today',
                    onClick(picker) {
                    picker.$emit('pick', new Date());
                    }
                }, {
                    text: 'Yesterday',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24);
                    picker.$emit('pick', date);
                    }
                }, {
                    text: 'A week ago',
                    onClick(picker) {
                    const date = new Date();
                    date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                    picker.$emit('pick', date);
                    }
                }]
            },
            data: JSON.parse(this.registration),
            ruleForm: {
                date: '',
                amount: null
            },
            rules: {
                date: [
                    { required: true, message: 'Please input payment date', trigger: 'blur'}
                ],
                amount: [
                    { validator: checkAmount, trigger: 'blur' }
                ],
            }
        }
    },
    methods: {
        formSubmit(){
            this.$refs['ruleForm'].validate(async (valid) => {
                const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });

                if (valid) {
                    setTimeout(async () => {
                        await axios.post(`/payments/${this.data.id}`, this.ruleForm)
                        .then(async (response) => {
                            loading.close()
                            
                            this.$refs['ruleForm'].resetFields();

                            this.$alert('', 'Payment Successfully Added!', {
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
                } else 
                    loading.close()
            })
        },
        viewDetails() {
          window.location.href = `/${this.event.slug}/registration/${this.data.id}/edit`;
        },
    }
}
</script>

<style scoped>
.el-date-editor.el-input, .el-date-editor.el-input__inner {
    width: 100% !important;
}
</style>