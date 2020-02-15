<template>
    <el-dialog width="40%" top="5vh" :title="dialogTitle" :visible="showDialog" @close="onClose">
        <form method="POST" @submit.prevent="handleSubmit()" novalidate>
            <div class="row">
                <div class="form-group col-md-12" v-bind:class="{'has-error' : errors.has('name')}">
                    <label class="control-label">Name:*</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        v-validate="'required'"
                        v-model.trim="badge.name"
                        v-bind:class="{'has-error' : errors.has('name')}"
                        placeholder="Name"
                    />
                    <div
                        v-show="errors.has('name')"
                        class="help text-danger"
                    >{{ errors.first('name') }}
                    </div>
                </div>
                <div
                    class="form-group col-md-12"
                    v-bind:class="{'has-error' : errors.has('display_name')}"
                >
                    <label class="control-label">Display name:</label>
                    <input
                        type="text"
                        name="display_name"
                        v-model.trim="badge.display_name"
                        v-bind:class="{'has-error' : errors.has('display_name')}"
                        placeholder="Display name"
                        class="form-control"
                    />
                    <div
                        v-show="errors.has('display_name')"
                        class="help text-danger"
                    >{{ errors.first('display_name') }}
                    </div>
                </div>

                <div
                    class="form-group col-md-12"
                    v-bind:class="{'has-error' : errors.has('target')}"
                >
                    <label class="control-label">Target:</label>
                    <input
                        type="text"
                        name="target"
                        v-model.trim="badge.target"
                        v-bind:class="{'has-error' : errors.has('target')}"
                        v-validate="'decimal:3'"
                        placeholder="00"
                        class="form-control"
                    />
                    <div
                        v-show="errors.has('target')"
                        class="help text-danger"
                    >{{ errors.first('target') }}
                    </div>
                </div>


                <div
                    class="form-group col-md-12"
                    v-bind:class="{'has-error' : errors.has('description')}"
                >
                    <label class="control-label">Description:</label>
                    <textarea v-model="badge.description" rows="3" class="form-control"></textarea>
                    <div
                        v-show="errors.has('description')"
                        class="help text-danger"
                    >{{ errors.first('description') }}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <button class="btn btn-danger" type="reset">Clear</button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
    export default {
        props: {
            showDialog: {
                type: Boolean,
                required: true
            },
            dialogTitle: {
                type: String,
                required: true
            }
        },
        data: () => ({
            badge: {},
            formType: "create"
        }),
        watch: {
            //
        },
        methods: {
            handleSubmit() {
                if (this.formType === "create") {
                    axios.post(this.$baseURL + 'badges', this.badge).then(response => {
                        this.$notification.success(response.data.message);
                        this.onClose();
                    }).catch(error => {
                        this.$setErrorsFromResponse(error.response.data);
                        this.$notification.error(error.response.data.message);
                    });
                } else if (this.formType === "update") {
                    //
                }
            },

            showBadge(id) {
                axios
                    .get(this.$baseURL + "badges/" + id)
                    .then(response => {
                        this.badge = response.data.data;
                        this.$emit("update:showDialog", true);
                    })
                    .catch(() => {
                        console.log("handle server error from here.");
                    });
            },

            /*updateBadge() {
                    this.$validator.validateAll().then((result) => {
                        if (result) {
                            axios.put(this.$baseURL + 'badges/' + this.badge.id, this.badge)
                                .then(response => {
                                    this.$notification.success(response.data.message);
                                    this.onClose();
                                })
                                .catch(error => {
                                    this.$notification.error(error.response.data.message);
                                });
                        }
                    })
                },*/

            onClose() {
                this.$validator.reset();
                this.badge = {};
                this.formType = "create";
                this.$emit("update:showDialog", false).$emit(
                    "update:dialogTitle",
                    "Add a new badge"
                );
            }
        },
        mounted: function () {
            // We listen for the event on the eventBus
            this.$eventBus.$on("editBadge", id => {
                this.formType = "update";
                this.showBadge(id);
            });
        },
        computed: {
            //
        },
        created: function () {
            //
        },
        beforeDestroy() {
            this.$eventBus.$off("editBadge");
        }
    };
</script>

<style scoped>
</style>
