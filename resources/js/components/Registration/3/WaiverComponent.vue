<template>
    <el-dialog
        :visible.sync="show"
        custom-class="responsive-dialog"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
        :destroy-on-close="false">
        <span class="content">
            <div class="m-0">
                <section class="waiver-section">
                    <h3 style="text-align: left;">LAMP CHURCH MUNTINLUPA FAMILY CAMP 2026 WAIVER FORM</h3>

                    <div class="waiver-box">
                        <p>
                            I am aware of the risks connected with attending the <strong>Family Survival Camp</strong> 
                            at <strong>Yvanders Nature’s Camp, Nasugbu, Batangas</strong> on 
                            <strong>April 2–5, 2026</strong>.
                        </p>

                        <p>
                            I agree not to hold accountable or take legal action against the church pastor or 
                            members of the organizing committee. This waiver releases them from any liability 
                            relating to injuries that may occur on location before, during, or after the activity.
                        </p>

                        <p>
                            By agreeing to this waiver, I hold <strong>LAMP Church Muntinlupa</strong>, its pastor, 
                            and members of the committee entirely free from any liability, including financial 
                            responsibility for injuries incurred, regardless of the cause or circumstances.
                        </p>

                        <p>
                            I certify that my participation is voluntary, that I have been informed of the risks, 
                            and that I do not have any medical or physical condition that may increase the likelihood 
                            of injury. I agree to follow all safety rules and instructions during the activity.
                        </p>
                    </div>

                    <el-checkbox v-model="isAllowing"/>&nbsp;&nbsp;I have read, understood, and agree to the waiver stated above.
                    <span v-if="display" class="error">
                        Please check if you want to continue
                    </span>
                </section>
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
        }
    },
    props: {
        show: {
            required: false
        },
    },
    methods: {
        validateAuthorization() {
            if (!this.isAllowing)
                this.display = true
            else
                this.$emit("continue");
        }
    }
 }
</script>

<style>
.waiver-box {
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  background: #fafafa;
  font-size: 14px;
  margin-bottom: 10px
}

.waiver-checkbox {
  display: block;
  margin-top: 12px;
  font-weight: 500;
}
</style>