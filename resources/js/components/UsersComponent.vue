<template>
    <div class="row justify-content-center">
        <div class="col-md-24">
            <el-table
                :data="tableData.data"
                class="mb-3"
                size="mini"
                border
                style="width: 100%">
                <el-table-column
                prop="name"
                label="Name">
                </el-table-column>
                <el-table-column
                prop="email"
                label="Email">
                </el-table-column>
                <el-table-column
                label="Permission">
                <template slot-scope="scope">
                    <el-tag
                        class="m-1"
                        v-for="item in scope.row.event_permission"
                        :key="item.id"
                        type=""
                        effect="plain"
                        size="mini">
                        {{ item.event.name }}
                    </el-tag>
                </template>
                </el-table-column>
            </el-table>

            <pagination 
                v-if="tableData.data.length > 0"
                class="m-0"
                :pagination="tableData"
                @paginate="fetchUsers(false)"
                :offset="4">
            </pagination>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: '',
            tableData: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
                data: []
            },
        }
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers(ignore_page = true) {
            axios
            .get(`/users/all`, {
                params: {
                    search: this.search,
                    page: this.tableData.current_page,
                }
            })
            .then(async response => {
                this.tableData = response.data;
            })
            .catch(error => {
                console.log(this.tableData)
                this.$notify.error({
                    title: error
                });
            });
        }
    }
}
</script>