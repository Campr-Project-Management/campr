import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import * as mutations from './mutations';
import project from './modules/project';
import task from './modules/task';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    actions,
    mutations,
    modules: {
        project,
        task,
    },
    strict: debug,
});
