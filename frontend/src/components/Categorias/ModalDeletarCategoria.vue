<template>
  <v-dialog v-model="dialog" max-width="600" persistent>
    <v-card>
      <v-card-title class="headline">Deletar Categoria</v-card-title>
      <v-card-subtitle>
        Deseja realmente deletar a categoria: {{ categoria.name }}?
      </v-card-subtitle>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn class="bg-info" @click="$emit('closeModal')" :disabled="isLoading">Cancelar</v-btn>
        <v-btn class="bg-red" @click="deletar" :disabled="isLoading">
          <template v-if="isLoading">
            <v-progress-circular indeterminate color="white" size="24"></v-progress-circular>
          </template>
          <template v-else>
            Deletar
          </template>
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { api, except } from '@/plugins/api';
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalDeletarCategoria',
  props: {
    categoria: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      dialog: true,
      isLoading: false, // Adicionado estado de carregamento
    };
  },
  methods: {
    async deletar() {
      this.isLoading = true; // Ativa o carregamento
      const errors = [];
      const messages = [];

      try {
        const response = await api(this).get(`categoria/delete/${this.categoria.id}`)
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        });
        if (response.data.status === 200) {
          messages.push({
            type: 'success',
            title: 'Sucesso: deletar categoria',
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
                  title: 'Erro: deletar categoria',
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
      } finally {
        this.isLoading = false; // Desativa o carregamento
      }
    }
  }
}
</script>
