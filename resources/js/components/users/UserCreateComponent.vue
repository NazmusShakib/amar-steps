<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">User</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">User</a></li>
                    <li class="active">Create</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">User Onboarding</h3>
                    <p class="text-muted m-b-30">Register new user</p>
                    <form @submit.prevent="userOnboarding()" novalidate>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="name">Name*</label>
                                <input type="text" class="form-control"
                                       id="name" name="name" placeholder="Name"
                                       v-model.trim="user.name"
                                       v-bind:class="{'has-error' : errors.has('name')}"
                                       v-validate="'required'">
                                <div v-show="errors.has('name')" class="help text-danger">{{ errors.first('name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="role">Role*</label>
                                <select class="form-control" id="role" name="role"
                                        v-model.trim="user.role"
                                        v-validate="'required'"
                                        data-vv-validate-on="blur|change"
                                        v-bind:class="{'has-error' : errors.has('role')}">
                                    <option value="admin">Administrator</option>
                                    <option value="staff">Staff</option>
                                    <option value="subscriber">Subscriber</option>
                                </select>
                                <div class="help text-danger" v-show="errors.has('role')">{{ errors.first('role') }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="control-label" for="email">Email*</label>
                                <input type="email" class="form-control" id="email"
                                       name="email" placeholder="Email"
                                       v-model.trim="user.email"
                                       v-validate="'required|email'"
                                       data-vv-validate-on="blur|change"
                                       v-bind:class="{'has-error' : errors.has('email')}">
                                <div class="help text-danger" v-show="errors.has('email')">
                                    {{ errors.first('email') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label" for="phone">Phone*</label>
                                <input type="text" autocomplete="off" class="form-control" id="phone"
                                       name="phone" placeholder="Phone"
                                       v-validate="'required'"
                                       v-model="user.phone">
                                <div class="help text-danger" v-show="errors.has('phone')">
                                    {{ errors.first('phone') }}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="textarea">Address</label>
                            <textarea class="form-control" id="textarea"
                                      name="address" v-model="user.address"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="password">Password*</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <input type="password" autocomplete="off" class="form-control"
                                           id="password" name="password" placeholder="Password"
                                           v-validate="'required|min:6'" ref="password" v-model="user.password">
                                    <div class="help text-danger" v-show="errors.has('password')">
                                        {{ errors.first('password') }}
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" autocomplete="off" class="form-control"
                                           id="password_confirmation" name="password_confirmation"
                                           placeholder="Repeat Password" v-validate="'required|confirmed:password'"
                                           data-vv-as="password"
                                           v-model="user.password_confirmation">
                                    <div class="help text-danger" v-show="errors.has('password_confirmation')">
                                        {{ errors.first('password_confirmation') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" :disabled="errors.any()">Submit
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
        name: 'UserCreate',
        components: {
            //
        },
        data: () => ({
            user: []
        }),
        mounted: function () {
            console.log('Create user component mounted.');
        },
        methods: {
            userOnboarding() {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        var newUser = this.user;
                        axios.post(this.$baseURL + 'users', {
                            name: newUser.name,
                            role: newUser.role,
                            email: newUser.email,
                            phone: newUser.phone,
                            address: newUser.address,
                            password: newUser.password,
                            password_confirmation: newUser.password_confirmation,
                        })
                            .then(response => {
                                newUser = [];
                                this.$notification.success(response.data.message);
                                this.$validator.reset();
                            })
                            .catch(err => {
                                this.$setErrorsFromResponse(err.response.data);
                                if (!err.response.data.valid) {
                                    this.user.password = this.user.password_confirmation = '';
                                    this.$notification.error(err.response.data.message);
                                }
                            });
                    }
                });
            }
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>
