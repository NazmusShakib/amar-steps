<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">List of badges</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="#">Badge</a>
                    </li>
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
                                    <th>Name</th>
                                    <th>Display name</th>
                                    <th>Description</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="badges.total === 0">
                                    <td colspan="6">No data to display.</td>
                                </tr>
                                <tr v-else v-for="badge in badges.data" v-bind:key="badge">
                                    <td>{{ badges.from + key }}</td>
                                    <td>{{ badge.name }}</td>
                                    <td>{{ badge.display_name }}</td>
                                    <td>{{ badge.description }}</td>
                                    <td>
                                        <a href="#">
                                            <button
                                                type="button"
                                                class="btn btn-info btn-xs"
                                            >Details</button>
                                        </a>
                                        <span class="m-r-5">|</span>
                                        <router-link
                                            :to="{ name: 'BadgeUpdate', params: {id: badge.id } }"
                                            data-toggle="tooltip"
                                            title="Edit!"
                                        >
                                            <button class="btn btn-success btn-xs m-r-5">Update</button>
                                        </router-link>
                                        <span class="m-r-5">|</span>
                                        <a
                                            href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            title="Delete!"
                                            @click="destroyBadge(badge.id, key)"
                                        >
                                            <button class="btn btn-danger btn-xs">Delete</button>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <vue-pagination
                            :pagination="badges"
                            v-if="badges.total >= 11"
                            @paginate="getBadges()"
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

import { MessageBox } from "element-ui";

export default {
    name: "BadgeList",
    components: {
        VuePagination
    },
    data: () => ({
        badges: {
            total: 0,
            per_page: 1,
            from: 1,
            to: 0,
            current_page: 1
        },
        offset: 4
    }),
    mounted: function() {
        this.getBadges();
    },
    methods: {
        getBadges() {
            axios
                .get(this.$baseURL + "badges?page=" + this.badges.current_page)
                .then(response => {
                    this.badges = response.data;
                })
                .catch(() => {
                    console.log("handle server error from here.");
                });
        },
        destroyBadge(id, index) {
            MessageBox.confirm(
                "This will permanently delete. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning"
                }
            )
                .then(() => {
                    axios
                        .delete(this.$baseURL + "badges/" + id)
                        .then(response => {
                            this.exports.data.splice(index, 1);
                            this.$notification.success(response.data.message);
                        })
                        .catch(error => {
                            console.log("Could not delete this export.");
                        });
                })
                .catch(() => {
                    console.log("Delete canceled");
                });
        }
    },
    created() {
        this.$emit("update:layout", MasterLayout);
    }
};
</script>


<style scoped>
</style>
