<template>
  <div>
    <div v-if="sidebarToggle">
      <i class="fas fa-bars sidebartoggle" @click="toggleSidebar"></i>
    </div>

    <div class="app">
      <aside :class="{'slideIn': sidebar, 'slideOut': !sidebar}">
        <sidebar></sidebar>
      </aside>
      <div class="content" :class="{'slideIn thin': sidebar, 'slideOut': !sidebar}">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sidebarToggle: false,
      sidebar: true
    };
  },
  created() {
    window.addEventListener("resize", this.resize);
    this.resize();
  },
  methods: {
    resize() {
      this.sidebar = window.innerWidth > 680;
      this.sidebarToggle = window.innerWidth <= 680;
    },
    toggleSidebar() {
      this.sidebar = !this.sidebar;
    }
  }
};
</script>

<style lang="scss">
@import "variables";

body {
  margin: 0px;
  padding: 0px;
  width: 100vw;
  height: 100vh;

  background-color: $color-primary-1;
}

*::-webkit-scrollbar {
  display: none;
}

.sidebartoggle {
  font-size: 48px;
  color: white;
  position: relative;
  top: 10px;
  left: 10px;
}

.spinner {
  color: white;
  font-size: 32px;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}

.app {
  position: absolute;
  display: flex;
  left: 50%;
  top: 50%;
  width: 90%;
  height: 90%;
  border-radius: 25px;
  overflow: hidden;
  background-color: $color-primary-0;

  transform: translate(-50%, -50%);
}

.slideIn {
  transform: translateX(0);
  transition: all 0.5s;
}

.slideOut {
  transform: translateX(-300px);
  transition: all 0.5s;
}

.content {
  float: left;
  padding: 10px;
  min-width: 100%;
  overflow-y: scroll;
}

.thin {
  min-width: calc(100% - 300px);
}

.drop-preview {
  background-color: $color-primary-2;
  opacity: 0.4;
  border: 1px dashed #abc;
  margin: 5px 5px 5px 5px;
}

@media screen and (max-width: 680px) {
  .app {
    height: 80%;
  }
}
</style>
