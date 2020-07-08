<template>
  <div>
    <div id="alert-box"></div>
    <div id="alert">
      <div class="title">
        <h1>{{alert.title}}</h1>
      </div>
      <div class="message">{{alert.message}}</div>
      <div class="buttons">
        <button
          v-for="button in alert.buttons"
          @click="button.action"
          :class="button.type"
          :key="button.id"
        >{{button.text}}</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["alert"]
};
</script>

<style lang="scss" scoped>
@import "../variables";

#alert-box {
  min-width: 100%;
  min-height: 100%;
  background-color: black;
  top: 0px;
  left: 0px;
  position: absolute;
  opacity: 0.5;
}

#alert {
  border-radius: 25px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: center;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 40vw;
  height: 20vh;
  background-color: $color-primary-2;
  padding: 10px;
  color: white;
  animation: slideIn;
  animation-duration: 0.2s;
  min-width: 250px;
}

#alert > .title {
  height: 15%;
  margin-bottom: 40px;
}

#alert > .message {
  height: 80%;
  margin-bottom: 20px;
}

#alert > .buttons {
  display: flex;
  justify-content: center;
}

#alert > .buttons > button {
  border: none;
  color: white;
  border-radius: 5px;
  width: 150px;
  margin: 0px 10px;
  padding: 10px;
}

@keyframes slideIn {
  0% {
    transform: translate(-50%, -100vh);
  }
  100% {
    transform: translate(-50%, -50%);
  }
}

#alert > .buttons > .warn {
  background-color: rgb(200, 157, 14);
}
#alert > .buttons > .error {
  background-color: rgb(235, 11, 11);
}
#alert > .buttons > .success {
  background-color: rgb(0, 160, 0);
}
#alert > .buttons > .generic {
  background-color: rgb(0, 119, 255);
}
</style>