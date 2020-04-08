<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">User list</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">Users</a>
                    </li>
                    <li class="active">List</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-sm-12">

                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <el-dropdown class="pull-right">
                                <el-button type="primary" size="small">
                                    Role<i class="el-icon-arrow-down el-icon--right"></i>
                                </el-button>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item @click.native="getUsers('admin')">Admin</el-dropdown-item>
                                    <el-dropdown-item @click.native="getUsers('subscriber')">Subscriber</el-dropdown-item>
                                    <el-dropdown-item @click.native="getUsers('staff')">Staff</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-center" v-if="users.total === 0">
                                <td colspan="6">No data to display.</td>
                            </tr>
                            <tr v-else v-for="(user, index) in users.data" v-bind:key="index">
                                <td>{{ index + 1}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.phone}}</td>
                                <td>{{ user.role }}</td>
                                <td>
                                    <!--<a href="#" data-toggle="tooltip" title="Show">
                                        <i class="fa fa-eye text-info m-r-5"></i>
                                    </a>
                                    <span class="m-r-5">|</span>
                                    <a herf="#" data-toggle="tooltip" title="Edit">
                                        <i class="fa fa-edit text-info m-r-5"></i>
                                    </a>
                                    <span class="m-r-5">|</span>-->
                                    <a href="javascript:void(0)"
                                       data-toggle="tooltip" title="Delete"
                                       @click="destroy(user.id, index)"><i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <vue-pagination
                            :pagination="users"
                            v-if="users.total >= 11"
                            @paginate="getUsers(selected_role)"
                            :offset="4"
                        ></vue-pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from "~/components/layouts/MasterLayoutComponent";
    import VuePagination from "~/components/partials/_PaginationComponent";

    import {MessageBox} from 'element-ui'

    export default {
        name: "UserList",
        components: {
            VuePagination
        },
        data: () => ({
            users: {
                total: 0,
                per_page: 1,
                from: 1,
                to: 0,
                current_page: 1
            },
            offset: 4,
            selected_role: 'subscriber'
        }),
        mounted: function () {
            this.getUsers(this.selected_role);
        },
        methods: {
            getUsers(selected_role) {
                axios.get("/api/v1/users?page=" + this.users.current_page + '&role=' + selected_role)
                    .then(response => {
                        this.users = response.data;
                    })
                    .catch(() => {
                        console.log("handle server error from here");
                    });
            },
            destroy(id, index) {
                MessageBox.confirm('This will permanently delete. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/v1/users/' + id)
                        .then(response => {
                            this.users.data.splice(index, 1);
                            this.$notification.success(response.data.message);
                        })
                        .catch(error => {
                            this.$notification.error(error.response.data['message']);
                        });
                }).catch(() => {
                    console.log("Delete canceled");
                });
            }
        },
        created() {
            this.$emit("update:layout", MasterLayout);
        }
    };
</script>
