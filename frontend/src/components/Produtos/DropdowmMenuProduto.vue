<template>

  <v-div>
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn color="dark" v-bind="props" icon>
          <v-icon>mdi mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list>
        <v-list-item v-for="(item, index) in items" :key="index" :value="index" @click="item.click()">
          <template v-slot:prepend>
            <v-icon :icon="item.icon" :color="item.color"></v-icon>
          </template>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
    <ModalEditarProduto v-if="items[0].modal" @closeModal="closeModalEditar()" :produto="this.produto" />

    <ModalDeletarProduto v-if="items[1].modal" @closeModal="closeModalDeletar()" :produto="this.produto" />
  </v-div>

</template>

<script>

import ModalEditarProduto from './ModalEditarProduto.vue';
import ModalDeletarProduto from './ModalDeletarProduto.vue';

export default {

  emits: ['updateLista'],

  components:{ModalEditarProduto, ModalDeletarProduto},

  name: 'DropdowmMenuProduto',

  props: {
    produto: null
  },

  data() {
    return {
      items: [
        { icon: "mdi mdi-pencil", color:'warning', title: 'Editar', modal:false, click(){
          console.log("Editar");
          this.modal = true;
        } },
        { icon: "mdi mdi-delete", color:'red', title: 'Deletar', modal:false, click(){
          console.log("Deletar");
          this.modal = true;
        } },
      ],
    };
  },

  methods:{
    closeModalEditar(){
      this.items[0].modal = false;
      //---
      this.$emit('updateLista');
    },

    closeModalDeletar(){
      this.items[1].modal = false;
      //---
      this.$emit('updateLista');
    }
  }
}

</script>
