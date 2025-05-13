<template>
    <el-card shadow="always" class="mb-3 pb-0" :style="`border-top: 10px solid ${event.border_color}`">
        <h2>{{ event.name }}</h2>
        <div v-html="event.form_description_block"></div>
    </el-card>
</template>

<script>
export default {
    props: {
        event: {
            required: true
        }
    }
}
</script>

<style scoped>
.text-sm {
    font-size: 13px;
}
</style>