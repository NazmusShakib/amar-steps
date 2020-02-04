<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">List Of Exports</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Exports</a></li>
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
                                <th>Requisition no</th>
                                <th>Requisition date</th>
                                <th>Requisition location</th>
                                <th>Buyer name</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="text-center" v-if="exports.total === 0">
                                <td colspan="6">
                                    No data to display.
                                </td>
                            </tr>

                            <tr v-else v-for="(eachExport, key) in exports.data">
                                <td>{{ exports.from + key }}</td>
                                <td>{{ eachExport.requisition_no }}</td>
                                <td>{{ eachExport.requisition_date }}</td>
                                <td>{{ eachExport.requisition_location}}</td>
                                <td>{{ eachExport.buyer_name }}</td>
                                <td>
                                    <router-link :to="{ name: 'ExportShow', params: {id: eachExport.id } }"
                                                 data-toggle="tooltip" title="Show!">
                                        <button type="button" class="btn btn-info btn-xs">Details</button>
                                    </router-link>
                                    <span class="m-r-5">|</span>
                                    <router-link :to="{ name: 'ExportUpdate', params: {id: eachExport.id } }"
                                                 data-toggle="tooltip" title="Edit!">
                                        <button class="btn btn-success btn-xs m-r-5">Update</button>
                                    </router-link>
                                    <span class="m-r-5">|</span>
                                    <a href="javascript:void(0)"
                                       data-toggle="tooltip" title="Delete!"
                                       @click="destroyExport(eachExport.id, key)"><button class="btn btn-danger btn-xs ">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <vue-pagination :pagination="exports" v-if="exports.total >= 11"
                                        @paginate="getExports()"
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

    import { MessageBox } from 'element-ui'

    export default {
        name: 'ListOfExports',
        components: {
            VuePagination
        },
        data: () => ({
            exports: {
                total: 0,
                per_page: 1,
                from: 1,
                to: 0,
                current_page: 1,
            },
            offset: 4,
        }),
        mounted: function () {
            this.getExports();
        },
        methods: {
            getExports() {
                axios.get('/api/v1/exports?page=' + this.exports.current_page)
                    .then((response) => {
                        this.exports = response.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here.');
                    });
            },
            destroyExport(id, index) {
                MessageBox.confirm('This will permanently delete. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/v1/exports/' + id)
                        .then(response => {
                            this.exports.data.splice(index, 1);
                            this.$notification.success(response.data.message);
                        })
                        .catch(error => {
                            console.log("Could not delete this export.");
                        });
                }).catch(() => {
                    console.log("Delete canceled");
                });
            }
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>


<style scoped>

</style>
