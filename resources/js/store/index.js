import Vuex from 'vuex';
import Vue from 'vue';
import User from './modules/User'
import Alert from './modules/Alert';
import Notifications from './modules/Notifications';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    User,
    Alert,
    Notifications
  }
});
