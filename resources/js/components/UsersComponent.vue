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
                label="Name"
                width="300">
                </el-table-column>
                <el-table-column
                prop="email"
                label="Email"
                width="300">
                </el-table-column>
                <el-table-column
                label="Permissions">
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
                <el-table-column
                    label="Actions"
                    width="300"
                    align="center">
                    <template slot-scope="scope">
                        <el-row class="text-center">
                            <el-button type="primary" plain size="small" @click="manageUser(scope.row.id)"><i class="el-icon-s-tools mr-2"></i>&nbsp;&nbsp;Manage</el-button>
                            <el-button type="danger" plain size="small"><i class="el-icon-delete-solid mr-2"></i>&nbsp;&nbsp;Delete</el-button>
                        </el-row>
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

            <el-dialog
                title="Tips"
                :visible.sync="userDialog"
                width="30%"
                :before-close="handleClose">
                <span>This is a message</span>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="userDialog = false">Cancel</el-button>
                    <el-button type="primary" @click="userDialog = false">Confirm</el-button>
                </span>
            </el-dialog>
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
            userDialog: false
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
        },
        manageUser(id) {
            console.log(id);
            this.userDialog = true
        }
    }
}
</script>