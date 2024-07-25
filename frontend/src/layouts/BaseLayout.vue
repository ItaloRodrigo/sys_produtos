<template>
  <v-card>
    <v-layout>
      <v-app-bar color="black" prominent>
        <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

        <v-btn size="small" class="border bg-primary" @click="selfback()">
          <v-icon >mdi mdi-arrow-left-circle</v-icon>
        </v-btn>

        <v-btn size="small" class="border mx-1 bg-primary" to="/">
          <v-icon >mdi-home</v-icon>
        </v-btn>

        <v-toolbar-title >{{titlecard}} </v-toolbar-title>

        <v-spacer></v-spacer>

        <template v-if="$vuetify.display.mdAndUp">
        </template>
      </v-app-bar>

      <v-navigation-drawer
        v-model="drawer"
        :location="$vuetify.display.mobile ? 'bottom' : undefined"
        temporary
      >
        <v-list>
          <v-list-subheader>
            Menu
          </v-list-subheader>
          <v-list-item v-for="item in items" :key="item.value" :title="item.title" :prepend-icon="item.icon" :value="item.value" :target="item.target" :href="item.to" link>
          </v-list-item>
        </v-list>

      </v-navigation-drawer>

      <v-main class="border-0">
        <v-container class="my-0 py-0">
          <v-breadcrumbs :items="items">
            <template v-slot:prepend>
              <v-icon icon="mdi-home" size="small"></v-icon>
            </template>
          </v-breadcrumbs>
        </v-container>

        <slot ></slot>
      </v-main>
    </v-layout>
  </v-card>
</template>

<script>
  import { ref } from 'vue';

  export default {
    name: "BaseLayout",
    props: ['titlecard'],
    data: () => ({
      drawer: false,
      group: null,
      API_URL: import.meta.env.VITE_API_URL,
      items: [
        {
          title: 'Home',
          value: '01',
          icon: "mdi-home",
          to:"/",
          target:""
        },
        {
          title: 'Usuários',
          value: '02',
          icon: 'mdi-account',
          to:"/users",
          target:""
        },
        {
          title: 'API - Swagger',
          value: '03',
          icon: 'mdi-api',
          to:import.meta.env.VITE_API_URL+"/documentation#/default",
          target:"_blank"
        },
      ],
    }),

    watch: {
      group () {
        this.drawer = false
      },
    },

    methods:{
      selfback(){
        this.$router.go(-1);
      }
    }
  }
</script>
