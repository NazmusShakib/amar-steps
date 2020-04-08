<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Dashboard</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="row row-in">
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <i data-icon="M" class="linea-icon linea-elaborate"></i>
                                    <h5 class="text-muted vb">Total Subscriber</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-danger">--</h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <i class="linea-icon linea-basic" data-icon="M"></i>
                                    <h5 class="text-muted vb">Total <br>Staff</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-megna">
                                        --
                                    </h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-primary" role="progressbar"
                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 row-in-br">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <i class="linea-icon linea-basic" data-icon="M"></i>
                                    <h5 class="text-muted vb">Numbers of Staff</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-primary">
                                        --
                                    </h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-megna" role="progressbar"
                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6  b-0">
                            <div class="col-in row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <i class="linea-icon linea-basic" data-icon="A"></i>
                                    <h5 class="text-muted vb">Online Subscribers</h5>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="counter text-right m-t-15 text-success">
                                        --
                                    </h3>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success" role="progressbar"
                                             aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                             style="width: 100%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--row -->
        <!-- /.row -->
        <!--<div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Yearly Transection</h3>
                    <ul class="list-inline text-right">
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>Manager</h5></li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #fb9678;"></i>Staff</h5></li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #9675ce;"></i>Workers</h5></li>
                    </ul>
                    <div id="morris-area-chart" style="height: 340px; width: 100%"></div>
                </div>
            </div>
        </div>-->
        <!--row -->
        <!--<div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Recent sales
                        <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
                            <select class="form-control pull-right row b-none">
                                <option>March 2017</option>
                                <option>April 2017</option>
                                <option>May 2017</option>
                                <option>June 2017</option>
                                <option>July 2017</option>
                            </select>
                        </div>
                    </h3>
                    <div class="row sales-report">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <h2>March 2017</h2>
                            <p>TRANSECTION REPORT</p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6 ">
                            <h1 class="text-right text-success m-t-20">$3,690</h1></div>
                    </div>
                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                            <tr>
                                <th>Staff NAME</th>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>TOTAL AMOUNT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="txt-oflo">Sfaff admin</td>
                                <td><span class="label label-megna label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 18</td>
                                <td><span class="text-success">$24</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Real Homes</td>
                                <td><span class="label label-info label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 19</td>
                                <td><span class="text-success">$1250</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Medical Pro</td>
                                <td><span class="label label-danger label-rounded">INACTIVE</span></td>
                                <td class="txt-oflo">April 20</td>
                                <td><span class="text-danger">-$0</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Hosting press</td>
                                <td><span class="label label-megna label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 21</td>
                                <td><span class="text-success">$29000</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Helping Hands</td>
                                <td><span class="label label-success label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 22</td>
                                <td><span class="text-success">$2400</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Digital Agency</td>
                                <td><span class="label label-megna label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 23</td>
                                <td><span class="text-success">$14000</span></td>
                            </tr>
                            <tr>
                                <td class="txt-oflo">Helping Hands</td>
                                <td><span class="label label-success label-rounded">ACTIVE</span></td>
                                <td class="txt-oflo">April 22</td>
                                <td><span class="text-success">$640000</span></td>
                            </tr>
                            </tbody>
                        </table>
                        <a href="#">Check all the sales</a></div>
                </div>
            </div>
        </div>-->
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from './layouts/MasterLayoutComponent.vue';

    export default {
        name: 'Dashboard',
        components: {
            //
        },
        data: () => ({
            //
        }),
        mounted: function () {
            // console.log('Dashboard component mounted.');

            if(!this.$localStorage.get('reloaded')) {
                this.$localStorage.set('reloaded', '1');
                location.reload();
            }

        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>
