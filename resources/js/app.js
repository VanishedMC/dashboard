require('./bootstrap');

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Sidebar from "./components/Sidebar";
import Notification from "./components/Notification";

Vue.component('sidebar', Sidebar);
Vue.component('notification', Notification);

Vue.prototype.$notify = (notification) => {
  if(store.state.Notifications.Notifications.length >= 5) {
    store.dispatch('queueNotification', notification);
  } else {
    store.dispatch('addNotification', notification);
  }
}

store.dispatch('getUserFromMeta');

window.vm = new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
