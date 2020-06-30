window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
  window.Popper = require('popper.js').default;
  window.$ = window.jQuery = require('jquery');

  require('bootstrap');
} catch (e) { }

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + document.querySelector('meta[name=api_token]').content;
// window.User = JSON.parse(document.querySelector('meta[name=user]').content);

// User.hasPermission = function(searchPerm) {
//   for(let perm in User.permissions) {
//     if(User.permissions[perm].name === searchPerm) return true;
//   }
//   return false;
// }

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'f98d8108f0d2b67f667e',
    cluster: 'eu',
    encrypted: true,
    auth: {
      headers: {
        Authorization: 'Bearer ' + document.querySelector('meta[name=api_token]').content
      }
    }
});
