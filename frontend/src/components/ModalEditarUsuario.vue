<template>

  <v-div>
    <v-dialog v-model="dialog" max-width="600" persistent>
      <v-card prepend-icon="mdi-pencil" title="Alterar Usuário">
        <v-card-item>
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="this.name"
                :rules="nameRules"
                label="Nome completo"
                required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                v-model="this.email"
                :rules="emailRules"
                label="E-mail"
                required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                v-model="this.cpf"
                :counter="14"
                :rules="cpfRules"
                label="CPF"
                v-mask="'###.###.###-##'"
                required>
              </v-text-field>
            </v-col>

          </v-row>
        </v-card-item>
        <template v-slot:actions>
          <v-spacer></v-spacer>

          <!-- <v-btn @click="dialog = false"> -->
          <v-btn class="bg-red" @click="$emit('closeModal')">
            Cancelar
          </v-btn>

          <v-btn class="bg-success" @click="salvar()">
            Salvar
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
  name: 'ModalEditarUsuario',

  emits: ['closeModal'],

  props: {
    usuario: null
  },

  data() {
    return {
      dialog: true,
      name:'',
      email:'',
      cpf:'',
      id:0,
      // ------------------ validações
      nameRules: [
        value => {
          if(value) return true;

          return "O nome é obrigatório!";
        }
      ],
      emailRules: [
        value => {
          if (value) return true

          return 'E-mail é obrigatório'
        },
        value => {
          if (/.+@.+\..+/.test(value)) return true

          return 'E-mail é inválido!'
        },
      ]
    };
  },

  created(){
    this.name = this.usuario.name;
    this.email = this.usuario.email;
    this.cpf = this.usuario.cpf;
    this.id = this.usuario.id;
  },

  methods:{
    async salvar(){
      var erros = [];
      var mensagem = [];
      //---
      await axios.post("user/update",{
        name: this.name,
        email: this.email,
        cpf: this.cpf,
        id: this.id,
      })
      .then((response) => {
          if (response.data.hasOwnProperty('erros')){
            for (const key in response.data.erros) {
              if (response.data.erros.hasOwnProperty(key)) {
                erros.push({
                  type: 'error',
                  title: 'Erro: editar usuário ',
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
              title: 'Sucesso: editar usuário ',
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
