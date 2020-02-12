<template>
    <div class="tab-pane" id="update-profile">
        <form class="form-horizontal form-material" @submit.prevent="updateProfile()">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12" for="full-name">Full name</label>
                        <div class="col-md-12">
                            <input type="text" v-model="auth.name" id="full-name"
                                   v-validate="'required'" name="name" placeholder="Full name"
                                   class="form-control form-control-line">
                            <div v-show="errors.has('name')" class="help text-danger">
                                {{ errors.first('name') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12" for="email">Email</label>
                        <div class="col-md-12">
                            <input type="email" name="email" v-model="auth.email" placeholder="Email"
                                   v-validate="'required'" class="form-control form-control-line"
                                   id="email">
                            <div v-show="errors.has('email')" class="help text-danger">
                                {{ errors.first('email') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12" for="phone">Phone</label>
                        <div class="col-md-12">
                            <input type="text" placeholder="Phone" name="phone" id="phone"
                                   v-validate="'required'" v-model.trim="auth.phone"
                                   class="form-control form-control-line" readonly>
                            <div v-show="errors.has('phone')" class="help text-danger">
                                {{ errors.first('phone') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-12">Gender</label>
                        <div class="col-sm-12">
                            <select v-model="auth.profile.gender"
                                    class="form-control form-control-line">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12" for="height">Height (kg)</label>
                        <div class="col-md-12">
                            <input type="number" min="0" placeholder="Height" name="height" id="height"
                                   v-validate="'required'" v-model.trim="auth.height"
                                   class="form-control form-control-line">
                            <div v-show="errors.has('height')" class="help text-danger">
                                {{ errors.first('height') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-12">Weight (cm)</label>
                        <div class="col-md-12">
                            <input type="number" min="0" placeholder="Weight" name="weight" id="weight"
                                   v-validate="'required'" v-model.trim="auth.weight"
                                   class="form-control form-control-line">
                            <div v-show="errors.has('weight')" class="help text-danger">
                                {{ errors.first('weight') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="col-md-12" for="address">Address</label>
                <div class="col-md-12">
                    <textarea v-model="auth.profile.address" id="address"
                              rows="5" class="form-control form-control-line"/>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-12" for="bio">Bio</label>
                <div class="col-md-12">
                    <textarea v-model="auth.profile.bio" id="bio"
                              rows="5" class="form-control form-control-line"/>
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

            updateProfile() {
                let authObj = this.auth;
                let input = {
                    name: authObj.name,
                    email: authObj.email,
                    height: authObj.height,
                    weight: authObj.weight,
                    bio: authObj.profile.bio,
                    gender: authObj.profile.gender,
                    address: authObj.profile.address
                };

                axios.post(this.$baseURL + 'profile', input)
                    .then(response => {
                        // this.$validator.reset();
                        this.$notification.success(response.data.message);
                        this.$store.dispatch("setGlobalAuth", response.data.data);
                    }).catch(error => {
                    this.$setErrorsFromResponse(error.response.data);
                    this.$notification.error(error.response.data.message);
                });
            }
        }
    }
</script>
