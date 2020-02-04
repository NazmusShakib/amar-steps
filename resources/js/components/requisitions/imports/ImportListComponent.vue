<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">List Of Imports</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Imports</a></li>
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
                                <th>Consignee name</th>
                                <th class="text-nowrap">Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr class="text-center" v-if="imports.total === 0">
                                <td colspan="6">
                                    No data to display.
                                </td>
                            </tr>

                            <tr v-else v-for="(eachImports, key) in imports.data">
                                <td>{{ imports.from + key }}</td>
                                <td>{{ eachImports.requisition_no }}</td>
                                <td>{{ eachImports.requisition_date }}</td>
                                <td>{{ eachImports.requisition_location}}</td>
                                <td>{{ eachImports.consignee_name }}</td>
                                <td>
                                    <router-link :to="{ name: 'ImportShow', params: {id: eachImports.id } }"
                                                 data-toggle="tooltip" title="Show!">
                                        <button type="button" class="btn btn-info btn-xs">Details</button>
                                    </router-link>
                                    <span class="m-r-5">|</span>
                                    <router-link :to="{ name: 'ImportUpdate', params: {id: eachImports.id } }"
                                                 data-toggle="tooltip" title="Edit!">
                                        <button type="button" class="btn btn-success btn-xs">Update</button>
                                    </router-link>
                                    <span class="m-r-5">|</span>
                                    <a href="javascript:void(0)"
                                       data-toggle="tooltip" title="Delete!"
                                       @click="destroyImport(eachImports.id, key)"><button type="button" class="btn btn-danger btn-xs">Delete</button>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <vue-pagination :pagination="imports" v-if="imports.total >= 11"
                                        @paginate="getImports()"
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
        name: 'ListOfImports',
        components: {
            VuePagination
        },
        data: () => ({
            imports: {
                total: 0,
                per_page: 1,
                from: 1,
                to: 0,
                current_page: 1,
            },
            offset: 4,
        }),
        mounted: function () {
            this.getImports();
        },
        methods: {
            getImports() {
                axios.get('/api/v1/imports?page=' + this.imports.current_page)
                    .then((response) => {
                        this.imports = response.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here.');
                    });
            },
            /*showImport(id) {
                axios.get('/api/v1/imports/' + id)
                    .then((response) => {
                        this.imports = response.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },*/
            destroyImport(id, index) {
                MessageBox.confirm('This will permanently delete. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.delete('/api/v1/imports/' + id)
                        .then(response => {
                            this.imports.data.splice(index, 1);
                            this.$notification.success(response.data.message);
                        })
                        .catch(error => {
                            console.log("Could not delete this import.");
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
