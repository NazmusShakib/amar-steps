<template>
    <div>
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <a
                    class="navbar-toggle hidden-sm hidden-md hidden-lg"
                    href="javascript:void(0)"
                    data-toggle="collapse"
                    data-target=".navbar-collapse"
                >
                    <i class="ti-menu"></i>
                </a>
                <div class="top-left-part">
                    <!--<span class="hidden-xs">
                    <router-link :to="{ name: 'Dashboard'}" class="waves-effect">
                        Transport Requisition Management
                    </router-link>
                    </span>-->
                    <a class="logo" :href="'/'">
                        <b>
                            <img
                                :src="'/plugins/images/amar-step-40x40.png'"
                                alt="home"
                                class="dark-logo"
                            />
                            <img
                                :src="'/plugins/images/amar-step-40x40.png'"
                                alt="home"
                                class="light-logo"
                            />
                        </b>
                        <span class="hidden-xs">
                            <img
                                :src="'/plugins/images/eliteadmin-text.png'"
                                alt="home"
                                class="dark-logo"
                            />
                            <img
                                :src="'/plugins/images/eliteadmin-text-dark.png'"
                                alt="home"
                                class="light-logo"
                            />
                        </span>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li>
                        <a
                            href="javascript:void(0)"
                            class="open-close hidden-xs waves-effect waves-light"
                        >
                            <i class="icon-arrow-left-circle ti-menu"></i>
                        </a>
                    </li>
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" placeholder="Search..." class="form-control" />
                            <a href="#">
                                <i class="fa fa-search"></i>
                            </a>
                        </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <img
                                :src="'/plugins/images/users/blank-profile-picture.png'"
                                alt="user-img"
                                width="36"
                                class="img-circle"
                            />
                            <b class="hidden-xs">{{ profile.name }}</b>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <router-link :to="{ name: 'Profile'}" class="waves-effect">
                                    <i class="icon-user fa-fw"></i> My Account
                                </router-link>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <a href="javascript:void(0)" @click.prevent="logout">
                                    <i class="icon-logout fa-fw"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</template>


<script>

export default {
    props: [],
    computed: {
        profile() {
            return this.$store.getters.globalAuth;
        }
    },
    methods: {
        logout() {
            this.$store.dispatch("globalLogout");
            this.$router.push({name: 'Login'});
        }
    },
    created: function() {
        console.log("NavBar component.");
    }
};
</script>

<style scoped>
.top-left-part a {
    margin-top: 11px;
    font-size: 16px;
    text-align: center;
    font-weight: bold;
    color: beige;
}
</style>
