<template>
    <div>
        <div class="login-box">
            <div class="white-box">
                <form
                    class="form-horizontal form-material"
                    @submit.prevent="register()"
                    method="post"
                    novalidate
                >
                    <h3 class="box-title m-b-20">Sign Up</h3>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="phone"
                                v-model.trim="user.phone"
                                autofocus
                                class="form-control"
                                name="phone"
                                placeholder="Phone"
                                v-bind:class="{'has-error' : errors.has('phone')}"
                                v-validate="'required'"
                            />
                            <div
                                v-show="errors.has('phone')"
                                class="help text-danger"
                            >{{ errors.first('phone') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="email"
                                v-model.trim="user.email"
                                autocomplete="email"
                                autofocus
                                class="form-control"
                                name="email"
                                placeholder="Email"
                                v-bind:class="{'has-error' : errors.has('email')}"
                                v-validate="'required|email'"
                            />
                            <div
                                v-show="errors.has('email')"
                                class="help text-danger"
                            >{{ errors.first('email') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="password"
                                v-model.trim="user.password"
                                class="form-control"
                                name="password"
                                placeholder="Password"
                                v-bind:class="{'has-error' : errors.has('password')}"
                                ref="password"
                                v-validate="'required'"
                            />
                            <div v-show="errors.has('password')" class="help text-danger">
                                {{ errors.first('password')
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="password"
                                v-model.trim="user.password_confirmation"
                                class="form-control"
                                name="password confirmation"
                                placeholder="Password confirmation"
                                v-bind:class="{'has-error' : errors.has('password confirmation')}"
                                v-validate="'required|confirmed:password'"
                            />
                            <div
                                v-show="errors.has('password confirmation')"
                                class="help text-danger"
                            >
                                {{ errors.first('password confirmation')
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12" v-bind:class="{'has-error' : errors.has('agreement')}">
                            <div class="checkbox checkbox-primary p-t-0">
                                <input id="checkbox-signup" type="checkbox" name="agreement" v-validate="'required'"/>
                                <label for="checkbox-signup">
                                    I agree to all
                                    <a href="#">Terms</a>
                                </label>
                            </div>
                            <div v-show="errors.has('agreement')" class="help text-danger" >
                                {{ errors.first('agreement') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit"
                            >Sign Up
                            </button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>
                                Already have an account?
                                <router-link :to="{name:'Login'}" class="text-primary m-l-5">
                                    <b>Sign In</b>
                                </router-link>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import GuestLayout from "~/components/layouts/GuestLayoutComponent.vue";

    export default {
        data: () => ({
            user: {}
        }),
        methods: {
            register() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios
                            .post(this.$baseURL + "register", this.user)
                            .then(response => {
                                var data = response.data.data;
                                localStorage.setItem("token", data.token);
                                localStorage.setItem("auth",JSON.stringify(data.auth));
                                this.$store.dispatch("profile/authStore", data.auth);
                                this.$router.push("/verify");
                                this.$notification.success(response.data.message);
                            })
                            .catch(error => {
                                this.$setErrorsFromResponse(error.response.data);
                                this.$notification.error(error.response.data.message);
                            });
                    }
                });
            }
        },
        mounted: function () {
            console.log("Register component mounted.");
        },
        created() {
            this.$emit("update:layout", GuestLayout);
        }
    };
</script>


<style type="text/css">
    .panel-title {
        display: inline;
        font-weight: bold;
    }

    .display-table {
        display: table;
    }

    .display-tr {
        display: table-row;
    }

    .display-td {
        display: table-cell;
        vertical-align: middle;
        width: 61%;
    }

    .login-box {
        background: #fff;
        width: 400px;
        margin: auto;
        margin-top: 50px;
    }

    .over-flow-auto {
        overflow: auto;
    }

    .box-width-loging {
        width: 500px;
    }

    /* Extra small devices (phones, 600px and down) */
    @media only screen and (max-width: 600px) {
        .box-width-loging {
            width: 100%;
        }
    }

    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {
        .box-width-loging {
            width: 100%;
        }
    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
        .box-width-loging {
            width: 500px;
        }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
        .box-width-loging {
            width: 500px;
        }
    }

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {
        .box-width-loging {
            width: 500px;
        }
    }
</style>

