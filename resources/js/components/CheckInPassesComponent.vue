<template>
    <div class="row justify-content-center my-4">
        <div class="col-md-6 col-lg-4 mb-4" v-for="(date, i) in passes" :key="i">
            <el-card class="box-card add-clip" :style="`border-bottom: 10px solid ${event.border_color}`">
                <div class="row">
                    <div class="col-md-5 p-4">
                        <center>
                            <svg  width="80%" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Calendar icon June 5">
                                <!-- Outer rounded background -->
                                <rect x="0" y="0" width="256" height="256" rx="36" ry="36" fill="#e9e9e9"/>

                                <!-- Top red bar (rounded only on top corners) -->
                                <path d="M0,36
                                        a36,36 0 0 1 36,-36
                                        h184
                                        a36,36 0 0 1 36,36
                                        v56
                                        h-256
                                        z" 
                                        :fill="event.border_color"/>

                                <!-- "JUN" text -->
                                <text x="50%" y="56" text-anchor="middle" dominant-baseline="middle"
                                        font-family="Arial, Helvetica, sans-serif" font-weight="700" font-size="36" fill="#ffffff" letter-spacing="2">
                                    {{ date.slot.month }}
                                </text>

                                <!-- Day number -->
                                <text x="50%" y="165" text-anchor="middle" dominant-baseline="middle"
                                        font-family="Arial, Helvetica, sans-serif" font-weight="700" font-size="120" fill="#333333">
                                    {{ date.slot.day }}
                                </text>
                            </svg>
                        </center>
                    </div>
                    <div class="col-md-6">
                        <el-descriptions class="margin-top" :column="1" size="mini" border>
                            <el-descriptions-item>
                                <template slot="label">
                                    <i class="el-icon-bank-card"></i>
                                </template>
                                {{ date.registration.uuid }}
                            </el-descriptions-item>
                            <el-descriptions-item>
                                <template slot="label">
                                    <i class="el-icon-user"></i>
                                </template>
                                {{ date.registration.fullname }}
                            </el-descriptions-item>
                            <el-descriptions-item>
                                <template slot="label">
                                    <i class="el-icon-location-outline"></i>
                                </template>
                                {{ date.registration.local_church }} - {{ date.registration.cluster_group }}
                            </el-descriptions-item>
                            <el-descriptions-item>
                                <template slot="label">
                                    <i class="el-icon-date"></i>
                                </template>
                                {{ date.created_at_formatted }}&nbsp;&nbsp;<el-tag size="mini">{{ date.notes }}</el-tag>
                            </el-descriptions-item>
                        </el-descriptions>
                    </div>
                </div>
            </el-card>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        passes: {
            required: true,
            type: Array
        },
        event: {
            required: true,
            type: Object
        }
    }
}
</script>

<style>
.add-clip {
    clip-path: polygon(0% 0%, 100% 0%, 100% 40%, 99% 41%, 98% 42%, 97% 44%, 96% 48%, 96% 50%, 96% 52%, 97% 56%, 98% 58%, 99% 59%, 100% 60%, 100% 100%, 0% 100%, 0% 60%, 1% 59%, 2% 58%, 3% 56%, 4% 52%, 4% 50%, 4% 48%, 3% 44%, 2% 42%, 1% 41%, 0% 40%);
}
</style>