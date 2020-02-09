<template>
    <div>
        <div class="login-box">
            <div class="white-box">
                <form
                    @submit.prevent="login()"
                    method="post"
                    class="form-horizontal form-material"
                    novalidate
                >
                    <h3 class="box-title m-b-20">Sign In</h3>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="text"
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
                                type="password"
                                v-model.trim="user.password"
                                autocomplete="current-password"
                                class="form-control"
                                name="password"
                                placeholder="Password"
                                v-bind:class="{'has-error' : errors.has('password')}"
                                v-validate="'required'"
                            />
                            <div v-show="errors.has('password')" class="help text-danger">
                                {{ errors.first('password')
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input id="checkbox-signup" type="checkbox" v-model="user.remember"/>
                                <label for="checkbox-signup">Remember me</label>
                            </div>
                            <a
                                href="javascript:void(0)"
                                id="to-recover"
                                class="text-dark pull-right"
                            >
                                <i class="fa fa-lock m-r-5"> </i> Forgot pwd?
                            </a>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit"
                                :disabled="errors.any()"
                            >Log In
                            </button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>
                                Don't have an account?
                                <router-link :to="{name:'Register'}" class="text-primary m-l-5">
                                    <b>Sign Up</b>
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
    import GuestLayout from "../layouts/GuestLayoutComponent.vue";

    export default {
        data: () => ({
            user: {}
        }),
        methods: {
            login() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post(this.$baseURL + "login", this.user)
                            .then(response => {
                                var data = response.data.data;
                                localStorage.setItem("token", data.token);
                                localStorage.setItem("auth", JSON.stringify(data.auth)
                                );
                                this.$store.dispatch("profile/authStore", data.auth);
                                this.$router.push("/dashboard");
                            })
                            .catch(error => {
                                this.$notification.error(error.response.data.errors.error);
                            });
                    }
                });
            },
        },
        mounted: function () {
            //
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
        margin-top: 70px;
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
