import {computed, ref} from 'vue';
import { defineStore } from "pinia";
import { api, except } from "../plugins/api";
// import Cookies from "js-cookie";

export const auth_store = defineStore('auth',{
  state: () => ({
    user: ref(JSON.parse(localStorage.getItem("user")))?ref(JSON.parse(localStorage.getItem("user"))):null,
    counter: 0,
  }),
  getters: {
    isAuth(state) {
      return state.user != null;
    },
    getUser(state) {
      return state.user;
    },
    getName(state) {
      if (state.user != null) return state.user.user.name;
      return "";
    },
    getId(state) {
      if (state.user != null) return state.user.user.id;
      return "";
    },
    token(state) {
      // return state.user != null ? "Bearer " + state.user.access : "";
      return state.user != null ? "Bearer " + state.user.token : "";
    },
    getCounter(state) {
      return state.counter;
    },
  },
  actions: {
    async login(user) {
      await api(this)
        .post("auth/login", user)
        .then((res) => {
          this.user = res.data.data;
        });
      localStorage.setItem('user', JSON.stringify(this.user));
      return this.user;
    },
    async logout(ctx = {}) {
      let data = null;
      await api(ctx)
        .post("auth/logout", { id: this.user.user.id })
        .then((res) => {
          data = res.data;
        })
        .catch((e) => console.log(ctx, e));
      this.user = null;
      localStorage.removeItem('user');
      return data;
    },
    async isLoged() {
      if (this.user.user != null) {
        await api(this)
          .get("auth/isloged/"+this.user.user.id)
          .then((res) => {
            this.user = res.data;
            return true;
          })
          .catch((e) => console.log(this, e));
      }
      return false;
    },
    async refresh() {},
    increment() {
      this.counter = this.counter + 1;
    },
  },
  persist: true,
  // persist: {
  //   key: "auth",
  //   enable:true,
  //   storage: {
  //     getItem: (key) => Cookies.get(key),
  //     setItem: (key, value) => Cookies.set(key, value),
  //     removeItem: (key) => Cookies.remove(key),
  //   },
  // },
});
