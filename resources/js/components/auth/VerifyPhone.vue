<template>
    <div>
        <div class="login-box">
            <div class="white-box">
                <form
                    @submit.prevent="verify()"
                    method="post"
                    class="form-horizontal form-material"
                    novalidate
                >
                    <h3 class="box-title m-b-20">Confirmation Code</h3>

                    <p>Enter the confirmation code sent to <b>{{ globalAuth.phone }}</b> code will expire in 30 min.</p>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                type="text"
                                v-model.trim="user.code"
                                class="form-control"
                                name="code"
                                placeholder="Code"
                                v-bind:class="{'has-error' : errors.has('code')}"
                                v-validate="'required'"
                            />
                            <div v-show="errors.has('code')" class="help text-danger">
                                {{ errors.first('code')
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit"
                                :disabled="errors.any()"
                            >Verify
                            </button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <a href="javascript:void(0)" @click.prevent="logout">
                                <b>Logout</b>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
    import GuestLayout from "~/components/layouts/GuestLayoutComponent.vue";

    import {mapState, mapGetters, mapMutations, mapActions} from "vuex";

    export default {
        data: () => ({
            user: {}
        }),
        computed: {
            ...mapGetters(['globalAuth']),
        },
        methods: {
            ...mapActions(['globalLogout']),
            verify() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        axios.post(this.$baseURL + "phone/verify", this.user)
                            .then(response => {
                                console.log(response.data);
                                this.$notification.success(response.data.message);
                                localStorage.setItem("auth", JSON.stringify(response.data.data));
                                this.$router.push("/dashboard");
                            })
                            .catch(error => {
                                this.$setErrorsFromResponse(error.response.data);
                                this.$notification.error(error.response.data.message);
                            });
                    }
                });
            },

            logout() {
                this.globalLogout();
                this.$router.push({name: "Login"});
            }
        },
        mounted: function () {
            //
        },
        created() {
            this.$emit("update:layout", GuestLayout);
        },
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
