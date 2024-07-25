<template>

  <v-div>
    <v-dialog v-model="dialog" max-width="600" persistent>
      <v-card prepend-icon="mdi-delete" title="Deletar Usuário">
        <v-card-item>
          <v-row>
            <v-col cols="12">
              <h2>
                Deseja realmente deletar o usuário: {{this.usuario.name}}?
              </h2>
            </v-col>
          </v-row>
        </v-card-item>
        <template v-slot:actions>
          <v-spacer></v-spacer>

          <!-- <v-btn @click="dialog = false"> -->
          <v-btn class="bg-info" @click="$emit('closeModal')">
            Cancelar
          </v-btn>

          <v-btn class="bg-red" @click="deletar()">
            Deletar
          </v-btn>
        </template>
      </v-card>
    </v-dialog>
  </v-div>

</template>

<script>

import axios from 'axios';
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalDeletarUsuario',

  emits: ['closeModal'],

  props: {
    usuario: null
  },

  data() {
    return {
      dialog: true,
      id:0,
      name:'',
    };
  },

  created(){
    this.id = this.usuario.id;
    this.name = this.usuario.name;
  },

  methods:{
    async deletar(){
      var erros = [];
      var mensagem = [];
      //---
      await axios.post("user/delete",{
        id: this.id,
      })
      .then((response) => {
          if (response.data.hasOwnProperty('erros')){
            for (const key in response.data.erros) {
              if (response.data.erros.hasOwnProperty(key)) {
                erros.push({
                  type: 'error',
                  title: 'Erro: Deletar usuário ',
                  message: `${response.data.erros[key]}`
                });
              }
            }
            //---
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(erros);
          }else{
            mensagem.push({
              type: 'success',
              title: 'Sucesso: Deletar usuário ',
              message: response.data.mensagem
            });
            //---
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(mensagem);
          }
      })
      .catch((error) => {
        console.log(error.message);
      });
      //---

      //---
      this.$emit('closeModal');
    }
  }
}

</script>
