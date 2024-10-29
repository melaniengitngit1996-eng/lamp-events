<template>
    <el-card shadow="always" class="mb-3 pb-0" style="border-top: 10px solid rgb(45 122 95)">
        <h2>LAMP WORLDWIDE AWTA {{year}}</h2>
        <p class="text-sm">
            BE BLESSED PHYSICALLY, MATERIALLY, & SPIRITUALLY <br/>
            Event Date: {{ event_date }} <br/>
            Event Place: Calamba Tent <br/>
            Theme: {{ theme }}
        </p>

        <p class="text-sm mb-0">
            Chosen people of God in the Old Testament gather for a so-called solemn assembly (Leviticus 23:36, Joel 1:14) where "offering made by fire unto the Lord" are given to celebrate God. But with Christ's death as ultimate sacrifice for all, today, animal sacrifices are no longer offered. Yet true worshipers of God continue to offer & make fire in the form of praise, worship & thanksgiving. <br/><br/>

            Annually, LAMP Church gathers & invites every one to congregate for one purpose -- offer worship & thanksgiving to the Lord of lords!
        </p>
    </el-card>
</template>

<script>
export default {
    data() {
        return {
            year: window.env.year,
            theme: window.env.theme,
            event_date: window.env.event_date
        }
    }
}
</script>

<style scoped>
.text-sm {
    font-size: 13px;
}
</style>