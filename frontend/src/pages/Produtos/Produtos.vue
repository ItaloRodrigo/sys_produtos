<template>
  <base-layout titlecard="Produtos">
    <v-container>
      <h1>Produtos</h1>

      <v-card flat class="border mt-3">
        <v-card-item >
          <template v-slot:append>
            <v-btn color="primary" @click="chamaModalCriarProduto()">Adicionar Produto</v-btn>
            <ModalCriarProduto v-if="this.modalCriarProduto" @closeModal="onCloseModal()" />
          </template>
          <!-- <v-text-field prepend-icon="mdi-searchmdi mdi-magnify" v-model="pesquisar" @keyup.enter="onEnterPesquisar" clearable label="Pesquisar" outlined></v-text-field> -->
        </v-card-item>

        <v-card-item class="">
          <v-list lines="one" select-strategy="single-leaf" >
            <v-row no-gutters class="border-b">
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Nome</v-list-item-subtitle>

                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Descrição</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Preço</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Categoria</v-list-item-subtitle>
                </v-sheet>
              </v-col>
            </v-row>
            <v-div v-for="produto,index in produtos" :key="index">

              <ItemProduto :produto="produto" @dblclick="onSelected(produto)" @updateLista="updateLista()"/>
            </v-div>


            <Tarefa/>
            <Tarefa/>
          </v-list>
        </v-card-item>

      </v-card>

    <!-- Elemento de notificação -->
    <NotificationDefault ref="notificationRef" />

    <!-- Modal Editar Usuário -->
    <ModalEditarUsuario v-if="modalEditarProduto" @closeModal="closeModalEditarProduto()" :produto="this.produtoSelected" />

    </v-container>

  </base-layout>
</template>

<script>
import BaseLayout from '../../layouts/BaseLayout.vue';
import { useNotificationsStore } from '@/stores/notifications';
import NotificationDefault from '@/components/NotificationDefault.vue';
import ItemProduto from '@/components/Produtos/ItemProduto.vue';
import ModalCriarProduto from '@/components/Produtos/ModalCriarProduto.vue'
import ModalEditarProduto from '@/components/Produtos/ModalEditarProduto.vue'

// import axios from 'axios';
import {api} from '@/plugins/api'


export default {
  components: { BaseLayout, NotificationDefault, ItemProduto, ModalCriarProduto, ModalEditarProduto },
  name: "Produtos",

  data() {
    return {
      pesquisar: null,
      modalCriarProduto:false,
      modalEditarProduto:false,
      produtoSelected:null,
      produtos:[
        {id:1, name:"Leite", description:"Leite integral desnatado", price:"4.50", categoria_name:"Laticínios"},
      ],
    }
  },

  created(){
    this.updateLista();
  },

  methods:{

    async updateLista(){
      // console.log("ctx token")
      // console.log(this.$auth.token);
      await api(this)
        .get("produto/all")
        .then((res) => {
          this.produtos = res.data.data.produtos;
          // this.pages = res.data.count;
        })
        .catch((e) => console.log(e));
      // console.log(this.usuarios);
      //---
      console.log(useNotificationsStore().messages);
      // if(useNotificationsStore().messages.length > 0){
      //   this.showMessage();
      // }
    },

    showMessage(){
      useNotificationsStore().messages.forEach(message => {
        this.$refs.notificationRef.addNotification(message);
      });
      //---
      useNotificationsStore().clearMessage();
    },

    chamaModalCriarProduto(){
      this.modalCriarProduto = true;
    },

    closeModalEditarProduto(){
      this.modalEditarProduto = false;
      this.updateLista();
      this.showMessage();
    },

    onSelected(produto){
      console.log(produto);
      this.produtoSelected = produto;
      this.modalEditarProduto = true;
    },

    onCloseModal(){
      this.modalCriarProduto = false;
      //---
      this.updateLista();
      //---
      this.showMessage();
    }
  }
}
</script>
