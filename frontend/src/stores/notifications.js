import { defineStore } from 'pinia'

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    messages: [],
  }),

  actions: {

    addMessagesAll(messages){
      this.messages = messages;
    },

    addMessage(message){
      this.messages.push(message);
    },

    clearMessage(){
      this.messages = [];
    },
  },
});
