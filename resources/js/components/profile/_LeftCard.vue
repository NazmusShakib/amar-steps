<template>
    <div class="white-box">
        <div class="user-bg"><img width="100%" alt="user" :src="'plugins/images/large/img1.jpg'">
            <div class="overlay-box">
                <div class="user-content">
                    <a href="javascript:void(0)"><img :src="'plugins/images/users/blank-profile-picture.png'"
                                                      class="thumb-lg img-circle" alt="img"></a>
                    <h4 class="text-white"> {{ auth.name || 'N/A' }}</h4>
                    <h5 class="text-white"> {{ auth.email || 'N/A' }}</h5>
                    <h5 class="text-white"> {{ auth.phone || 'N/A' }}</h5>
                </div>
            </div>
        </div>
        <div class="user-btm-box">
            <div class="col-md-4 col-sm-4 text-center">
                <p class="text-purple"><i class="ti-facebook"></i></p>
                <h1>258</h1>
            </div>
            <div class="col-md-4 col-sm-4 text-center">
                <p class="text-blue"><i class="ti-twitter"></i></p>
                <h1>125</h1>
            </div>
            <div class="col-md-4 col-sm-4 text-center">
                <p class="text-danger">
                    <i class="ti-dribbble"></i>
                </p>
                <h1>556</h1>
            </div>
        </div>
    </div>

</template>


<script>
    export default {
        props: {
            auth: {
                type: Object,
                required: false
            },
        },
        data: () => ({
            //
        }),
        mounted: function () {
            console.log('Left card component mounted.');
        },
        methods: {
            //
        }
    }
</script>
