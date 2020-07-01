const state = {
  Notifications: [],
  Queue: []
}

let id = 0;

const getters = {
  getAllNotifications: state => state.Notifications
}

const actions = {
  addNotification({ commit }, notification) {
    prepareNotification(notification);
    commit('addNotification', notification);
  },
  queueNotification({ commit }, notification) {
    prepareNotification(notification);
    commit('queueNotification', notification);
  },
  removeNotification({ commit }, id) {
    commit('removeNotification', id);
  }
}

const mutations = {
  addNotification: (state, notification) => state.Notifications.push(notification),
  queueNotification: (state, notification) => state.Queue.push(notification),

  removeNotification: (state, id) => {
    state.Notifications = state.Notifications.filter(notification => notification.id !== id);

    if (state.Queue.length > 0) {
      let next = state.Queue[0];
      state.Queue.splice(0, 1);
      setTimeout(() => {
        state.Notifications.push(next);
      }, 1);
    }
  }
}

const prepareNotification = (notification) => {
  notification.id = id++;
  notification.fadeOut = false;

  notification.isFirst = () => {
    if (state.Notifications.length > 0) {
      return notification.id == state.Notifications[0].id;
    } else return false;
  }
}

export default {
  state,
  getters,
  actions,
  mutations
}