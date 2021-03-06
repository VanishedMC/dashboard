window._ = require('lodash');
import Echo from 'laravel-echo';

try {
  window.Popper = require('popper.js').default;
  window.$ = window.jQuery = require('jquery');

  require('bootstrap');
} catch (e) { }

window.axios = require('axios');
window.csrf = document.querySelector('meta[name=csrf-token]').content;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.querySelector('meta[name=api_token]').content;

window.Pusher = require('pusher-js');

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.MIX_PUSHER_APP_KEY,
  cluster: process.env.MIX_PUSHER_APP_CLUSTER,
  encrypted: false,
  auth: {
    headers: {
      Authorization: 'Bearer ' + document.querySelector('meta[name=api_token]').content
    }
  }
});
