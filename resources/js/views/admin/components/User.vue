<template>
  <div>
    <div class="spinner" v-if="loading">
      <i class="fas fa-sync-alt fa-spin"></i>
    </div>
    <div class="permissions" v-if="!loading">
      <div class="group">
        <Container
          drag-class="card-ghost-left"
          group-name="permissions"
          drop-class="card-ghost-drop"
          :get-ghost-parent="getGhostParent"
          :get-child-payload="getUserPayload"
          :drop-placeholder="dropPlaceholderOptions"
          @drop="onUserDrop($event)"
        >
          <Draggable v-for="permission in manageUser.permissions" :key="permission.id">
            <div class="draggable-item">{{permission.name}}</div>
          </Draggable>
        </Container>
      </div>

      <div class="group">
        <Container
          drag-class="card-ghost-right"
          group-name="permissions"
          drop-class="card-ghost-drop"
          :get-ghost-parent="getGhostParent"
          :get-child-payload="getInactivePayload"
          :drop-placeholder="dropPlaceholderOptions"
          @drop="onDrop('availablePermissions', $event)"
        >
          <Draggable v-for="item in availablePermissions" :key="item.id">
            <div class="draggable-item">{{item.name}}</div>
          </Draggable>
        </Container>
      </div>
    </div>
  </div>
</template>

<script>
import { Container, Draggable } from "vue-smooth-dnd";

export default {
  components: { Container, Draggable },
  props: ["id"],

  data() {
    return {
      loading: true,
      manageUser: {},
      availablePermissions: [],
      permissions: [],
      dropPlaceholderOptions: {
        className: "drop-preview",
        animationDuration: "150",
        showOnTop: true
      }
    };
  },
  mounted() {
    this.load();

    Echo.private(`admin.permissions.${this.id}`).listen(
      `PermissionChange`,
      e => {
        this.manageUser.permissions = e.user.permissions;

        this.availablePermissions = this.permissions.filter(
          perm => !this.manageUser.hasPermission(perm.name)
        );
      }
    );
  },
  methods: {
    async load() {
      let response = await axios.get(`/api/admin/users/${this.id}`);
      this.manageUser = response.data;

      this.manageUser.hasPermission = perm => {
        for (let i in this.manageUser.permissions) {
          if (this.manageUser.permissions[i].name == perm) return true;
        }
      };

      response = await axios.get(`/api/admin/permissions`);
      this.permissions = response.data;

      this.availablePermissions = this.permissions.filter(
        perm => !this.manageUser.hasPermission(perm.name)
      );
      this.manageUser.permissions = this.manageUser.permissions.sort(
        (p1, p2) => p1.id - p2.id
      );

      this.loading = false;
    },

    grantPermission(perm) {
      axios.put(`/api/admin/permissions/grant`, {
        user_id: this.manageUser.id,
        permission_id: perm
      });
    },

    revokePermission(perm) {
      axios.put(`/api/admin/permissions/revoke`, {
        user_id: this.manageUser.id,
        permission_id: perm
      });
    },

    onUserDrop(dropResult) {
      this.manageUser.permissions = this.applyDrag(
        this.manageUser.permissions,
        dropResult
      );

      if (dropResult.addedIndex != null && dropResult.removedIndex == null) {
        this.grantPermission(dropResult.payload.id);
      } else if (
        dropResult.removedIndex != null &&
        dropResult.addedIndex == null
      ) {
        this.revokePermission(dropResult.payload.id);
      }
    },

    onDrop(collection, dropResult) {
      this[collection] = this.applyDrag(this[collection], dropResult);
    },

    getUserPayload(index) {
      return this.manageUser.permissions[index];
    },

    getInactivePayload(index) {
      return this.availablePermissions[index];
    },

    getGhostParent() {
      return document.body;
    },

    applyDrag(arr, dragResult) {
      const { removedIndex, addedIndex, payload } = dragResult;
      if (removedIndex === null && addedIndex === null) return arr;

      const result = [...arr];
      let itemToAdd = payload;

      if (removedIndex !== null) {
        itemToAdd = result.splice(removedIndex, 1)[0];
      }

      if (addedIndex !== null) {
        result.splice(addedIndex, 0, itemToAdd);
      }

      return result;
    }
  }
};
</script>

<style lang="scss" scoped>
@import "../../../variables";

.permissions {
  display: flex;
  justify-content: stretch;
  margin-top: 50px;
  margin-right: 50px;
  transition: all 0.2s ease-in;
}
.group {
  margin-left: 50px;
  flex: 1;
  background-color: $color-primary-1;
  height: 30vh;
  padding: 4px;
  transition: all 0.2s ease-in;
}

.smooth-dnd-container {
  overflow-y: scroll;
  height: 29vh;
}

.draggable-item,
.draggable-item-horizontal {
  text-align: center;
  display: block;
  background-color: $color-primary-2;
  outline: 0;
  border: 1px solid $color-primary-3;
  color: white;
  cursor: default;
}

.draggable-item {
  height: 50px;
  line-height: 50px;
  margin: 3px 5px 3px 5px;
  user-select: none;
}

.card-ghost-left {
  transition: transform 0.18s ease;
  transform: rotateZ(5deg);
}

.card-ghost-right {
  transition: transform 0.18s ease;
  transform: rotateZ(-5deg);
}

.card-ghost-drop {
  transition: transform 0.18s ease-in-out;
  transform: rotateZ(0deg);
}

@media screen and (max-width: 1000px) {
  .group {
    margin-left: 5px;
    padding: 7px;
    transition: all 0.2s ease-in;
  }
  .permissions {
    margin-right: 5px;
    transition: all 0.2s ease-in;
  }

  .smooth-dnd-container {
    height: 28vh;
  }
}
</style>