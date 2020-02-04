<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">List Of Users</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Users</a></li>
                    <li class="active">List</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Serial</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <!--<th class="text-nowrap">Action</th>-->
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="text-center" v-if="users.total === 0">
                                <td colspan="6">
                                    No data to display.
                                </td>
                            </tr>

                            <tr v-else v-for="(user, key) in users.data">
                                <td>{{ users.from + key}}</td>
                                <td>{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.phone}}</td>
                                <td>{{ user.role }}</td>
                                <!--<td>{{ user.id }}</td>-->
                            </tr>
                            </tbody>
                        </table>

                        <vue-pagination :pagination="users" v-if="users.total >= 11"
                                        @paginate="getUsers()"
                                        :offset="4">
                        </vue-pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from '~/components/layouts/MasterLayoutComponent';
    import VuePagination from '~/components/partials/_PaginationComponent';

    export default {
        name: 'ListOfUsers',
        components: {
            VuePagination
        },
        data: () => ({
            users: {
                total: 0,
                per_page: 1,
                from: 1,
                to: 0,
                current_page: 1,
            },
            offset: 4,
        }),
        mounted: function () {
            this.getUsers();
        },
        methods: {
            getUsers() {
                axios.get('/api/v1/users?page=' + this.users.current_page)
                    .then((response) => {
                        this.users = response.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            }
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>
