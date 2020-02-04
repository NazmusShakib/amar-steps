<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Export</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Exports</a></li>
                    <li class="active">Create</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">

                    <form action="#" method="POST" @submit.prevent="exportStore()" novalidate>

                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Transport Name</label>
                                <input type="text" name="transport name"
                                       class="form-control"
                                       v-model.trim="exports.transport_name"
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
                                        v-model.trim="exports.requisition_location"
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
                                       v-model.trim="exports.requisition_no"
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
                                       v-model.trim="exports.requisition_date"
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
                                <label class="control-label">Buyer Name</label>
                                <select class="form-control" name="Buyer Name"
                                        v-model="exports.buyer_name"
                                        v-bind:class="{'has-error' : errors.has('Buyer Name')}"
                                        v-validate="'required'">
                                    <option>---Select Buyer Name---</option>
                                    <option value="CnA">C&A</option>
                                    <option value="HnM">H&M</option>
                                    <option value="KIABI">KIABI</option>
                                    <option value="JENIFER">JENIFER</option>
                                    <option value="NEXT">NEXT</option>
                                    <option value="STILL STROM">STILL STROM</option>
                                    <option value="LIDL">LIDL</option>
                                </select>
                                <div v-show="errors.has('Buyer Name')" class="help text-danger">
                                    {{ errors.first('Buyer Name') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Load Point</label>
                                <select class="form-control" name="Load Point"
                                        v-model.trim="exports.load_point"
                                        v-bind:class="{'has-error' : errors.has('Load Point')}"
                                        v-validate="'required'">
                                    <option>---Select Load Point---</option>
                                    <option value="Vulta">Vulta</option>
                                    <option value="Fatullah">Fatullah</option>
                                    <option value="Kaliakoir">Kaliakoir</option>
                                </select>
                                <div v-show="errors.has('Load Point')" class="help text-danger">
                                    {{ errors.first('Load Point') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Unload Point</label>
                                <select class="form-control" name="Unload Point"
                                        v-model.trim="exports.unload_point"
                                        v-bind:class="{'has-error' : errors.has('Unload Point')}"
                                        v-validate="'required'">
                                    <option>---Select Unload Point---</option>
                                    <option value="Airport-Hemlane">Airport-Hemlane</option>
                                    <option value="Airport-Nippon">Airport-Nippon</option>
                                    <option value="All Port">All Port</option>
                                    <option value="BM">BM</option>
                                    <option value="BM">Incontred</option>
                                    <option value="Ispahani">Ispahani</option>
                                    <option value="K&T">K&T</option>
                                    <option value="KDS">KDS</option>
                                    <option value="Mawna">Mawna</option>
                                    <option value="Mokterpur">Mokterpur</option>
                                    <option value="Nemsan">Nemsan</option>
                                    <option value="NKD">Nemsan</option>
                                    <option value="OCL">OCL</option>
                                    <option value="Portlink">Portlink</option>
                                    <option value="SAPL">SAPL</option>
                                </select>
                                <div v-show="errors.has('Unload Point')" class="help text-danger">
                                    {{ errors.first('Unload Point') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Item</label>
                                <input type="text"
                                       name="item"
                                       v-model.trim="exports.items"
                                       v-bind:class="{'has-error' : errors.has('item')}"
                                       v-validate="'required'" class="form-control"
                                       placeholder="Enter Item">
                                <div v-show="errors.has('item')" class="help text-danger">
                                    {{ errors.first('item') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Quantity</label>
                                <input type="number" name="Quantity"
                                       class="form-control"
                                       v-model.number="exports.qty"
                                       v-bind:class="{'has-error' : errors.has('Quantity')}"
                                       v-validate="'required'"
                                       placeholder="Enter Quantity">
                                <div v-show="errors.has('Quantity')" class="help text-danger">
                                    {{ errors.first('Quantity') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">CBM</label>
                                <input type="number" name="cbm"
                                       class="form-control"
                                       v-model.number="exports.cbm"
                                       v-bind:class="{'has-error' : errors.has('cbm')}"
                                       v-validate="'required'"
                                       placeholder="Enter CBM">
                                <div v-show="errors.has('cbm')" class="help text-danger">
                                    {{ errors.first('cbm') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">No Of Van</label>
                                <input type="number" name="No Of Van"
                                       v-model.number="exports.no_of_van"
                                       v-bind:class="{'has-error' : errors.has('No Of Van')}"
                                       v-validate="'required'"
                                       class="form-control"
                                       placeholder="Enter No Of Van">
                                <div v-show="errors.has('No Of Van')" class="help text-danger">
                                    {{ errors.first('No Of Van') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Load Time</label>
                                <div class="input-group">
                                    <input type="text" name="Load Time"
                                           class="form-control timepicker"
                                           v-model.trim="exports.load_time"
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
                            <div class=" form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Unload Time</label>
                                <div class="input-group">
                                    <input type="text" name="Unload Time"
                                           class="form-control timepicker"
                                           v-model.trim="exports.unload_time"
                                           v-bind:class="{'has-error' : errors.has('Unload Time')}"
                                           v-validate="'required'"
                                           placeholder="Enter Unload Time">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <div v-show="errors.has('Unload Time')" class="help text-danger">
                                    {{ errors.first('Unload Time') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6 bootstrap-timepicker">
                                <label class="control-label">Cut Off Time</label>
                                <div class="input-group">
                                    <input type="text" name="Cut Off Time"
                                           class="form-control timepicker"
                                           v-model.trim="exports.cut_off_time"
                                           v-bind:class="{'has-error' : errors.has('Cut Off Time')}"
                                           v-validate="'required'"
                                           placeholder="Enter Cut Of Time">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <div v-show="errors.has('Cut Off Time')" class="help text-danger">
                                    {{ errors.first('Cut Off Time') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class=" form-group col-md-6">
                                <label class="control-label">Cut Off Date</label>
                                <input type="date"
                                       name="Cut Off Date"
                                       v-model.trim="exports.cut_off_date"
                                       v-bind:class="{'has-error' : errors.has('Cut Off Date')}"
                                       v-validate="'required'"
                                       class="form-control">
                                <div v-show="errors.has('Cut Off Date')" class="help text-danger">
                                    {{ errors.first('Cut Off Time') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="control-label">Fare</label>
                                <input type="number" name="fare"
                                       v-model.trim="exports.fare"
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
                                       v-model.number="exports.cover_van_no"
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
                                       v-model.number="exports.cover_van_capacity"
                                       v-bind:class="{'has-error' : errors.has('Cover Van Capacity')}"
                                       v-validate="'required'" class="form-control"
                                       placeholder="Enter Capacity">
                                <div v-show="errors.has('Cover Van Capacity')" class="help text-danger">
                                    {{ errors.first('Cover Van Capacity') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" :disabled="errors.any()" class="btn btn-success">Save
                                Requisition Info
                            </button>
                            <button class="btn btn-danger" type="reset">Clear</button>
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
        name: 'ExportCreate',
        components: {
            //
        },
        data: () => ({
            exports: {
                // requisition_no: ''
            }
        }),
        mounted: function () {
            console.log('Export create component mounted.');
        },
        methods: {
            generateRequisitionNo() {
                let currentObj = this;
                axios.post(this.$baseURL + 'exports/generate-requisition-no',
                    {
                        requisition_location: currentObj.exports.requisition_location
                    })
                    .then(response => {
                        currentObj.exports.requisition_no = response.data.data;
                    })
                    .catch(error => {
                        console.log(error.response.data.message);
                        this.$notification.error(error.response.data.message);
                    });
            },
            exportStore() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post(this.$baseURL + 'exports', this.exports)
                            .then(response => {
                                this.exports = {};
                                this.$notification.success(response.data.message);
                                this.$validator.reset();
                            })
                            .catch(error => {
                                console.log(error.message);
                                this.$notification.error(error.response.data.message);
                            });
                    }
                })
            },
            focusOnInvalidField() {
                const firstField = Object.keys(this.errors.collect())[0];
                this.$refs[`${firstField}Input`].focus();
            },
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>

