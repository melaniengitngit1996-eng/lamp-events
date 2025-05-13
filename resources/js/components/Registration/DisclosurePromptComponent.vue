<template>
    <el-dialog
        :visible.sync="dialogVisible"
        custom-class="responsive-dialog"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
        :destroy-on-close="false">
        <span class="content">
            <div class="m-0">
                <el-checkbox v-model="isAllowing"/>&nbsp;&nbsp;I authorize LAMP Church to collect, use, and retain the personal information in this form for the purpose of attending {{event.name}} and in the pursuit of any legal interests of the church.
                <span v-if="display" class="error">
                    Please check if you want to continue
                </span>
            </div>
        </span>
        <span slot="footer" class="dialog-footer">
            <el-button @click="validateAuthorization()">Continue</el-button>
        </span>
    </el-dialog>
</template>

<script>
 export default {
    data() {
        return {
            isAllowing: false,
            display: false,
            dialogVisible: true,
        }
    },
    props: {
            event: {
                required: false
            },
        },
    mounted() {
        console.log('test');
    },
    methods: {
        validateAuthorization() {
            if (!this.isAllowing)
                this.display = true
            else
                this.dialogVisible = false
        }
    }
 }
</script>