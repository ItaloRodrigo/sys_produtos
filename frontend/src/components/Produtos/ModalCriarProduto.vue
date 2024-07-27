<template>

  <v-div>
    <v-dialog v-model="dialog" max-width="600" persistent>
      <v-card prepend-icon="mdi mdi-account" title="Criar Produto" title-color="success">
        <v-card-item>
          <v-row>
            <v-col cols="12">
              <v-text-field v-model="this.name" :rules="nameRules" label="Nome" required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field v-model="this.description" :rules="descriptionRules" label="Descrição" required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field v-model="this.price" :rules="[validateDouble]" label="Preço"
              required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field v-model="this.date_expiration" label="Data de validade"
              :rules="[validateDate]"
              hint="DD/MM/YYYY"
              persistent-hint
              @input="applyMask"
              required>
              </v-text-field>
            </v-col>

            <v-col cols="12">
              <v-combobox
              :items="itemsCategoria"
              v-model="selectedCategoria"
              label="Selecione um item"
              :loading="isloading"
              :disabled="isloading"
              item-title="name"
              item-value="id"
              return-object
              clearable
            ></v-combobox>
            </v-col>

            <v-col cols="12">
              <v-file-input
                v-model="image"
                label="Escolha uma foto"

                accept="image/*"
                prepend-icon="mdi-image"
                show-size
              ></v-file-input>
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

// import axios from 'axios';
import { api } from '@/plugins/api'
import { useNotificationsStore } from '@/stores/notifications';

export default {

  name: 'ModalCriarProduto',

  emits: ['closeModal'],

  data() {
    return {
      dialog: true,
      isloading:false,
      name: '',
      description:'',
      price:0,
      date_expiration: '',
      categorias:null,
      itemsCategoria:[],
      selectedCategoria:null,
      image:null,
      // erros:[],
      // ------------------ validações
      nameRules: [
        value => {
          // console.log(value);
          if (value) return true;

          return "O nome é obrigatório!";
        }
      ],
      date_expirationRules: [
        value => {
          // console.log(value);
          if (value) return true;

          return "O nome é obrigatório!";
        }
      ],
      fileRules: [
        value => !!value || 'O arquivo é obrigatório',
        value => {
          if (value) return true; // Se o valor estiver vazio, não realiza a validação
          return "O campo é obrigatório"
        },
      ],
    };
  },

  mounted(){
    this.loadCategoria();
  },

  methods: {

    validateDouble(value) {
      // Verifica se o valor é um número válido
      if (!value || isNaN(value)) {
        return 'O valor deve ser um número decimal, troque a "," por "."';
      }
      // Verifica se o valor é um número decimal
      if (!/^[-+]?\d*\.?\d+$/.test(value)) {
        return 'O valor deve ser um número decimal, troque a "," por "."';
      }
      return true;
    },

    applyMask() {
      // Remove all non-numeric characters
      let value = this.date_expiration.replace(/\D/g, '');

      // Apply the mask (DD/MM/YYYY)
      if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
      }
      if (value.length > 5) {
        value = value.substring(0, 5) + '/' + value.substring(5, 9);
      }

      this.date_expiration = value;
    },
    validateDate(value) {
      // Check if the value matches the date format DD/MM/YYYY
      const datePattern = /^(\d{2})\/(\d{2})\/(\d{4})$/;
      if (!datePattern.test(value)) {
        return 'A data deve estar no formato DD/MM/YYYY';
      }

      const [_, day, month, year] = value.match(datePattern);

      // Convert to numbers
      const dayNum = parseInt(day, 10);
      const monthNum = parseInt(month, 10);
      const yearNum = parseInt(year, 10);

      // Check if the date is valid
      const date = new Date(yearNum, monthNum - 1, dayNum);
      if (date.getFullYear() !== yearNum || date.getMonth() + 1 !== monthNum || date.getDate() !== dayNum) {
        return 'Data inválida';
      }

      return true;
    },

    async loadCategoria(){
      this.isloading = true;
      await api(this).get("categoria/all")
        .then((response) => {
          // console.log(response.data.data.categorias);
          if (response.data.status == 200) {
            const data = response.data.data.categorias;
            //---
            const itemsArray = Object.keys(data)
                .filter(key => !isNaN(key))
                .map(key => data[key]);

            this.itemsCategoria = itemsArray;
            this.isloading = false;
            //---
            console.log(this.itemsCategoria)
          } else {
            if (response.data.hasOwnProperty('data')) {
              for (const key in response.data.data) {
                if (response.data.data.hasOwnProperty(key)) {
                  erros.push({
                    type: 'error',
                    title: 'Erro: consultar categoria ',
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
    },

    async salvar() {
      var erros = [];
      var mensagem = [];
      //---
      console.log(this.image);
      alert(this.image+"<br>"+this.selectedCategoria.id);

      //---
      // await api(this).post("produto/create", {
      //   name: this.name,
      //   description: this.description,
      //   price: this.price,
      //   date_expiration: this.date_expiration,
      //   image: this.image,
      //   id_categoria: this.selectedCategoria.id
      // })
      //   .then((response) => {
      //     console.log(response);
      //     if (response.data.status == 200) {
      //       mensagem.push({
      //         type: 'success',
      //         title: 'Sucesso: criar usuário ',
      //         message: response.data.msg
      //       });
      //       //---
      //       useNotificationsStore().clearMessage();
      //       useNotificationsStore().addMessagesAll(mensagem);
      //     } else {
      //       if (response.data.hasOwnProperty('data')) {
      //         for (const key in response.data.data) {
      //           if (response.data.data.hasOwnProperty(key)) {
      //             erros.push({
      //               type: 'error',
      //               title: 'Erro: criar usuário ',
      //               message: `${response.data.data[key]}`
      //             });
      //           }
      //         }
      //         //---
      //         useNotificationsStore().clearMessage();
      //         useNotificationsStore().addMessagesAll(erros);
      //       }
      //     }
      //   })
      //   .catch((error) => {
      //     console.log(error.message);
      //   });
      //---

      //---
      // this.$emit('closeModal');
    }
  }
}

</script>
