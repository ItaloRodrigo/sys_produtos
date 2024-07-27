<template>
  <base-layout titlecard="Usuários">
    <v-container>
      <h1>Usuários</h1>

      <v-card flat class="border mt-3">
        <v-card-item >
          <template v-slot:append>
            <v-btn color="primary" @click="chamaModalCriarUsuario()">Adicionar Usuário</v-btn>
            <ModalCriarUsuario v-if="this.modalCriarUsuario" @closeModal="onCloseModal()" />
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
                  <v-list-item-subtitle>Email</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Data de cadastro</v-list-item-subtitle>
                </v-sheet>
              </v-col>
            </v-row>
            <v-div v-for="usuario,index in usuarios" :key="index">

              <ItemUsuario :usuario="usuario" @dblclick="onSelected(usuario)" @updateLista="updateLista()"/>
            </v-div>


            <Tarefa/>
            <Tarefa/>
          </v-list>
        </v-card-item>

      </v-card>

    <!-- Elemento de notificação -->
    <NotificationDefault ref="notificationRef" />

    <!-- Modal Editar Usuário -->
    <ModalEditarUsuario v-if="modalEditarUsuario" @closeModal="closeModalEditarUsuario()" :usuario="this.usuarioSelected" />

    </v-container>

  </base-layout>
</template>

<script>
import BaseLayout from '../../layouts/BaseLayout.vue';
import { useNotificationsStore } from '@/stores/notifications';
import NotificationDefault from '@/components/NotificationDefault.vue';
import ItemUsuario from '@/components/Usuarios/ItemUsuario.vue';
import ModalCriarUsuario from '@/components/Usuarios/ModalCriarUsuario.vue'

// import axios from 'axios';
import {api} from '@/plugins/api'


export default {
  components: { BaseLayout, NotificationDefault, ItemUsuario, ModalCriarUsuario },
  name: "Usuarios",

  data() {
    return {
      pesquisar: null,
      modalCriarUsuario:false,
      modalEditarUsuario:false,
      usuarioSelected:null,
      usuarios:[
        {id:1, name:"teste1", email:"teste@gmail.com", cpf:"xxx.xxx.xxx-xx", status:true},
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
        .get("user/all")
        .then((res) => {
          this.usuarios = res.data.data.users;
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

    chamaModalCriarUsuario(){
      this.modalCriarUsuario = true;
    },

    closeModalEditarUsuario(){
      this.modalEditarUsuario = false;
      this.updateLista();
      this.showMessage();
    },

    onSelected(usuario){
      console.log(usuario);
      this.usuarioSelected = usuario;
      this.modalEditarUsuario = true;
    },

    onCloseModal(){
      this.modalCriarUsuario = false;
      //---
      this.updateLista();
      //---
      this.showMessage();
    }
  }
}
</script>
