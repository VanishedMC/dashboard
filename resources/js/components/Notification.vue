<template>
  <div
    :id="'notification-' + notification.id"
    class="notification"
    :class="styles"
    @click="fadeOut"
  >
    <div class="stripe"></div>
    <div class="content">
      <span class="title">{{notification.title}}</span>
      <span class="body">{{notification.message}}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: ["notification"],
  data() {
    return {
      isFirstChildCountdown: -1,
      fadeCountDown: -1,
      deleted: false
    };
  },
  mounted() {
    this.isFirstChildCountdown = setInterval(() => {
      if (this.notification.isFirst()) {
        this.lifeCycle = setTimeout(() => {
          this.fadeOut();
        }, 5000);
        clearInterval(this.isFirstChildCountdown);
      }
    }, 50);
  },
  methods: {
    fadeOut() {
      if (this.deleted) return;
      this.deleted = true;

      clearTimeout(this.lifeCycle);
      clearInterval(this.isFirstChildCountdown);

      this.notification.fadeOut = true;

      setTimeout(() => {
        this.$store.dispatch("removeNotification", this.notification.id);
      }, 500);
    }
  },
  computed: {
    styles() {
      return (
        this.notification.type +
        " " +
        (this.notification.fadeOut ? "fadeOut" : "")
      );
    }
  }
};
</script>

<style lang="scss" scoped>
.notification {
  width: 290px;
  display: flex;
  animation: slideIn;
  animation-duration: 0.5s;
  user-select: none;
}

.fadeOut {
  animation: fadeOut;
  animation-duration: 0.5s;
  animation-fill-mode: forwards;
}

.content {
  padding: 3px;
  padding-left: 7px;
  color: white;

  height: 85px;
  margin-top: 10px;

  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
}

.stripe {
  min-width: 15px;
  min-height: 85px;
  margin-top: 10px;
  padding: 3px;
}

span.title {
  font-size: 20px;
  width: 100%;
  float: left;
}

span.body {
  font-size: 15px;
  float: left;
}

@keyframes slideIn {
  0% {
    transform: translateY(95px);
    height: 0px;
  }
  100% {
    transform: translateY(0px);
    height: 95px;
  }
}

@keyframes fadeOut {
  0% {
    height: 95px;
    opacity: 1;
  }
  50% {
    opacity: 0;
  }
  100% {
    height: 0px;
    opacity: 0;
  }
}

// Stripe colors
.warn > .stripe {
  background-color: rgb(138, 114, 38);
}
.error > .stripe {
  background-color: rgb(122, 24, 24);
}
.success > .stripe {
  background-color: rgb(2, 93, 2);
}
.generic > .stripe {
  background-color: rgb(11, 75, 148);
}

// Body colors
.warn > .content {
  background-color: rgb(200, 157, 14);
}
.error > .content {
  background-color: rgb(235, 11, 11);
}
.success > .content {
  background-color: rgb(0, 160, 0);
}
.generic > .content {
  background-color: rgb(0, 119, 255);
}
</style>
