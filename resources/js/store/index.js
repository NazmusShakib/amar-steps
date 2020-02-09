import Vue from 'vue';
import Vuex from 'vuex';
import profile from './profile';
Vue.use(Vuex);

export default new Vuex.Store({
    strict: true,
    state: {
        globalUser: localStorage.getItem('auth')
    },
    getters: {
        globalAuth(state) {
            return state.globalUser
        }
    },
    modules: {
        profile: profile,
    }
});
