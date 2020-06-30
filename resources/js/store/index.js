import Vuex from 'vuex';
import Vue from 'vue';
import User from './modules/User'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    User
  }
});