import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},

    mutations: {
        // ToDo: I do not like that there appears to be no mapping helper to map mutators AND getters at the same time
        // with the same name, but apparently this is currently the case with Vuex right now
        setMap(state, map) {
            state.map = map;
        }
    }

});