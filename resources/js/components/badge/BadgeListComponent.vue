<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Badge list</h4>
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
                                @click="dialogVisible = !dialogVisible"
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
                                <th>Unit</th>
                                <th>Target</th>
                                <th>Icon</th>
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
                                <td>{{ badge.unit.short_name }}</td>
                                <td>{{ badge.target || 'n/a'}}</td>
                                <td>
                                    <img :src="'/images/badges/thumb/thumb_200x200_' + badge.badge_icon"
                                         alt="badge_icon" height="30" width="30"/>
                                </td>
                                <td>
                                    <a
                                        href="javascript:void(0)"
                                        data-toggle="tooltip"
                                        title="Edit"
                                        @click="editBadge(badge, index)"
                                    >
                                        <i class="fa fa-edit m-r-5"></i>
                                    </a>
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
                            v-if="badges.total >= badges.per_page"
                            @paginate="getBadges()"
                            :offset="4"
                        ></vue-pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <badge-modal :dialog-visible.sync="dialogVisible" :dialog-title.sync="dialogTitle"></badge-modal>
    </div>
</template>

<script>
    import MasterLayout from "~/components/layouts/MasterLayoutComponent";
    import VuePagination from "~/components/partials/_PaginationComponent";
    import BadgeModal from "~/components/badge/_BadgeModalComponent";

    import {MessageBox} from "element-ui";

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
            dialogVisible: false,
            dialogTitle: "Add a new badge",
        }),
        mounted: function () {
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
                                this.badges.total--;
                                this.$notification.success(response.data.message);
                            })
                            .catch(error => {
                                console.log("Could not delete this badge.");
                            });
                    })
                    .catch(() => {
                        console.log("Delete canceled");
                    });
            },
            editBadge(badge, index) {
                this.$eventBus.$emit('edit-badge', badge);
            }
        },
        created() {
            this.$emit("update:layout", MasterLayout);
            this.$eventBus.$on("add-badge", badge => {
                this.badges.total++;
                this.badges.data.unshift(badge);
            });
            this.$eventBus.$on("reload-badges", () => {
                this.getBadges();
            });
        }
    };
</script>


<style scoped>
</style>
