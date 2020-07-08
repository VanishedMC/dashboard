require('./bootstrap');

import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import Alert from "./components/Alert";
import Sidebar from "./components/Sidebar";
import Notification from "./components/Notification";

Vue.component('alert', Alert);
Vue.component('sidebar', Sidebar);
Vue.component('notification', Notification);

Vue.prototype.$notify = (notification) => {
  if (store.state.Notifications.Notifications.length >= 5) {
    store.dispatch('queueNotification', notification);
  } else {
    store.dispatch('addNotification', notification);
  }
}

Vue.prototype.$alert = (alert) => {
  store.dispatch('sendAlert', alert);
}

store.dispatch('getUserFromMeta');

window.vm = new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");

if (!store.state.User.User.in_guild && store.state.User.User.ask_invite) {
  vm.$alert({
    title: 'Not in the discord server',
    message: 'You are currently not in our discord server, which limits your accessible features. Would you like to join the server?',
    buttons: [
      {
        text: 'Join!',
        type: 'success',
        action: () => {
          window.open(process.env.MIX_DISCORD_GUILD_INVITE, '_blank').focus();
        }
      },
      {
        text: 'Dont ask again',
        type: 'warn',
        action: () => {
          axios.post('/api/preferences/dontaskinvite');
          vm.$store.dispatch('sendAlert', null);
        }
      }
    ]
  });
}
