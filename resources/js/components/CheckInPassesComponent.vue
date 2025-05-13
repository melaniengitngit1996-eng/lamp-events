<template>
    <div class="row justify-content-center my-4">
        <div class="col-md-6 col-lg-4 mb-4" v-for="(date, i) in passes" :key="i">
            <el-card class="box-card add-clip" :style="`border-bottom: 10px solid ${getImage(date.slot_id).color}`">
                <div class="row">
                    <div class="col-md-4">
                        <center>
                        <img :src="`/images/${getImage(date.slot_id).image}`" class="my-3" width="100%">
                        </center>
                    </div>
                    <div class="col-md-7">
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
                                {{ $func.formatToDateTime(date.created_at) }}&nbsp;&nbsp;<el-tag size="mini">{{ date.notes }}</el-tag>
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
        }
    },
    methods: {
        getImage(id) {
            if ([1,5].includes(id))
                return {
                    'image': 'day_1.png',
                    'color': '#a44fa4'
                }
            if ([2,6].includes(id))
                return {
                    'image': 'day_2.png',
                    'color': '#de0e3f'
                }
            if ([3,7].includes(id))
                return {
                    'image': 'day_3.png',
                    'color': '#3f9eff'
                }
            if ([4,8].includes(id))
                return {
                    'image': 'day_4.png',
                    'color': 'rgb(44 159 104)'
                }
        }
    }
}
</script>

<style>
.add-clip {
    clip-path: polygon(0% 0%, 100% 0%, 100% 40%, 99% 41%, 98% 42%, 97% 44%, 96% 48%, 96% 50%, 96% 52%, 97% 56%, 98% 58%, 99% 59%, 100% 60%, 100% 100%, 0% 100%, 0% 60%, 1% 59%, 2% 58%, 3% 56%, 4% 52%, 4% 50%, 4% 48%, 3% 44%, 2% 42%, 1% 41%, 0% 40%);
}
</style>