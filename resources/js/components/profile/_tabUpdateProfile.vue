<template>
    <div class="tab-pane" id="update-profile">
        <form class="form-horizontal form-material" @submit.prevent="updateProfile">
            <div class="form-group">
                <label class="col-md-12">Full Name</label>
                <div class="col-md-12">
                    <input type="text" v-model="auth.name" placeholder="Johnathan Doe"
                           class="form-control form-control-line"></div>
            </div>
            <div class="form-group">
                <label for="example-email" class="col-md-12">Email</label>
                <div class="col-md-12">
                    <input type="email" v-model="auth.email" placeholder="johnathan@admin.com"
                           class="form-control form-control-line"
                           name="example-email" id="example-email" readonly></div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Phone No</label>
                <div class="col-md-12">
                    <input type="text" placeholder="123 456 7890"
                           v-model="auth.phone" class="form-control form-control-line"></div>
            </div>
            <div class="form-group">
                <label class="col-sm-12">Gender</label>
                <div class="col-sm-12">
                    <select v-model="auth.profile.gender" class="form-control form-control-line">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Address</label>
                <div class="col-md-12">
                    <textarea v-model="auth.profile.address" rows="5" class="form-control form-control-line"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12">Bio</label>
                <div class="col-md-12">
                    <textarea v-model="auth.profile.bio" rows="5" class="form-control form-control-line"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <button class="btn btn-success">Update Profile</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            //
        },
        data: () => ({
            auth: {
                profile: []
            }
        }),
        mounted: function () {
            this.getProfile();
        },
        methods: {
            getProfile() {
                let currentObj = this;
                axios.get(this.$baseURL + 'profile')
                    .then(response => {
                        currentObj.auth = response.data.data
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            updateProfile(user) {
                this.$store.dispatch('profile/authStore', user);
            }
        }
    }
</script>
