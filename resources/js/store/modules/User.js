const state = {
  User: {}
};

const getters = {
  User: state => state.User
};

const actions = {
  getUserFromMeta({ commit }) {
    let User = JSON.parse(document.querySelector('meta[name=user]').content);
    
    User.hasPermission = function(searchPerm) {
      for (let perm in User.permissions) {
        if (User.permissions[perm].name === searchPerm) return true;
      }
      return false;
    }
    
    commit('setUser', User);
  }
};

const mutations = {
  setUser: (state, User) => {
    state.User = User
  },
};

export default {
  state,
  getters,
  actions,
  mutations
};
