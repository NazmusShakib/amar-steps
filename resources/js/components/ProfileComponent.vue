<template>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Profile</h4></div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Profile</a></li>
                    <li class="active">Details</li>
                </ol>
            </div>
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-4 col-xs-12">

                <left-card :auth="profile"></left-card>

            </div>

            <div class="col-md-8 col-xs-12">
                <div class="white-box">
                    <ul class="nav nav-tabs tabs customtab">
                        <li class="active tab">
                            <a href="#profile" data-toggle="tab" v-on:click="activeTab = 'Profile'">
                                <span class="visible-xs"><i class="fa fa-user"></i></span>
                                <span class="hidden-xs">Profile</span>
                            </a>
                        </li>
                        <li class="tab" v-on:click="activeTab = 'UpdateProfile'">
                            <a href="#update-profile" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                <span class="hidden-xs">Settings</span>
                            </a>
                        </li>
                        <li class="tab" v-on:click="activeTab = 'Headshot'">
                            <a href="#headshot" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                <span class="hidden-xs">Headshot</span>
                            </a>
                        </li>
                        <li class="tab" v-on:click="activeTab = 'ChangePassword'">
                            <a href="#change-password" data-toggle="tab" aria-expanded="false">
                                <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                <span class="hidden-xs">Change Password</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <keep-alive>
                            <component v-bind:is="activeTab" :auth="profile"></component>
                        </keep-alive>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
</template>

<script>
    import MasterLayout from './layouts/MasterLayoutComponent.vue';

    import {ChangePassword, Headshot, LeftCard, Profile, UpdateProfile} from './profile';

    export default {
        name: 'ProfileDetails',
        components: {
            LeftCard, Profile, UpdateProfile, Headshot, ChangePassword
        },
        computed: {
            profile() {
                return this.$store.getters.globalAuth;
            },
        },
        data: () => ({
            activeTab: Profile
        }),
        mounted: function () {
            console.log('Profile component mounted.');
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>
