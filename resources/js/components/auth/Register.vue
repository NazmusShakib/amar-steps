<template>
    <div>
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" id="loginform" action="index.html">
                    <h3 class="box-title m-b-20">Sign In</h3>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required placeholder="Name" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required placeholder="Email" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                class="form-control"
                                type="password"
                                required
                                placeholder="Password"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input
                                class="form-control"
                                type="password"
                                required
                                placeholder="Confirm Password"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary p-t-0">
                                <input id="checkbox-signup" type="checkbox" />
                                <label for="checkbox-signup">
                                    I agree to all
                                    <a href="#">Terms</a>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit"
                            >Sign Up</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>
                                Already have an account?
                                <a
                                    href="login.html"
                                    class="text-primary m-l-5"
                                >
                                    <b>Sign In</b>
                                </a>
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
        //
    }),
    mounted: function() {
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

