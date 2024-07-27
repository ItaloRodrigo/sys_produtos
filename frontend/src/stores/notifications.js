import {computed, ref} from 'vue';
import { defineStore } from 'pinia'

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    messages: ref(JSON.parse(localStorage.getItem("messages")))?ref(JSON.parse(localStorage.getItem("messages"))):null,
  }),

  actions: {

    addMessagesAll(messages){
      this.messages = messages;
      localStorage.setItem('messages', JSON.stringify(this.messages));
    },

    addMessage(message){
      this.messages.push(message);
      localStorage.setItem('messages', JSON.stringify(this.messages));
    },

    clearMessage(){
      this.messages = [];
      localStorage.removeItem('messages');
    },
  },
});
