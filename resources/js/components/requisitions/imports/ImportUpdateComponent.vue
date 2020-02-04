<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Import</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Imports</a></li>
                    <li class="active">Update</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <form action="#" method="POST" @submit.prevent="importUpdate()" novalidate>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Transport Name</label>
                                <input type="text" name="transport name"
                                       class="form-control"
                                       v-model.trim="imports.transport_name"
                                       v-bind:class="{'has-error' : errors.has('transport name')}"
                                       v-validate="'required'"
                                       placeholder="Enter Transport Name">
                                <div v-show="errors.has('transport name')" class="help text-danger">
                                    {{ errors.first('transport name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Requisition Location</label>
                                <select class="form-control" name="requisition location"
                                        @change="generateRequisitionNo()"
                                        v-model.trim="imports.requisition_location"
                                        v-bind:class="{'has-error' : errors.has('requisition location')}"
                                        v-validate="'required'">
                                    <option value="" selected>---Select Location---</option>
                                    <option value="MFL">Micro Fibre Ltd</option>
                                    <option value="MKL">Midland Knitwear Ltd</option>
                                    <option value="LKL">Liberty Kinwear Ltd</option>
                                    <option value="AOPL">A-One Polar Ltd</option>
                                    <option value="OCTL">Orient Chem-Tex Ltd</option>
                                    <option value="HCL">Harmony Chem Ltd</option>
                                    <option value="MTL">Micro Trims Ltd</option>
                                </select>
                                <div v-show="errors.has('requisition location')" class="help text-danger">
                                    {{ errors.first('requisition location') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Requisition No</label>
                                <input type="text" name="requisition no"
                                       class="form-control"
                                       v-model.trim="imports.requisition_no"
                                       v-bind:class="{'has-error' : errors.has('requisition no')}"
                                       v-validate="'required'" readonly="readonly"
                                       placeholder="Auto generate">
                                <div v-show="errors.has('requisition no')" class="help text-danger">
                                    {{ errors.first('requisition no') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Requisition Date</label>
                                <input type="date" name="requisition date"
                                       v-model.trim="imports.requisition_date"
                                       v-bind:class="{'has-error' : errors.has('requisition date')}"
                                       v-validate="'required'"
                                       class="form-control">
                                <div v-show="errors.has('requisition date')" class="help text-danger">
                                    {{ errors.first('requisition date') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Consignee Name</label>
                                <select class="form-control" name="consignee name"
                                        v-model.trim="imports.consignee_name"
                                        v-bind:class="{'has-error' : errors.has('consignee name')}"
                                        v-validate="'required'">
                                    <option>---Select Consignee Name---</option>
                                    <option value="MFL">Micro Fibre Ltd</option>
                                    <option value="MKL">Midland Knitwear Ltd</option>
                                    <option value="LKL">Liberty Kinwear Ltd</option>
                                    <option value="AOPL">A-One Polar Ltd</option>
                                    <option value="OCTL">Orient Chem-Tex Ltd</option>
                                    <option value="HCL">Harmony Chem Ltd</option>
                                    <option value="MTL">Micro Trims Ltd</option>
                                    <option value="TCBL">Tubingen Chem (BD)</option>
                                    <option value="STL">Sungarh Tex Ltd</option>
                                </select>
                                <div v-show="errors.has('consignee name')" class="help text-danger">
                                    {{ errors.first('consignee name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">LC No</label>
                                <input type="text" name="lc no"
                                       v-model.trim="imports.lc_no"
                                       v-bind:class="{'has-error' : errors.has('lc no')}"
                                       v-validate="'required'"
                                       class="form-control" placeholder="Enter LC No">
                                <div v-show="errors.has('lc no')" class="help text-danger">
                                    {{ errors.first('lc no') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">LC Date</label>
                                <input type="date" name="lc date"
                                       v-model.trim="imports.lc_date"
                                       v-bind:class="{'has-error' : errors.has('lc date')}"
                                       v-validate="'required'" class="form-control">
                                <div v-show="errors.has('lc date')" class="help text-danger">
                                    {{ errors.first('lc date') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Item</label>
                                <input type="text" name="items" class="form-control"
                                       v-model.trim="imports.items"
                                       v-bind:class="{'has-error' : errors.has('items')}"
                                       v-validate="'required'" placeholder="Enter Item">
                                <div v-show="errors.has('items')" class="help text-danger">
                                    {{ errors.first('items') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Quantity</label>
                                <input type="number" name="Quantity"
                                       class="form-control"
                                       v-model.number="imports.qty"
                                       v-bind:class="{'has-error' : errors.has('Quantity')}"
                                       v-validate="'required'"
                                       placeholder="Enter Quantity">
                                <div v-show="errors.has('Quantity')" class="help text-danger">
                                    {{ errors.first('Quantity') }}
                                </div>
                            </div>

                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Box</label>
                                <input type="number" name="no of box"
                                       class="form-control"
                                       v-model.number="imports.no_of_box"
                                       v-bind:class="{'has-error' : errors.has('No Of Box')}"
                                       v-validate="'required'"
                                       placeholder="Enter No Of Box">
                                <div v-show="errors.has('no of box')" class="help text-danger">
                                    {{ errors.first('no of box') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Drum</label>
                                <input type="number" name="No Of Box"
                                       class="form-control"
                                       v-model.number="imports.no_of_drum"
                                       v-bind:class="{'has-error' : errors.has('no of drum')}"
                                       v-validate="'required'"
                                       placeholder="Enter No Of Drum">
                                <div v-show="errors.has('No Of Box')" class="help text-danger">
                                    {{ errors.first('No Of Box') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">No Of Jar</label>
                                <input type="number" name="no of jar"
                                       class="form-control"
                                       v-model.number="imports.no_of_jar"
                                       v-bind:class="{'has-error' : errors.has('no of jar')}"
                                       v-validate="'required'"
                                       placeholder="Enter no of jar">
                                <div v-show="errors.has('no of jar')" class="help text-danger">
                                    {{ errors.first('no of jar') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Bag</label>
                                <input type="number" name="no of bag"
                                       class="form-control"
                                       v-model.number="imports.no_of_bag"
                                       v-bind:class="{'has-error' : errors.has('no of bag')}"
                                       v-validate="'required'"
                                       placeholder="Enter no of bag">
                                <div v-show="errors.has('no of bag')" class="help text-danger">
                                    {{ errors.first('no of bag') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">No Of Roll</label>
                                <input type="number" name="no of roll"
                                       class="form-control"
                                       v-model.number="imports.no_of_roll"
                                       v-bind:class="{'has-error' : errors.has('no of roll')}"
                                       v-validate="'required'"
                                       placeholder="Enter No Of Roll">
                                <div v-show="errors.has('no of roll')" class="help text-danger">
                                    {{ errors.first('no of roll') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Bell</label>
                                <input type="number" name="no of bell"
                                       v-model.number="imports.no_of_bell"
                                       v-bind:class="{'has-error' : errors.has('no of bell')}"
                                       v-validate="'required'"
                                       class="form-control"
                                       placeholder="Enter no of bell">
                                <div v-show="errors.has('no of bell')" class="help text-danger">
                                    {{ errors.first('no of bell') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">No Of CBM</label>
                                <input type="number" name="no of cbm"
                                       class="form-control"
                                       v-model.number="imports.no_of_cbm"
                                       v-bind:class="{'has-error' : errors.has('no of cbm')}"
                                       v-validate="'required'"
                                       placeholder="Enter no of cbm">
                                <div v-show="errors.has('no of cbm')" class="help text-danger">
                                    {{ errors.first('no of cbm') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">CNF Name</label>
                                <input type="text" name="cnf name"
                                       v-model.trim="imports.cnf_name"
                                       v-bind:class="{'has-error' : errors.has('cnf name')}"
                                       v-validate="'required'" class="form-control"
                                       placeholder="Enter cnf name">
                                <div v-show="errors.has('cnf name')" class="help text-danger">
                                    {{ errors.first('cnf name') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Jetty Sorkar Name</label>
                                <input type="text" name="jetty sorkar name"
                                       v-model.trim="imports.jetty_sorkar_name"
                                       v-bind:class="{'has-error' : errors.has('jetty sorkar name')}"
                                       v-validate="'required'" class="form-control"
                                       placeholder="Enter jetty sorkar name">
                                <div v-show="errors.has('jetty sorkar name')" class="help text-danger">
                                    {{ errors.first('jetty sorkar name') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Jetty Sorkar Cell No</label>
                                <input type="text" name="jetty sorkar cell no"
                                       v-model.number="imports.jetty_sorkar_cell_no"
                                       v-bind:class="{'has-error' : errors.has('jetty sorkar cell no')}"
                                       v-validate="'required|digits:11'" class="form-control"
                                       placeholder="Enter Jetty Sorkar Cell No">
                                <div v-show="errors.has('jetty sorkar cell no')" class="help text-danger">
                                    {{ errors.first('jetty sorkar cell no') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Load Time</label>
                                <div class="input-group">
                                    <input type="text" name="Load Time"
                                           class="form-control timepicker"
                                           v-model.trim="imports.load_time"
                                           v-bind:class="{'has-error' : errors.has('Load Time')}"
                                           v-validate="'required'"
                                           placeholder="Enter Load Time">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <div v-show="errors.has('Load Time')" class="help text-danger">
                                    {{ errors.first('Load Time') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Get Out Time</label>
                                <div class="input-group">
                                    <input type="text" name="get out time"
                                           class="form-control timepicker"
                                           v-model.trim="imports.get_out_time"
                                           v-bind:class="{'has-error' : errors.has('get out time')}"
                                           v-validate="'required'"
                                           placeholder="Enter Get Out Time">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <div v-show="errors.has('get out time')" class="help text-danger">
                                    {{ errors.first('get out time') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Storage Location</label>
                                <input type="text" name="storage location"
                                       class="form-control timepicker"
                                       v-model.trim="imports.storage_location"
                                       v-bind:class="{'has-error' : errors.has('storage location')}"
                                       v-validate="'required'"
                                       placeholder="Enter storage location">
                                <div v-show="errors.has('storage location')" class="help text-danger">
                                    {{ errors.first('storage location') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class=" control-label">Store Concern Name</label>
                                <input type="text" name="store concern name"
                                       class="form-control"
                                       v-model.trim="imports.store_concern_name"
                                       v-bind:class="{'has-error' : errors.has('store concern name')}"
                                       v-validate="'required'"
                                       placeholder="store concern name">
                                <div v-show="errors.has('store concern name')" class="help text-danger">
                                    {{ errors.first('store concern name') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Store Concern Cell No</label>
                                <input type="text" name="Store Concern Cell No"
                                       class="form-control"
                                       v-model.trim="imports.store_concern_cell_no"
                                       v-bind:class="{'has-error' : errors.has('Store Concern Cell No')}"
                                       v-validate="'required|digits:11'"
                                       placeholder="Enter Store Concern Cell No">
                                <div v-show="errors.has('Store Concern Cell No')" class="help text-danger">
                                    {{ errors.first('Store Concern Cell No') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Van</label>
                                <input type="number" name="no of van"
                                       class="form-control"
                                       v-model.number="imports.no_of_van"
                                       v-bind:class="{'has-error' : errors.has('no of van')}"
                                       v-validate="'required'"
                                       placeholder="Enter no of van">
                                <div v-show="errors.has('no of van')" class="help text-danger">
                                    {{ errors.first('no of van') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Fare</label>
                                <input type="number" name="fare"
                                       v-model.trim="imports.fare"
                                       v-bind:class="{'has-error' : errors.has('fare')}"
                                       v-validate="'required'"
                                       class="form-control" placeholder="Enter Fare">
                                <div v-show="errors.has('fare')" class="help text-danger">
                                    {{ errors.first('fare') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Cover Van No</label>
                                <input type="number" name="cover van no"
                                       v-model.number="imports.cover_van_no"
                                       v-bind:class="{'has-error' : errors.has('cover van no')}"
                                       v-validate="'required'"
                                       class="form-control"
                                       placeholder="Enter No">
                                <div v-show="errors.has('cover van no')" class="help text-danger">
                                    {{ errors.first('cover van no') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">Cover Van Capacity</label>
                                <input type="number" name="Cover Van Capacity"
                                       v-model.number="imports.cover_van_capacity"
                                       v-bind:class="{'has-error' : errors.has('Cover Van Capacity')}"
                                       v-validate="'required'" class="form-control"
                                       placeholder="Enter Capacity">
                                <div v-show="errors.has('Cover Van Capacity')" class="help text-danger">
                                    {{ errors.first('Cover Van Capacity') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" :disabled="errors.any()" class="btn btn-success">Update Requisition Info</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from '~/components/layouts/MasterLayoutComponent.vue';

    export default {
        name: 'ImportUpdate',
        components: {
            //
        },
        data: () => ({
            imports: {}
        }),
        mounted: function () {
            // console.log('Import update component mounted.');
            this.importShow(this.$route.params.id);
        },
        methods: {
            generateRequisitionNo() {
                let currentObj = this;
                axios.post(this.$baseURL + 'imports/generate-requisition-no',
                    {
                        requisition_location: currentObj.imports.requisition_location
                    })
                    .then(response => {
                        currentObj.imports.requisition_no = response.data.data;
                    })
                    .catch(error => {
                        console.log(error.response.data.message);
                        this.$notification.error(error.response.data.message);
                    });
            },
            importShow(id) {
                axios.get('/api/v1/imports/' + id)
                    .then((response) => {
                        this.imports = response.data.data;
                    })
                    .catch(() => {
                        console.log('handle server error from here');
                    });
            },
            importUpdate() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.put(this.$baseURL + 'imports/' + this.imports.id, this.imports)
                            .then(response => {
                                this.$notification.success(response.data.message);
                                this.$validator.reset();
                            })
                            .catch(error => {
                                console.log(error.message);
                                this.$notification.error(error.response.data.message);
                            });
                    }
                })
            }
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>

