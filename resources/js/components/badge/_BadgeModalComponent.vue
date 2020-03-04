<template>
    <el-dialog width="40%" top="5vh" :title="dialogTitle" :visible="dialogVisible" @close="onClose">
        <form method="POST" @submit.prevent="handleSubmit()" enctype="multipart/form-data" novalidate>

            <div class="row">
                <div class="form-group col-md-12" v-bind:class="{'has-error' : errors.has('name')}">
                    <label class="control-label">Name:*</label>
                    <input type="text"
                        name="name"
                        class="form-control"
                        v-validate="'required'"
                        v-model.trim="badge.name"
                        v-bind:class="{'has-error' : errors.has('name')}"
                        placeholder="Name"/>
                    <div v-show="errors.has('name')"
                        class="help text-danger">{{ errors.first('name') }}
                    </div>
                </div>

                <div class="form-group col-md-6"
                     v-bind:class="{'has-error' : errors.has('unit_id')}">
                    <label class="control-label">Unit:*</label>
                    <select v-model.trim="badge.unit_id" name="unit_id"
                            v-validate="'required'"
                            v-bind:class="{'has-error' : errors.has('unit_id')}"
                            class="form-control">
                        <option value="1">Steps</option>
                        <option value="2">Distance</option>
                    </select>
                    <div v-show="errors.has('unit_id')" class="help text-danger">
                        {{ errors.first('unit_id') }}
                    </div>
                </div>

                <div class="form-group col-md-6"
                    v-bind:class="{'has-error' : errors.has('target')}">
                    <label class="control-label">Target:</label>
                    <input type="text"
                        name="target"
                        v-model.trim="badge.target"
                        v-bind:class="{'has-error' : errors.has('target')}"
                        v-validate="'decimal:3'"
                        placeholder="00"
                        class="form-control"/>
                    <div v-show="errors.has('target')"
                        class="help text-danger">{{ errors.first('target') }}
                    </div>
                </div>

                <div class="form-group col-md-12"
                    v-bind:class="{'has-error' : errors.has('description')}">
                    <label class="control-label" for="description">Description:</label>
                    <textarea v-model="badge.description" id="description" rows="3" class="form-control"/>
                    <div v-show="errors.has('description')"
                        class="help text-danger">{{ errors.first('description') }}
                    </div>
                </div>

                <div class="form-group col-md-12" v-bind:class="{'has-error' : errors.has('badge_icon')}">
                    <label class="control-label">Badge icon:</label>
                    <el-upload
                        action=""
                        :on-change="handleAvatarChange"
                        :show-file-list="false"
                        accept=" .jpg, .jpeg, .png"
                        list-type="picture-card"
                        :on-preview="handlePictureCardPreview"
                        :auto-upload="false"
                        class="avatar-uploader">
                        <img v-if="badge.badge_icon"
                             height="100%"
                             width="100%"
                             :src="badge_icon_url"
                             alt="badge_icon"/>
                        <i class="el-icon-plus"></i>
                    </el-upload>
                    <el-dialog :visible.sync="dialogVisible2">
                        <img width="100%" :src="dialogImageUrl" alt />
                    </el-dialog>

                    <div v-show="errors.has('badge_icon')"
                        class="help text-danger">{{ errors.first('badge_icon') }}
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
            dialogVisible: {
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
            badge_icon_url: "",
            dialogImageUrl: "",
            dialogVisible2: false,
            submitMethod: "create"
        }),
        watch: {
            //
        },
        methods: {
            handleAvatarChange(file) {
                console.log(URL.createObjectURL(file.raw));
                this.badge_icon_url = URL.createObjectURL(file.raw);
                this.badge.badge_icon = file.raw;
            },
            handlePictureCardPreview(file) {
                this.dialogImageUrl = file.url;
                this.badge.badge_icon = file;
                this.dialogVisible2 = true;
            },
            handleSubmit() {
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };
                let formData = new FormData();

                if (this.submitMethod === "create") {
                    $.each(this.badge, function(key, value) {
                        formData.append(key, value);
                    });
                    axios.post(this.$baseURL + 'badges', formData, config)
                        .then(response => {
                        this.$eventBus.$emit('add-badge', response.data.data);
                        this.$notification.success(response.data.message);
                        this.onClose();
                    }).catch(error => {
                        this.$setErrorsFromResponse(error.response.data);
                        this.$notification.error(error.response.data.message);
                    });
                } else if (this.submitMethod === "update") {
                    axios.put(this.$baseURL + 'badges/' + this.badge.id, this.badge)
                        .then(response => {
                            this.$notification.success(response.data.message);
                            this.$eventBus.$emit('reload-badges');
                            this.onClose();
                        })
                        .catch(error => {
                            this.$notification.error(error.response.data.message);
                        });
                }
            },
            onChangeBadgeIcon(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    this.badge.badge_icon = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            onClose() {
                this.$validator.reset();
                this.badge = {};
                this.submitMethod = "create";
                this.$emit("update:dialogVisible", false);
            }
        },

        mounted: function () {
            // We listen for the event on the eventBus
            this.$eventBus.$on("edit-badge", badge => {
                this.submitMethod = "update";
                this.badge = badge;
                this.badge_icon_url = '/images/badges/thumb/thumb_200x200_' + this.badge.badge_icon;
                this.badge.unit_id = badge.unit.id;
                this.$emit("update:dialogVisible", true)
                    .$emit("update:dialogTitle", "Badge update");
            });
        },
        computed: {
            //
        },
        created: function () {
            //
        },
        beforeDestroy() {
            this.$eventBus.$off("edit-badge");
        }
    };
</script>

<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
    .el-upload__input {
        display: none !important;
    }

</style>
