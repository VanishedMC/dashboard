<template>
  <div>
    <div v-if="loading">
      <div class="spinner" v-if="loading">
        <i class="fas fa-sync-alt fa-spin"></i>
      </div>
    </div>
    <div v-else>
      <div v-if="videoInformation != null">
        <div v-if="!informationFinishedLoading">
          <span>Please wait while the information is loading</span>
        </div>
        <div v-else>
          <div v-for="video in videoInformation.data" :key="video.id">
            <span>{{video.title}}</span>
            <br />
          </div>
          <div v-if="videoInformation.status == 0">
            <button @click="startDownload">Download</button>
            <button @click="cancelSearch">Search different video</button>
          </div>
          <div v-else-if="videoInformation.status == 1">
            <span>Your files are downloading</span>
          </div>
          <div v-else>
            <span>Files finished downloading</span>
            <button @click="finishDownload">Download</button>
          </div>
        </div>
      </div>
      <div v-else>
        <input type="text" v-model="searchUrl" />
        <button @click="search">Search</button>
      </div>
    </div>
  </div>
</template>

<script>
const FileDownload = require("./../helpers/js-file-download");

export default {
  data() {
    return {
      loading: true,
      videoInformation: null,
      download: false,
      searchUrl: "",
    };
  },
  mounted() {
    this.loadVideoInformation();

    Echo.private(`youtube.${this.User.id}`)
      .listen("YoutubeInformationFinishedLoading", () => {
        this.$notify({
          type: "success",
          title: "Finished loading",
          message: "The system loaded your video's information"
        });
        this.loadVideoInformation();
      })
      .listen("YoutubeStartedDownloading", () => {
        this.$notify({
          type: "success",
          title: "Started download",
          message: "Your download has started!"
        });
        this.loadVideoInformation();
      })
      .listen("YoutubeFinishedDownloading", () => {
        this.$notify({
          type: "success",
          title: "Finished downloading",
          message: "Your download has finished and is ready!"
        });
        this.loadVideoInformation();
      });
  },
  methods: {
    loadVideoInformation() {
      axios
        .get("/youtube/information")
        .then(res => {
          if (res.status == 200) {
            this.videoInformation = res.data;
            this.videoInformation.data = JSON.parse(this.videoInformation.data);
          }
          this.loading = false;
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Error!",
            message: "Something has gone wrong, please try again later"
          });
        });
    },
    search() {
      axios
        .post("/youtube/information", {
          url: this.searchUrl
        })
        .then(res => {
          this.$notify({
            type: "success",
            title: "Searching",
            message:
              "Please wait while the system will load your video's information"
          });
          this.loadVideoInformation();
        })
        .catch(err => {
          console.log(err);
        });
    },
    startDownload() {
      axios.post("/youtube/download");
    },
    finishDownload() {
      FileDownload();
      this.reset();
    },
    cancelSearch() {
      axios.post("/youtube/reset").then(() => {
        this.reset();
        this.loadVideoInformation();
      });
    },
    reset() {
      this.loading = true;
      this.videoInformation = null;
      this.download = false;
      this.searchUrl = "";
    }
  },
  computed: {
    User() {
      return this.$store.state.User.User;
    },
    informationFinishedLoading() {
      return (
        this.videoInformation != null && this.videoInformation.data != null
      );
    }
  },
  beforeDestroy() {
    Echo.leave(`youtube.information.${this.User.id}`);
  }
};
</script>

<style lang="scss" scoped>
span {
  color: white;
}
</style>