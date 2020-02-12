<template>
    <div>
        <NavBar></NavBar>
        <Sidebar></Sidebar>

        <div id="page-wrapper">

            <main class="MasterLayout__main">
                <slot/>
            </main>

            <footer class="footer text-center">
                {{ new Date().getFullYear() }} &copy; AmarSteps
            </footer>
        </div>

    </div>

</template>

<style lang="scss">

</style>

<script>
    import {NavBar, Sidebar} from '../partials'

    export default {
        components: {
            NavBar,
            Sidebar
        },
        data: () => ({
            //
        }),
        methods: {
            //
        },
        mounted: function () {
            // console.log('Master layout component mounted.');
        }
    }
</script>
