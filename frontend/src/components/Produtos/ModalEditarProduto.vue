<template>
  <v-dialog v-model="dialog" max-width="600" persistent>
    <v-card>
      <v-card-title class="headline">Editar Produto</v-card-title>
      <v-card-subtitle>Atualize os detalhes do produto</v-card-subtitle>
      <v-card-item>
        <v-row>
          <v-col cols="12">
            <v-text-field v-model="name" :rules="nameRules" label="Nome" required ref="name" />
          </v-col>

          <v-col cols="12">
            <v-text-field v-model="description" :rules="descriptionRules" label="Descrição" required ref="description" />
          </v-col>

          <v-col cols="12">
            <v-text-field v-model="price" :rules="[validateDouble]" label="Preço" ref="price" required />
          </v-col>

          <v-col cols="12">
            <v-text-field v-model="date_expiration" label="Data de validade" ref="date_expiration" hint="DD/MM/YYYY"
              prepend-icon="mdi-calendar" persistent-hint @input="applyMask" :rules="dateRules" required />
          </v-col>

          <v-col cols="12">
            <v-combobox :items="itemsCategoria" v-model="selectedCategoria" label="Selecione uma categoria"
              :loading="isLoading" :disabled="isLoading" item-title="name" item-value="id" return-object ref="categoria"
              clearable />
          </v-col>

          <v-col cols="12">
            <!-- Mostrar o caminho da imagem existente -->
            <v-text-field v-model="imagePath" label="Imagem Atual" readonly />
            <v-btn @click="toggleImageUpload" class="mt-2">Atualizar Imagem</v-btn>

            <!-- Campo para atualizar a imagem -->
            <v-file-input v-if="showImageUpload" type="file" v-model="image" label="Escolha uma foto" accept="image/*"
              prepend-icon="mdi-image" @change="handleFileUpload" ref="image" show-size />
            <v-img v-if="imagePath" :src="imagePath" max-height="200" class="mt-2" />
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
  name: 'ModalEditarProduto',
  props: ['produto'],
  emits: ['closeModal'],
  data() {
    return {
      dialog: true,
      isLoading: false,
      name: '',
      description: '',
      price: 0,
      date_expiration: '',
      itemsCategoria: [],
      selectedCategoria: null,
      image: null,
      imagePath: '', // Adiciona imagePath para armazenar o caminho da imagem
      showImageUpload: false, // Controle para mostrar ou ocultar o campo de upload de imagem
      // ------------------ validações
      nameRules: [
        value => value ? true : "O campo nome é obrigatório!",
      ],
      dateRules: [
        value => !!value || 'A data é obrigatória',
        value => this.isValidDate(value) || 'A data deve estar no formato DD/MM/YYYY',
        value => this.isFutureDate(value) || 'A data deve ser futura',
      ],
      fileRules: [
        value => !!value || 'O arquivo é obrigatório',
        value => value ? true : "O campo é obrigatório",
      ],
    };
  },
  watch: {
    produto: {
      handler(produto) {
        if (produto) {
          this.loadProdutoData(produto);
        }
      },
      immediate: true,
    },
  },
  mounted() {
    this.loadCategoria();
  },
  methods: {
    validateDouble(value) {
      if (!value || isNaN(value)) {
        return 'O valor deve ser um número decimal, troque a "," por "."';
      }
      if (!/^[-+]?\d*\.?\d+$/.test(value)) {
        return 'O valor deve ser um número decimal, troque a "," por "."';
      }
      return true;
    },
    applyMask() {
      let value = this.date_expiration.replace(/\D/g, '');
      if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
      }
      if (value.length > 5) {
        value = value.substring(0, 5) + '/' + value.substring(5, 9);
      }
      this.date_expiration = value;
    },
    isValidDate(dateString) {
      const regex = /^\d{2}\/\d{2}\/\d{4}$/;
      if (!regex.test(dateString)) return false;
      const [day, month, year] = dateString.split('/').map(Number);
      const date = new Date(year, month - 1, day);
      return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day;
    },
    isFutureDate(dateString) {
      const [day, month, year] = dateString.split('/').map(Number);
      const date = new Date(year, month - 1, day);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      return date > today;
    },
    handleFileUpload(event) {
      this.image = event.target.files[0];
    },
    async loadProdutoData(produto) {
      this.name = produto.name;
      this.description = produto.description;
      this.price = produto.price;
      this.date_expiration = this.convertDateToDDMMYYYY(produto.date_expiration);
      this.selectedCategoria = this.itemsCategoria.find(cat => cat.id === produto.id_categoria) || null;
      this.imagePath = produto.image ? `/storage/img/${produto.image}` : ''; // Definir o caminho da imagem
    },
    convertDateToDDMMYYYY(dateString) {
      const [year, month, day] = dateString.split(' ')[0].split('-');
      return `${day}/${month}/${year}`;
    },
    async loadCategoria() {
      this.isLoading = true;
      try {
        const response = await api(this).get("categoria/all")
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        });
        if (response.data.status === 200) {
          const data = response.data.data.categorias;
          const itemsArray = Object.keys(data)
            .filter(key => !isNaN(key))
            .map(key => data[key]);
          this.itemsCategoria = itemsArray;

          // Após carregar as categorias, configurar a categoria selecionada com base na prop produto
          if (this.produto) {
            this.selectedCategoria = this.itemsCategoria.find(cat => cat.id === this.produto.id_categoria) || null;
          }
        } else {
          this.handleError(response.data);
        }
      } catch (error) {
        console.error(error.message);
      } finally {
        this.isLoading = false;
      }
    },
    async atualizar() {
      var erros = [];
      var mensagem = [];

      // Verificação dos campos obrigatórios
      if (!this.name) return this.$refs.name.focus();
      if (!this.description) return this.$refs.description.focus();
      if (!this.price) return this.$refs.price.focus();
      if (!this.date_expiration) return this.$refs.date_expiration.focus();
      if (!this.selectedCategoria) return this.$refs.categoria.focus();

      // Converter a data para o formato esperado pelo backend (YYYY-MM-DD HH:MM:SS)
      const [day, month, year] = this.date_expiration.split('/').map(Number);
      if (day && month && year) {
        const date = new Date(year, month - 1, day);
        if (!isNaN(date.getTime())) {
          const formattedDate = date.toISOString().replace('T', ' ').split('.')[0]; // Formato YYYY-MM-DD HH:MM:SS
          const formData = new FormData();
          formData.append("name", this.name);
          formData.append("description", this.description);
          formData.append("price", this.price);
          formData.append("date_expiration", formattedDate); // Data e hora formatadas
          formData.append("id_categoria", this.selectedCategoria.id);

          if (this.image) {
            formData.append("image", this.image);
          }

          this.isLoading = true; // Define isLoading como true
          try {
            const response = await api(this).post(`produto/update/${this.produto.id}`, formData);
            if (response.data.status === 200) {
              mensagem.push({
                type: 'success',
                title: 'Sucesso: editar produto',
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
        } else {
          console.error('Data inválida:', this.date_expiration);
          return;
        }
      } else {
        console.error('Data inválida:', this.date_expiration);
        return;
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
    toggleImageUpload() {
      this.showImageUpload = !this.showImageUpload;
    }
  }
}
</script>

<style scoped>
.v-img {
  border-radius: 4px;
}
</style>
