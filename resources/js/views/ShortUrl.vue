<template>
  <div class="urlOverview">
    <input type="text" class="hidden" ref="hidden" />
    <div v-if="loading">
      <div class="spinner" v-if="loading">
        <i class="fas fa-sync-alt fa-spin"></i>
      </div>
    </div>
    <table v-else>
      <tr>
        <th>ID</th>
        <th>URL</th>
        <th>Delete</th>
      </tr>
      <tr v-for="url in urls" :key="url.id">
        <td>
          <a @click="copy(url.uuid)">
            <code>u{{url.uuid}}</code>
          </a>
        </td>
        <td>
          <a :href="url.url" target="_blank">
            <code>{{url.url}}</code>
          </a>
        </td>
        <td>
          <button class="btn btn-danger" @click="deleteUrl(url.id)">Delete</button>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      urls: []
    };
  },
  mounted() {
    this.load();

    Echo.private(`url.created.${this.User.id}`).listen('ShortUrlCreated', (e) => {
      this.urls.push(e.url);
      this.$notify({
        type: 'success',
        title: 'New URL',
        message: 'A new URL was created, and has been loaded!'
      });
    });
  },
  beforeDestroy() {
    Echo.leave(`url.created.${this.User.id}`);
  },
  methods: {
    load() {
      axios
        .get("/api/url/list")
        .then(res => {
          this.urls = res.data;
          this.loading = false;
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Something went wrong",
            message:
              "Something went wrong loading your short urls. Please try again later."
          });
        });
    },
    deleteUrl(id) {
      axios
        .delete(`/api/url/${id}`)
        .then(res => {
          this.load();
          this.$notify({
            type: "success",
            title: "Url deleted",
            message: "This url has been deleted"
          });
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Something went wrong",
            message:
              "Something went wrong deleting this URL. Please try again later."
          });
        });
    },
    copy(url) {
      this.$refs.hidden.value = `${process.env.MIX_SITE_URL}u${url}`;
      this.$refs.hidden.select();
      document.execCommand("copy");

      this.$notify({
        type: "success",
        title: "URL copied",
        message: "Short URL has been copied to your clipboard"
      });
    }
  },
  computed: {
    User() {
      return this.$store.state.User.User;
    }
  },
};
</script>

<style lang="scss" scoped>
@import "../variables";

.hidden {
  position: absolute;
  left: -10000px;
  top: -10000px;
}

.urlOverview {
  overflow-y: scroll;
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

td,
th {
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