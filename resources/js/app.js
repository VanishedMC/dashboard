require('./bootstrap');

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import Sidebar from "./components/Sidebar";

Vue.component('sidebar', Sidebar);

new Vue({
  router,
  render: h => h(App)
}).$mount("#app");
