const state = {
  Alert: null
};

let id = 0;

const getters = {
  getAlert: state => state.Alert
};

const actions = {
  sendAlert({ commit }, alert) {
    if (alert != null) {
      alert.id = id++;
      alert.buttons.forEach(button => button.id = id++);
      alert.buttons.push({
        text: 'Close',
        type: 'error',
        action: () => {
          vm.$store.dispatch('sendAlert', null);
        }
      });
    }
    commit('setAlert', alert);
  }
};

const mutations = {
  setAlert: (state, alert) => {
    state.Alert = alert;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
