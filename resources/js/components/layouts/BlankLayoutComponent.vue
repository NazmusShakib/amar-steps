<template>
    <div>
        <div id="wrapper">
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
            console.log("Blank layout component mounted.");
        }
    };
</script>


<style lang="scss">

</style>
