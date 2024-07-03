import Vuex from 'vuex';
import vue from 'vue';

vue.use(Vuex);

export const store = new Vuex.Store({

    state: {
        token: localStorage.getItem('auth') || '',
    },
    mutations: {

        isPasswordUpdated(state, status) {
            localStorage.setItem('password_status', status)
            state.password_status = status;
        },
        setToken(state, token) {
            localStorage.setItem('auth', token)
            state.token = token;
        },

        setRole(state, role) {
            localStorage.setItem('role', role)
            state.role = role;
        },
        setParent(state, parent) {
            localStorage.setItem('parent', parent)
            state.parent = parent;
        },

        clearToken(state) {
            localStorage.removeItem('auth')
            localStorage.removeItem('parent')
            state.token = '';
            state.parent = null;
        }
    }

})
