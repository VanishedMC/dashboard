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

      <li v-if="this.User.hasPermission('UPLOAD_IMAGE')">
        <router-link to="/images">Images</router-link>
        <hr />
      </li>

      <li v-if="this.User.hasPermission('CREATE_SHORT_URL')">
        <router-link to="/urls">Short links</router-link>
        <hr />
      </li>

      <li>
        <router-link
          to="/reminders"
          :event="discord ? 'click' : ''"
          @click.native="discordReminder"
        >Reminders</router-link>
        <hr />
      </li>

      <li v-if="this.User.hasPermission('YOUTUBE')">
        <router-link to="/youtube">Youtube</router-link>
        <hr />
      </li>

      <h4 v-if="this.User.hasPermission('ADMIN')">Admin page</h4>

      <li v-if="this.User.hasPermission('USER_OVERVIEW')">
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
      return this.$store.state.User.User;
    },
    discord() {
      return this.User.in_guild;
    }
  },
  methods: {
    discordReminder() {
      if (!this.User.in_guild) {
        this.$alert({
          title: "Not in the discord server",
          message:
            "To use reminders, you have to be in the discord server. Would you like to join?",
          buttons: [
            {
              text: "Join!",
              type: "success",
              action: () => {
                window
                  .open(process.env.MIX_DISCORD_GUILD_INVITE, "_blank")
                  .focus();
              }
            }
          ]
        });
      }
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
