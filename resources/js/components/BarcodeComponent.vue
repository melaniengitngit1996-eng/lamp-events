<template>
    <div class="text-center">
        <center>
            <qr-code :size="130" :text="uuid" name="Anniversary"></qr-code> <br>
        </center>
        <small class="text-description d-block">Registration Reference</small>
        <h3 class="d-block mb-0">{{ uuid }}</h3>
    </div>
</template>

<script>
export default {
    props: {
        uuid: {
            required: true,
            type: String
        },
    }
}
</script>

<style scoped>
.text-description {
    position: relative;
    top: -15px;
}

h3 {
    position: relative;
    top: -17px;
}
</style>