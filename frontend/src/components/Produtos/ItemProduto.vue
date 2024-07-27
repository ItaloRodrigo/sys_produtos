<template>

  <v-div>
    <v-list-item :value="produto.id">
      <template v-slot:prepend="{ isActive }">

        <v-list-item-action start>
          <v-checkbox-btn :model-value="isActive"></v-checkbox-btn>
        </v-list-item-action>
      </template>

      <v-row no-gutters>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{produto.name}}</v-list-item-title>

          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{produto.description}}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{produto.price}}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{produto.categoria_name}}</v-list-item-title>
          </v-sheet>
        </v-col>
      </v-row>



      <template v-slot:append>
        <DropdowmMenu :usuario="this.usuario" @updateLista="updateLista()"/>
      </template>

    </v-list-item>
  </v-div>

</template>

<script>

import DropdowmMenu from './DropdowmMenu.vue';


export default{

  emits:['updateLista'],

  components:{DropdowmMenu},

  name: 'ItemUsuario',

  props: {
    produto: null
  },

  data() {
      return {

      };
  },

  methods:{
    updateLista(){
      this.$emit('updateLista');
    },
  }
}

</script>
