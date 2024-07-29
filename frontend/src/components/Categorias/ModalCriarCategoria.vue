<template>
  <v-dialog v-model="dialog" max-width="600" persistent>
    <v-card>
      <v-card-title class="headline">Criar Categoria</v-card-title>
      <v-card-subtitle>Preencha os detalhes da categoria</v-card-subtitle>
      <v-card-item>
        <v-row>
          <v-col cols="12">
            <v-text-field
              v-model="name"
              :rules="nameRules"
              label="Nome"
              required
              ref="name"
            />
          </v-col>

          <!-- Indicador de carregamento -->
          <v-col cols="12" v-if="isLoading" class="d-flex justify-center">
            <v-progress-circular indeterminate color="primary" />
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-actions>
        <v-spacer />
        <v-btn @click="$emit('closeModal')">Cancelar</v-btn>
        <v-btn @click="salvar" :disabled="isLoading">Salvar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { api } from '@/plugins/api';
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalCriarCategoria',
  emits: ['closeModal'],
  data() {
    return {
      dialog: true,
      isLoading: false, // Adiciona controle de carregamento
      name: '',
      // ------------------ validações
      nameRules: [
        value => value ? true : 'O nome é obrigatório!',
      ],
    };
  },
  methods: {
    async salvar() {
      var erros = [];
      var mensagem = [];

      // Verificação dos campos obrigatórios
      if (!this.name) return this.$refs.name.focus();

      this.isLoading = true; // Define isLoading como true
      try {
        const response = await api(this).post('categoria/create', { name: this.name });
        if (response.data.status === 200) {
          mensagem.push({
            type: 'success',
            title: 'Sucesso: criar categoria',
            message: response.data.msg
          });
          useNotificationsStore().clearMessage();
          useNotificationsStore().addMessagesAll(mensagem);
          this.$emit('closeModal');
        } else {
          this.handleError(response.data);
        }
      } catch (error) {
        console.error('Erro na requisição:', error.message);
      } finally {
        this.isLoading = false; // Define isLoading como false
      }
    },
    handleError(data) {
      var erros = [];
      if (data.hasOwnProperty('data')) {
        for (const key in data.data) {
          if (data.data.hasOwnProperty(key)) {
            erros.push({
              type: 'error',
              title: 'Erro',
              message: `${data.data[key]}`
            });
          }
        }
        useNotificationsStore().clearMessage();
        useNotificationsStore().addMessagesAll(erros);
      }
    }
  }
}
</script>
