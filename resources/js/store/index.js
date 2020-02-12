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
        SET_GLOBAL_AUTH: (state, payload) => {
            localStorage.set('auth', JSON.stringify(payload));
            state.globalUser = localStorage.get('auth');
        },
        GLOBAL_LOGOUT: (state) => {
            localStorage.clear();
            state.globalUser = [];
        }
    },
    actions: {
        setGlobalAuth({commit}, payload) {
            return new Promise((resolve, reject) => {
                commit('SET_GLOBAL_AUTH', payload);
                resolve();
            }).then(() => {
                // console.log('Yay! updateAuth')
            }).catch((error) => {
                // console.log(error.response);
                reject();
            });
        },
        globalLogout({commit}) {
            commit('GLOBAL_LOGOUT');
        }
    },
    modules: {
        profile: profile,
    }
});
