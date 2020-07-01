<template>
  <div>
    <div v-if="loading">
      <div class="spinner" v-if="loading">
        <i class="fas fa-sync-alt fa-spin"></i>
      </div>
    </div>
    <div v-else class="images">
      <div v-for="image in images" :key="image.id">
        <router-link :to="'images/' + image.id">
          <div class="img-preview">
            <div class="img-holder">
              <img v-if="image.public" :src="'storage/images/' + image.file" />
              <img v-else :src="'image' + image.uuid" />
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      images: [],
      loading: true
    };
  },
  created() {
    Echo.private(`image.uploaded.${this.User.id}`).listen("ImageUploaded", e => {
      this.images.push(e.image);
      this.$notify({
        type: 'success',
        title: 'New Image',
        message: 'A new image was uploaded, and has been loaded!'
      });
    });
  },
  beforeDestroy() {
    Echo.leave(`image.uploaded.${this.User.id}`);
  },
  mounted() {
    this.load();
  },
  methods: {
    load() {
      axios.get("/api/image/list").then(response => {
        this.images = response.data;
        this.loading = false;
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
.images {
  display: grid;
  grid-template-columns: repeat(auto-fit, 136px);
  column-gap: 20px;
  row-gap: 20px;
}

.img-preview {
  width: 136px;
  height: 136px;

  display: flex;
  justify-content: center;

  background-color: $color-primary-1;
}

.img-holder {
  overflow: hidden;
  max-width: 128px;
  max-height: 128px;

  margin-top: 4%;
}

img {
  max-width: 100%;
  height: auto;
}
</style>