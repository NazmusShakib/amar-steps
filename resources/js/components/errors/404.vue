<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="error-body text-center">
                    <h1>404</h1>
                    <h3 class="text-uppercase">Page Not Found !</h3>
                    <p class="text-muted m-t-30 m-b-30">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>
                    <router-link :to="{name:'Dashboard'}"
                                 class="btn btn-info btn-rounded waves-effect waves-light m-b-40">Back To Home</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MasterLayout from '~/components/layouts/MasterLayoutComponent.vue';

    export default {
        data: () => ({
            //
        }),
        mounted: function () {
            console.log('Error404 component mounted.');
        },
        created() {
            this.$emit('update:layout', MasterLayout);
        },
    }
</script>