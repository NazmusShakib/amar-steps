<template>
    <div class="tab-pane active" id="profile">
        <div class="row">
            <div class="col-md-3 col-xs-6 b-r"><strong>Full Name</strong>
                <br>
                <p class="text-muted">{{ auth.name || 'N/A' }}</p>
            </div>
            <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                <br>
                <p class="text-muted">{{ auth.phone || 'N/A' }}</p>
            </div>
            <div class="col-md-4 col-xs-6 b-r"><strong>Email</strong>
                <br>
                <p class="text-muted">{{ auth.email || 'N/A' }}</p>
            </div>
            <div class="col-md-2 col-xs-6"><strong>Gender</strong>
                <br>
                <p class="text-muted">{{ auth.profile.gender || 'N/A' }}</p>
            </div>
        </div>
        <hr>
        <p class="m-t-30">{{ auth.profile.bio || 'N/A' }}</p>
        <b>Address: </b>
        <p> {{ auth.profile.address || 'N/A' }}</p>
        <h4 class="font-bold m-t-30">Skill Set</h4>
        <hr>
        <h5>Wordpress <span class="pull-right">80%</span></h5>
        <div class="progress">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                 aria-valuemax="100" style="width:80%;"><span class="sr-only">50% Complete</span></div>
        </div>
        <h5>HTML 5 <span class="pull-right">90%</span></h5>
        <div class="progress">
            <div class="progress-bar progress-bar-custom" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                 aria-valuemax="100" style="width:90%;"><span class="sr-only">50% Complete</span></div>
        </div>
        <h5>jQuery <span class="pull-right">50%</span></h5>
        <div class="progress">
            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="50" aria-valuemin="0"
                 aria-valuemax="100" style="width:50%;"><span class="sr-only">50% Complete</span></div>
        </div>
        <h5>Photoshop <span class="pull-right">70%</span></h5>
        <div class="progress">
            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70" aria-valuemin="0"
                 aria-valuemax="100" style="width:70%;"><span class="sr-only">50% Complete</span></div>
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
            console.log('Tab-1 component mounted.');
        },
        methods: {
            //
        }
    }
</script>
