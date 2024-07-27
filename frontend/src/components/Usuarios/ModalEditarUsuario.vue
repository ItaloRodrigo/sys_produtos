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
              <v-text-field type="password"
                v-model="this.password"
                label="Nova Senha"
                v-mask="'*****'"
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

import {api} from '@/plugins/api'
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
    // this.password = this.usuario.password;
    this.id = this.usuario.id;
    //---
    //console.log()
  },

  methods:{
    async salvar(){
      var erros = [];
      var mensagem = [];
      //---
      await api(this).post("user/update",{
        name: this.name,
        email: this.email,
        password: this.password,
        id: this.id,
      })
      .then((response) => {
          if(response.data.status == 200){
            mensagem.push({
              type: 'success',
              title: 'Sucesso: editar usuário ',
              message: response.data.msg
            });
            //---
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(mensagem);
          }else{
            if (response.data.hasOwnProperty('data')){
              for (const key in response.data.data) {
                if (response.data.data.hasOwnProperty(key)) {
                  erros.push({
                    type: 'error',
                    title: 'Erro: editar usuário ',
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
        console.log(error.response);
      });
      //---
      this.$emit('closeModal');
    }
  }
}

</script>
