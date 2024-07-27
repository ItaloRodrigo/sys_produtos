<template>

  <v-div>
    <v-dialog v-model="dialog" max-width="600" persistent>
      <v-card prepend-icon="mdi-delete" title="Deletar Usuário">
        <v-card-item>
          <v-row>
            <v-col cols="12">
              <h2>
                Deseja realmente deletar o usuário: {{ this.usuario.name }}?
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

// import axios from 'axios';
import { api } from '@/plugins/api'
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
      id: 0,
      name: '',
    };
  },

  created() {
    this.id = this.usuario.id;
    this.name = this.usuario.name;
  },

  methods: {
    async deletar() {
      var erros = [];
      var mensagem = [];
      //---
      await api(this).get("user/delete/" + this.id)
        .then((response) => {
          console.log(response);
          if (response.data.status == 200) {
            mensagem.push({
              type: 'success',
              title: 'Sucesso: editar usuário ',
              message: response.data.msg
            });
            //---
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(mensagem);
          } else {
            if (response.data.hasOwnProperty('data')) {
              for (const key in response.data.data) {
                if (response.data.data.hasOwnProperty(key)) {
                  erros.push({
                    type: 'error',
                    title: 'Erro: deletar usuário ',
                    message: `${response.data.data[key]}`
                  });
                }
              }
              //---
              useNotificationsStore().clearMessage();
              useNotificationsStore().addMessagesAll(erros);
            }
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
