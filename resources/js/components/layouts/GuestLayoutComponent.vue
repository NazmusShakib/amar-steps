<template>
    <div>
        <div id="wrapper" class="login-register">
            <main class="MasterLayout__main">
                <slot />
            </main>
        </div>
    </div>
</template>


<script>
export default {
    components: {
        //
    },
    data: () => ({
        //
    }),
    mounted: function() {
        console.log("Guest layout component mounted.");
    }
};
</script>


<style lang="scss">
.login-register {
    background-color: #2c3e50 !important;
    height: 100%;
    position: fixed;
    overflow: auto;
}
</style>