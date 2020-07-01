<template>
  <div>
    <div v-if="loading">
      <div class="spinner" v-if="loading">
        <i class="fas fa-sync-alt fa-spin"></i>
      </div>
    </div>
    <div v-else class="image">
      <h3>Image settings</h3>
      <label for="title">Image title</label>
      <input type="text" id="title" :value="image.title" />

      <label for="uid">Image id</label>
      <input
        type="text"
        id="uid"
        :value="image.uuid"
        :disabled="this.User.hasPermission('IMAGE_CUSTOM_UID') == 0"
      />

      <div id="optionsGrid">
        <div>
          <label for="public">Public</label>
          <label class="public-checkbox">
            <input type="checkbox" id="public" :checked="image.public" />
            <span class="checkmark"></span>
          </label>
        </div>
        <div>
          <label for="delete" id="delete">Delete</label>
          <button @click="deleteImage()">Delete</button>
        </div>
        <div>
          <label for="save" id="save">Save</label>
          <button @click="update()">Save</button>
        </div>
      </div>
    </div>
    <div class="preview" v-if="!loading">
      <img v-if="image.public" :src="'/storage/images/' + image.file" />
      <img v-else :src="'/image' + image.uuid" />
    </div>
  </div>
</template>

<script>
export default {
  props: ["id"],
  data() {
    return {
      image: {},
      loading: true
    };
  },
  computed: {
    User() {
      return this.$store.state.User.User;
    }
  },
  mounted() {
    this.load();
  },
  methods: {
    load() {
      axios.get(`/api/image/${this.id}`).then(response => {
        this.image = response.data;
        this.loading = false;
      });
    },
    update() {
      let isPublic = document.querySelector("#public").checked;
      let title = document.querySelector("#title").value;
      let uid = document.querySelector("#uid").value;

      if (title.lenght == 0) title = null;

      axios
        .post(`/api/image/${this.id}/update`, {
          public: isPublic ? 1 : 0,
          title: title,
          uuid: uid
        })
        .then(response => {
          this.$notify({
            type: "success",
            title: "Updated",
            message: "Image settings have been updated!"
          });
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Error",
            message:
              "Something went wrong updating the image! Please try again later"
          });
        });
    },
    deleteImage() {
      axios
        .delete(`/api/image/${this.id}`)
        .then(response => {
          this.$router.push("/images");
        })
        .then(response => {
          this.$notify({
            type: "success",
            title: "Deleted",
            message: "The image has been deleted!"
          });
        })
        .catch(err => {
          this.$notify({
            type: "error",
            title: "Error",
            message:
              "Something went wrong updating the image! Please try again later"
          });
        });
    }
  }
};
</script>

<style lang="scss" scoped>
@import "../variables";

.preview {
  background-color: $color-primary-1;
  padding: 12px;
  max-width: 100%;
  margin-top: 10px;
  margin-left: 10px;
  border-radius: 15px;
  display: inline-block;
}

img {
  width: 100%;
  height: auto;
}

#optionsGrid {
  display: flex;
  justify-content: center;
}
#optionsGrid > div {
  margin: 0px 5px;
}

#save {
  width: 100%;
  text-align: center;
}

#save + button {
  float: right;
  background-color: rgb(21, 228, 21);
  border: none;
  padding: 2px;
  color: black;
  width: 100%;
  border-radius: 5px;
}

#delete {
  width: 100%;
  text-align: center;
}

#delete + button {
  background-color: red;
  border: none;
  padding: 2px;
  color: black;
  width: 100%;
  border-radius: 5px;
}

.image {
  background-color: $color-primary-1;
  color: white;
  padding: 10px;
  border-radius: 15px;
  margin-top: 10px;
  margin-left: 10px;
  margin-right: 100%;
  display: inline-block;
  min-width: 190px;
}

input[type="text"] {
  border: 1px solid black;
  width: 100%;
  margin-bottom: 25px;
  border-radius: 5px;
  padding: 2px;
  padding-left: 5px;
}

label {
  margin-bottom: 0px;
}

/* Customize the label (the container) */
.public-checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 30px;
  cursor: pointer;
  font-size: 22px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.public-checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
}

.public-checkbox:hover input ~ .checkmark {
  background-color: #ccc;
}

.public-checkbox input:checked ~ .checkmark {
  background-color: #2196f3;
}

.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

.public-checkbox input:checked ~ .checkmark:after {
  display: block;
}

.public-checkbox .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
</style>