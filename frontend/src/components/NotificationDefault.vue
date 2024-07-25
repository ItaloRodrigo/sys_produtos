<template>

  <v-div class="notification-container">
    <v-div v-for="(notification, index) in notifications" :key="notification.id"
    :class="['notification', `notification--${notification.type}`]"
    :style="{ bottom: `${index * (notificationHeight + gap)}px` }"
    @transitionend="removeNotification(index)"
    ref="notificationItem">

    <v-row class="justify-content-between" >
      <v-col cols="auto">
        <strong>{{ notification.title }}</strong>
      </v-col>
      <v-col cols="auto">
        <v-icon icon="mdi mdi-alert-circle-outline" class="cursor" @click="removeNotification(index)"></v-icon>
      </v-col>
    </v-row>

      <v-row>
        <v-col cols="auto">
          {{ notification.message }}
        </v-col>
      </v-row>

    </v-div>

  </v-div>

</template>

<script>
export default {
  name: 'NotificationDefault',

  props: {
      notificationDuration: {
          type: Number,
          default: 3000
      },
      gap: {
          type: Number,
          default: 10
      }
  },

  data() {
      return {
          notifications: [],
          notificationHeight: 0
      };
  },

  mounted() {
      this.updateNotificationHeight();
  },

  updated() {
      this.updateNotificationHeight();
  },

  watch: {
      notifications: {
          handler(notifications) {
              if (notifications.length > 1) {
                  setTimeout(() => {
                      this.removeNotification(0);
                  }, this.notificationDuration);
              }
          },
          deep: true
      }
  },

  methods: {
      addNotification(notification) {
          this.notifications.push(notification);
          setTimeout(() => {
              this.removeNotification(0);
          }, this.notificationDuration);
      },

      removeNotification(index) {
          this.notifications.splice(index, 1);
      },

      updateNotificationHeight() {
          this.$nextTick(() => {
              const notificationItem = this.$refs.notificationItem;
              if (notificationItem && notificationItem.length > 0) {
                  this.notificationHeight = notificationItem[0].offsetHeight;
              }
          });
      }
  },
};
</script>

<style scoped>
@import "@/assets/css/notification.css";
</style>
