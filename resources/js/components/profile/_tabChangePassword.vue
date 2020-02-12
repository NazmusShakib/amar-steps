<template>
    <div class="tab-pane" id="change-password">
        <form class="form-horizontal form-material" @submit.prevent="changePassword()">
            <div class="form-group">
                <label class="col-md-12" for="old_password">Old Password</label>
                <div class="col-md-12">
                    <input type="password" name="old_password" id="old_password" class="form-control form-control-line"
                           v-validate="'required'" v-model.trim="user.old_password" autocomplete="off">
                    <div v-show="errors.has('old_password')" class="help text-danger">
                        {{ errors.first('old_password') }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12" for="password">New Password</label>
                <div class="col-md-12">
                    <input type="password" name="password" id="password" class="form-control form-control-line"
                           v-validate="'required'" v-model.trim="user.password"
                           autocomplete="off">
                    <div v-show="errors.has('password')" class="help text-danger">
                        {{ errors.first('password') }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12" for="password_confirmation">Password Confirmation</label>
                <div class="col-md-12">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-line"
                           v-validate="'required'" v-model.trim="user.password_confirmation" autocomplete="off">
                    <div v-show="errors.has('password_confirmation')" class="help text-danger">
                        {{ errors.first('password_confirmation') }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn btn-success">Change Password</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            //
        },
        data: () => ({
            user: {}
        }),
        mounted: () => {
            //
        },
        methods: {
            changePassword() {
                axios.post(this.$baseURL + 'profile/change-password', this.user)
                    .then( (response) => {
                        this.user = {};
                        this.$validator.reset();
                        this.$notification.success(response.data.message);
                    }).catch(error => {
                        this.user = {};
                        this.$setErrorsFromResponse(error.response.data);
                        this.$notification.error(error.response.data.message);
                });
            }
        }
    }
</script>
