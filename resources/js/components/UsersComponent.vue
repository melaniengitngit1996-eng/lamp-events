<template>
    <div class="row justify-content-center">
        <div class="col-md-24">
            <el-table
                :data="tableData.data"
                class="mb-3"
                size="mini"
                border
                style="width: 100%">
                <el-table-column>
                    <template slot="header" slot-scope="scope">
                        <table class="w-100">
                            <tr style="background-color: #f5f7fa;">
                                <td width="250">
                                    <small>Search by Name</small>
                                    <input type="hidden" name="type" value="lookup" />
                                    <el-input
                                        clearable
                                        v-model="search"
                                        size="mini"
                                        name="search"
                                        placeholder="Type to search"
                                        @clear="fetchUsers()"/>
                                </td>
                                <td>
                                    <br />
                                    <el-button @click="fetchUsers()" size="mini" type="primary">Search</el-button>
                                </td>
                                <td v-if="permissions.can_manage_users">
                                    <br />
                                        <el-button @click="createUser()" type="success" class="float-end" size="mini">Add User</el-button>
                                    </el-popover>
                                </td>
                            </tr>
                        </table>
                    </template>
                    <el-table-column
                    prop="name"
                    label="Name"
                    width="300">
                    </el-table-column>
                    <el-table-column
                    prop="masked_email"
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
                        v-if="permissions.can_manage_users"
                        label="Actions"
                        width="300"
                        align="center">
                        <template slot-scope="scope">
                            <el-row class="text-center">
                                <el-button type="primary" plain size="small" @click="manageUser(scope.row)"><i class="el-icon-s-tools mr-2"></i>&nbsp;&nbsp;Manage</el-button>
                                <el-button type="danger" plain size="small" @click="deleteUser(scope.row.id)"><i class="el-icon-delete-solid mr-2"></i>&nbsp;&nbsp;Delete</el-button>
                            </el-row>
                        </template>
                    </el-table-column>
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
                title="User Details"
                :visible.sync="userDialog"
                width="60%"
                custom-class="user-details-dialog">
                <el-tabs type="border-card" v-model="selected">
                    <el-tab-pane label="Credentials" name="credentials">
                        <div class="row">
                            <div class="col-md-6">
                                <el-form ref="form" :model="form" label-width="120px" size="mini">
                                    <el-form-item label="Full Name">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>
                                    <el-form-item label="Email Address">
                                        <el-input v-model="form.email"></el-input>
                                    </el-form-item>
                                    <el-form-item label="Password" :gap="20">
                                        <div class="row">
                                            <el-col :span="12">
                                                <el-input type="password" v-model="form.password" placeholder="New Password"></el-input>
                                            </el-col>
                                            <el-col :span="12">
                                                <el-input type="password" v-model="form.confirm_password" placeholder="Confirm Password"></el-input>
                                            </el-col>
                                        </div>
                                    </el-form-item>
                                
                                    <span slot="footer" class="dialog-footer">
                                        <el-button @click="userDialog = false">Cancel</el-button>
                                        <el-button type="primary" @click="onSubmit">Confirm</el-button>
                                    </span>
                                </el-form>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane label="Events" name="events">
                        <el-checkbox-group v-model="form.events" class="mt-3">
                            <div class="row" v-for="(event, index) in events">
                                <el-col :span="12">
                                    <el-checkbox :value="event.id" :label="event.id">
                                        <div style="
                                            display: grid;
                                            margin-bottom: 15px;">
                                            <label>{{ event.name }}</label>
                                            <small>{{ event.description }}</small>
                                        </div>
                                    </el-checkbox>
                                </el-col>
                            </div>
                        </el-checkbox-group>
                    </el-tab-pane>
                    <el-tab-pane label="Permissions" name="permissions">
                        <el-checkbox-group v-model="form.permissions" class="mt-3">
                            <div class="row" v-for="(event, index) in permissions_config">
                                <el-col :span="12">
                                    <el-checkbox :value="event.id" :label="event.id">
                                        <div style="
                                            display: grid;
                                            margin-bottom: 15px;">
                                            <label>{{ event.label }}</label>
                                            <small>{{ event.descriptions }}</small>
                                        </div>
                                    </el-checkbox>
                                </el-col>
                            </div>
                        </el-checkbox-group>
                    </el-tab-pane>
                </el-tabs>
                <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="userDialog = false">Cancel</el-button>
                    <el-button size="mini" type="primary" :loading="form.loading" @click="onSubmit">Save Changes</el-button>
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
            form: {
                id: 0,
                name: '',
                email: '',
                password: '',
                confirm_password: '',
                events: [],
                permissions: [],
                loading: false
            },
            tableData: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                current_page: 1,
                data: []
            },
            events: [],
            selected: "events",
            userDialog: false,
            permissions_config: window.env.permissions,
            permissions: window.auth_user.permissions,
        }
    },
    mounted() {
        this.fetchUsers();
        this.fetchEvents();
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
        fetchEvents() {
            axios
            .get(`/events/all`)
            .then(async response => {
                this.events = response.data;
            })
            .catch(error => {
                this.$notify.error({
                    title: error
                });
            });
        },
        async manageUser(data) {
            this.form.name = data.name;
            this.form.email = data.email;

            let result = []
            
            await data.event_permission.forEach(event => {
                result.push(event.event_id)
            });

            this.form.events = result

            var excludedKeys = ['id', 'user_id', 'created_at', 'updated_at'];

            var permissions = Object.entries(data.permissions)
            .filter(([key, value]) => value === true && !excludedKeys.includes(key))
            .map(([key]) => key);

            this.form.permissions = permissions;

            this.form.id = data.id;

            this.userDialog = true
        },
        async createUser() {
            this.form = {
                id: 0,
                name: '',
                email: '',
                password: '',
                confirm_password: '',
                events: [],
                permissions: [],
                loading: false
            }

            this.userDialog = true
        },
        deleteUser(id) {
            this.$confirm(`Are you sure you want to delete this user?`, 'Warning', {
                customClass: 'prompt-message',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(async () => {
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });

            setTimeout(async () => {
                await axios.delete(`/users/${id}/delete`)
                .then(async (response) => {
                loading.close();
                
                this.$alert('', 'User Successfully Deleted!', {
                    confirmButtonText: 'OK',
                    showCancelButton: false,
                    closeOnPressEscape: false,
                    closeOnClickModal: false,
                    showClose: false,
                    center: true,
                    type: 'success',
                    callback: action => {
                        this.fetchUsers();
                    }
                });
                })
            }, 1000);
            })
        },
        resetForm() {
            this.form.id = 0;
            this.form.name = '';
            this.form.email = '';
            this.form.password = '';
            this.form.confirm_password = '';
            this.form.events = [];
            this.form.permissions = [];
            this.form.loading = false;

            this.userDialog = false
        },
        onSubmit() {
            this.form.loading = true;

            var url = `/users`;

            if (this.form.id) {
                url = `/users/${this.form.id}`;
            }
            
            axios
            .post(url, this.form)
            .then(async response => {
                this.resetForm();
                this.$alert('', 'User successfully created!', {
                    confirmButtonText: 'OK',
                    showCancelButton: false,
                    closeOnPressEscape: false,
                    closeOnClickModal: false,
                    showClose: false,
                    center: true,
                    type: 'success',
                    callback: action => {
                        this.fetchUsers();
                    }
                });
            })
            .catch(error => {
                this.form.loading = false;
                this.$notify.error({
                    title: error
                });
            });
        }
    }
}
</script>

<style>
.user-details-dialog {
    margin-top: 5vh !important;
}

.user-details-dialog .el-dialog__body {
    padding: 10px 20px !important;

}
</style>