<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Import Details</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Imports</a></li>
                    <li class="active">Show</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xs-12">
                <div class="white-box">
                    <ul class="basic-list">
                        <li><b>Requisition No. </b><span class="pull-right">{{ imports.requisition_no }}</span></li>
                        <li><b>Requisition Location </b><span class="pull-right">{{ imports.requisition_location }}</span></li>
                        <li><b>Transport Name </b> <span class="pull-right">{{ imports.transport_name }}</span></li>
                        <li><b>Requisition Date </b><span class="pull-right">{{ imports.requisition_date }}</span></li>
                        <li><b>Consignee Name </b><span class="pull-right">{{ imports.consignee_name }}</span></li>
                        <li><b>LC No. </b><span class="pull-right">{{ imports.lc_no }}</span></li>
                        <li><b>LC Date </b><span class="pull-right">{{ imports.lc_date }}</span></li>
                        <li><b>Item </b><span class="pull-right">{{ imports.items }}</span></li>
                        <li><b>Quantity </b><span class="pull-right">{{ imports.qty }}</span></li>
                        <li><b>No. Of Box </b><span class="pull-right">{{ imports.no_of_box }}</span></li>
                        <li><b>No. Of Drum </b><span class="pull-right">{{ imports.no_of_drum }}</span></li>
                        <li><b>No. Of Jar </b><span class="pull-right">{{ imports.no_of_jar }}</span></li>
                        <li><b>No. Of Bag </b><span class="pull-right">{{ imports.no_of_bag }}</span></li>
                        <li><b>No. Of Roll </b><span class="pull-right">{{ imports.no_of_roll }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6 col-md-6 col-xs-12">
                <div class="white-box">
                    <ul class="basic-list">
                        <li><b>No. Of Bell </b><span class="pull-right">{{ imports.no_of_bell }}</span></li>
                        <li><b>No. Of CMB </b><span class="pull-right">{{ imports.no_of_cbm }}</span></li>
                        <li><b>CNF Name </b><span class="pull-right">{{ imports.cnf_name }}</span></li>
                        <li><b>Jetty Sorkar Name </b><span class="pull-right">{{ imports.jetty_sorkar_name }}</span></li>
                        <li><b>Jetty Sorkar Cell No. </b><span class="pull-right">{{ imports.jetty_sorkar_cell_no }}</span></li>
                        <li><b>Load Time</b><span class="pull-right">{{ imports.load_time }}</span></li>
                        <li><b>Get Out Time </b><span class="pull-right">{{ imports.get_out_time }}</span></li>
                        <li><b>Storage Location </b><span class="pull-right">{{ imports.storage_location }}</span></li>
                        <li><b>Store Concern Name </b><span class="pull-right">{{ imports.store_concern_name }}</span></li>
                        <li><b>Store Concern Cell No. </b><span class="pull-right">{{ imports.store_concern_cell_no }}</span></li>
                        <li><b>No. Of Van </b><span class="pull-right">{{ imports.no_of_van }}</span></li>
                        <li><b>Fare </b><span class="pull-right">{{ imports.fare }}</span></li>
                        <li><b>Cover Van No. </b><span class="pull-right">{{ imports.cover_van_no }}</span></li>
                        <li><b>Cover Van Capacity </b><span class="pull-right">{{ imports.cover_van_capacity }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from '~/components/layouts/MasterLayoutComponent.vue';

    export default {
        name: 'ImportsShow',
        components: {
            //
        },
        data: () => ({
            imports: {}
        }),
        mounted: function () {
            this.importsShow(this.$route.params.id);
        },
        methods: {
            importsShow(id) {
                axios.get('/api/v1/imports/' + id)
                    .then((response) => {
                        this.imports = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>

