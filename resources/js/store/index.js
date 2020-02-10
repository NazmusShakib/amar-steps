import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import localStorage from '~/services/localStorage';

import profile from './profile';

export default new Vuex.Store({
    strict: true,
    state: {
        globalUser: localStorage.get('auth')
    },
    getters: {
        globalAuth(state) {
            return state.globalUser
        }
    },
    mutations: {
        SET_GLOBAL_AUTH: (state) => {
            state.globalUser = localStorage.get('auth');
        },
        GLOBAL_LOGOUT: (state) => {
            localStorage.clear();
            state.globalUser = [];
        }
    },
    actions: {
        setGlobalAuth({commit}) {
            commit('SET_GLOBAL_AUTH');
        },
        globalLogout({commit}) {
            commit('GLOBAL_LOGOUT');
        }
    },
    modules: {
        profile: profile,
    }
});
