const profile = {
    namespaced: true,
    state: {
        profile: {}
    },
    mutations: {
        UPDATE_AUTH: (state, payload) => {
            state.profile = payload;
        },
        LOGOUT: (state) => {
            state.profile = [];
        }
    },
    actions: {
        authStore: (context, payload) => {
            return new Promise((resolve, reject) => {
                context.commit('UPDATE_AUTH', payload);
                resolve()
            });
        },

        storeLogout({commit}) {
            commit('LOGOUT');
        }
    },
    getters: {
        profile: state => {
            return state.profile;
        }
    }
};

export default profile
