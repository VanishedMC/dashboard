<template>
  <div class="usersOverview">
    <div class="spinner" v-if="loading">
      <i class="fas fa-sync-alt fa-spin"></i>
    </div>
    <table v-if="!loading">
      <tr>
        <th></th>
        <th>id</th>
        <th>Name</th>
        <th v-if="hasManagePerm">Manage user</th>
      </tr>
      <tr v-for="user in users" :key="user.id">
        <td>
          <img :src="user.avatar_url" alt="avatar" />
        </td>
        <td><code>{{user.id}}</code></td>
        <td>{{user.name}}</td>
        <td v-if="hasManagePerm">
          <router-link :to="`/admin/users/${user.id}`">Manage</router-link>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      users: [],
      loading: true
    };
  },

  mounted() {
    this.onLoad();
  },

  methods: {
    onLoad() {
      axios.get("/api/admin/users").then(data => {
        this.users = data.data;
        this.loading = false;
      });
    }
  },
  computed: {
    hasManagePerm() {
      return User.hasPermission('MANAGE_USER');
    }
  }
};
</script>

<style lang="scss" scoped>
@import "../../variables";

.usersOverview {
  overflow-y: scroll;
}

img {
  width: 32px;
  height: 32px;
  border-radius: 50%;
}

table {
  position: absolute;
  top: 10%;
  left: 50%;
  transform: translate(-50%, 0%);
  color: white;
  overflow: scroll;
  margin-bottom: 50px;
}

tr {
  border-bottom: 1px solid white;
}

td,th {
  padding: 15px;
  text-align: center;
  vertical-align: middle;
}

code {
  background-color: $color-primary-1;
  padding: 5px;
  color: white;
  border-radius: 5px;
}
</style>