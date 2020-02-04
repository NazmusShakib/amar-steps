<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Export Details</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Exports</a></li>
                    <li class="active">Show</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xs-12">
                <div class="white-box">
                    <ul class="basic-list">
                        <li><b>Requisition No </b> <span class="pull-right">{{ exports.requisition_no }}</span></li>
                        <li><b>Requisition Date </b><span class="pull-right">{{ exports.requisition_date }}</span></li>
                        <li><b>Requisition Location </b><span class="pull-right">{{ exports.requisition_location }}</span></li>
                        <li><b>Transport Name </b><span class="pull-right">{{ exports.transport_name }}</span></li>
                        <li><b>Buyer Name </b><span class="pull-right">{{ exports.buyer_name }}</span></li>
                        <li><b>Load Point </b><span class="pull-right">{{ exports.load_point }}</span></li>
                        <li><b>Unload Point </b><span class="pull-right">{{ exports.unload_point }}</span></li>
                        <li><b>Item </b><span class="pull-right">{{ exports.items }}</span></li>
                        <li><b>Quantity </b><span class="pull-right">{{ exports.qty }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6 col-md-6 col-xs-12">
                <div class="white-box">
                    <ul class="basic-list">
                        <li><b>CMB </b><span class="pull-right">{{ exports.cbm }}</span></li>
                        <li><b>No. Of Van </b><span class="pull-right">{{ exports.no_of_van }}</span></li>
                        <li><b>Load Time </b><span class="pull-right">{{ exports.load_time }}</span></li>
                        <li><b>Unload Time </b><span class="pull-right">{{ exports.unload_time }}</span></li>
                        <li><b>Cut Off Time </b><span class="pull-right">{{ exports.cut_off_time }}</span></li>
                        <li><b>Cut Off Date </b><span class="pull-right">{{ exports.cut_off_date }}</span></li>
                        <li><b>Fare </b><span class="pull-right">{{ exports.fare }}</span></li>
                        <li><b>Cover Van No </b><span class="pull-right">{{ exports.cover_van_no }}</span></li>
                        <li><b>Cover Van Capacity </b><span class="pull-right">{{ exports.cover_van_capacity }}</span></li>
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
        name: 'ExportShow',
        components: {
            //
        },
        data: () => ({
            exports: {}
        }),
        mounted: function () {
            this.exportShow(this.$route.params.id);
        },
        methods: {
            exportShow(id) {
                axios.get('/api/v1/exports/' + id)
                    .then((response) => {
                        this.exports = response.data.data;
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

