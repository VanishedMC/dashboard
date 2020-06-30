require('./bootstrap');

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Sidebar from "./components/Sidebar";

Vue.component('sidebar', Sidebar);

store.dispatch('getUserFromMeta')

window.vm = new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
