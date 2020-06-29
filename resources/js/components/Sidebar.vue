<template>
  <div class="sidebar">
    <div class="user">
      <img :src="User.avatar_url" alt="Avatar" />
      <p>{{User.name}}</p>
    </div>

    <ul>
      <h4>User page</h4>

      <li>
        <router-link to="/">Home</router-link>
        <hr />
      </li>

      <li>
        <router-link to="/account">Account overview</router-link>
        <hr />
      </li>

      <li>
        <router-link to="/">Images</router-link>
        <hr />
      </li>

      <h4>Admin page</h4>

      <li v-if="User.hasPermission('USER_OVERVIEW')">
        <router-link to="/admin/users">Users overview</router-link>
        <hr />
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  computed: {
    User() {
      return User;
    }
  }
};
</script>

<style lang="scss" scoped>
@import "../variables";

* {
  color: white;
}

hr {
  border-color: white;
}

h4 {
  margin-top: 50px;
}

.sidebar {
  background-color: $color-primary-2;
  border-bottom-left-radius: 25px;
  border-top-left-radius: 25px;
  padding: 50px 50px 15px 50px;
  overflow-y: scroll;
  height: 100%;
  width: 300px;
  min-width: 300px;
  max-width: 300px;
}

.user {
  text-align: center;
}

.user > img {
  border-radius: 50%;
}

ul {
  list-style: none;
  padding: 0px;
}
</style>
