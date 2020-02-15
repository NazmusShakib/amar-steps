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
                    <div class="row">
                        <div class="col-md-10 col-lg-10 col-sm-12">
                            <el-input
                                placeholder="Search input"
                                v-model="searchText"
                                @keyup.native="filterTableData"
                                suffix-icon="el-icon-search"
                                size="small"
                            />
                        </div>
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <el-button
                                type="primary"
                                class="pull-right"
                                size="small"
                                @click="showDialog = !showDialog"
                            >
                                <i class="el-icon-plus el-icon-right"></i> Add new
                            </el-button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Name</th>
                                    <th>Display name</th>
                                    <th>Target</th>
                                    <th>Description</th>
                                    <th class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center" v-if="badges.total === 0">
                                    <td colspan="6">No data to display.</td>
                                </tr>
                                <tr v-else v-for="(badge, index) in badges.data" v-bind:key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ badge.name }}</td>
                                    <td>{{ badge.display_name }}</td>
                                    <td>{{ badge.target }}</td>
                                    <td>{{ badge.description }}</td>
                                    <td>
                                        <router-link
                                            :to="{ name: 'BadgeUpdate', params: {id: badge.id } }"
                                            data-toggle="tooltip"
                                            title="Edit"
                                        >
                                            <i class="fa fa-edit m-r-5"></i>
                                        </router-link>
                                        <span class="m-r-5">|</span>
                                        <a
                                            href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            title="Delete"
                                            @click="destroyBadge(badge.id, index)"
                                        >
                                            <i class="fa fa-trash-o m-r-5"></i>
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
                        > </vue-pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <badge-modal :show-dialog.sync="showDialog" :dialog-title.sync="dialogTitle"> </badge-modal>
    </div>
</template>

<script>
import MasterLayout from "~/components/layouts/MasterLayoutComponent";
import VuePagination from "~/components/partials/_PaginationComponent";
import BadgeModal from "~/components/badge/_BadgeModalComponent";

import { MessageBox } from "element-ui";

export default {
    name: "BadgeList",
    components: {
        VuePagination,
        BadgeModal
    },
    data: () => ({
        badges: {
            total: 0,
            per_page: 1,
            from: 1,
            to: 0,
            current_page: 1
        },
        offset: 4,
        searchText: "",
        dialogTitle: "Add a new badge",
        showDialog: false
    }),
    mounted: function() {
        this.getBadges();
    },
    methods: {
        getBadges() {
            axios.get(this.$baseURL + "badges?page=" + this.badges.current_page)
                .then(response => {
                    this.badges = response.data;
                })
                .catch(() => {
                    console.log("handle server error from here.");
                });
        },
        filterTableData() {
            //
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
                    axios.delete(this.$baseURL + "badges/" + id)
                        .then(response => {
                            this.badges.data.splice(index, 1);
                            this.$notification.success(response.data.message);
                        })
                        .catch(error => {
                            console.log("Could not delete this badge.");
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
