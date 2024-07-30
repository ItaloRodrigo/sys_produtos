<template>
  <v-dialog v-model="dialog" max-width="600" persistent>
    <v-card>
      <v-card-title class="headline">Deletar Produto</v-card-title>
      <v-card-subtitle>
        Deseja realmente deletar o produto: {{ produto.name }}?
      </v-card-subtitle>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="bg-info" @click="$emit('closeModal')">Cancelar</v-btn>
        <v-btn class="bg-red" @click="deletar">Deletar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { api, except } from '@/plugins/api';
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalDeletarProduto',
  props: {
    produto: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      dialog: true,
    };
  },
  methods: {
    async deletar() {
      const errors = [];
      const messages = [];

      try {
        const response = await api(this).get(`produto/delete/${this.produto.id}`)
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        });

        if (response.data.status === 200) {
          messages.push({
            type: 'success',
            title: 'Sucesso: deletar produto',
            message: response.data.msg
          });

          useNotificationsStore().clearMessage();
          useNotificationsStore().addMessagesAll(messages);

          this.$emit('closeModal');
        } else {
          if (response.data.hasOwnProperty('data')) {
            for (const key in response.data.data) {
              if (response.data.data.hasOwnProperty(key)) {
                errors.push({
                  type: 'error',
                  title: 'Erro: deletar produto',
                  message: `${response.data.data[key]}`
                });
              }
            }
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(errors);
          }
        }
      } catch (error) {
        console.error('Erro na requisição:', error.message);
      }
    }
  }
}
</script>

