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
                    <el-form-item label="Complete Name" prop="name" required>
                        <el-input v-model="ruleForm.name"></el-input>
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
                    <el-form-item
                        class="check-name"
                        label="User Name"
                        prop="userName"
                        required
                    >
                        <el-input v-model="ruleForm.userName"></el-input>
                    </el-form-item>
                </div>
                </div>
            </div>
        </el-card>

        <el-row>
            <div class="col-md-12">
                <el-button
                    type="warning"
                    @click="submitForm('ruleForm')"
                    >Create</el-button
                >
            </div>
        </el-row>
    </el-form>
</template>

<script>
export default {
    data() {
        return {
            ruleForm: {
                name: "",
                localChurch: "",
                userName: "",
            },
            localChurches: Object.keys(window.env.cluster_groups),
            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input Complete Name",
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
                userName: [
                    {
                        required: true,
                        message: "Please input User Name",
                        trigger: ["blur", "change"],
                    },
                ]
            }
        }
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
                                `/users/mobile`,
                                this.ruleForm
                            )
                            .then(async (response) => {
                                loading.close();

                                this.$alert(
                                    "",
                                    "User Successfully Created!",
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
    },
}
</script>