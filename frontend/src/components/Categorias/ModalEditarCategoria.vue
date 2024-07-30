<template>
  <v-dialog v-model="dialog" max-width="600" persistent>
    <v-card>
      <v-card-title class="headline">Editar Categoria</v-card-title>
      <v-card-subtitle>Atualize os detalhes da categoria</v-card-subtitle>
      <v-card-item>
        <v-row>
          <v-col cols="12">
            <v-text-field v-model="name" :rules="nameRules" label="Nome" required ref="name" />
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
        <v-btn @click="atualizar" :disabled="isLoading">Atualizar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { api, except } from '@/plugins/api';
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalEditarCategoria',
  props: ['categoria'],
  emits: ['closeModal'],
  data() {
    return {
      dialog: true,
      isLoading: false,
      name: '',
      // ------------------ validações
      nameRules: [
        value => value ? true : "O campo nome é obrigatório!",
      ],
    };
  },
  watch: {
    categoria: {
      handler(categoria) {
        if (categoria) {
          this.loadCategoriaData(categoria);
        }
      },
      immediate: true,
    },
  },

  mounted() {
    console.log(this.categoria)
    this.loadCategoriaData(this.categoria);
  },
  methods: {
    loadCategoriaData(categoria) {
      this.name = categoria.name;
    },
    async atualizar() {
      if (!this.name) return this.$refs.name.focus();

      const formData = new FormData();
      formData.append("id", this.categoria.id);
      formData.append("name", this.name);

      this.isLoading = true;
      try {
        const response = await api(this).post(`categoria/update`, formData)
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        });
        if (response.data.status === 200) {
          useNotificationsStore().clearMessage();
          useNotificationsStore().addMessagesAll([{
            type: 'success',
            title: 'Sucesso: editar categoria',
            message: response.data.msg
          }]);
          this.$emit('closeModal');
        } else {
          this.handleError(response.data);
        }
      } catch (error) {
        console.error('Erro na requisição:', error.message);
      } finally {
        this.isLoading = false;
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
    },
  }
}
</script>
