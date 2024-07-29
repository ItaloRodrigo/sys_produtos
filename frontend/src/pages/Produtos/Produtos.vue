<template>
  <base-layout titlecard="Produtos">
    <v-container>
      <h1>Produtos</h1>

      <v-card flat class="border mt-3">
        <v-card-item>
          <template v-slot:append>
            <v-btn color="primary" @click="chamaModalCriarProduto()">Adicionar Produto</v-btn>
            <ModalCriarProduto v-if="modalCriarProduto" @closeModal="onCloseModal()" />
          </template>
        </v-card-item>

        <v-card-item>
          <!-- Spinner de carregamento -->
          <v-progress-circular
            v-if="loading"
            indeterminate
            color="primary"
            class="ma-5"
          ></v-progress-circular>

          <!-- Conteúdo da tabela -->
          <v-list v-else lines="one" select-strategy="single-leaf">
            <v-row no-gutters class="border-b">
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Nome</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Descrição</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Preço</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Categoria</v-list-item-subtitle>
                </v-sheet>
              </v-col>
            </v-row>

            <v-div v-for="produto in produtos" :key="produto.id">
              <ItemProduto :produto="produto" @dblclick="onSelected(produto)" @updateLista="updateLista" />
            </v-div>
          </v-list>

          <!-- Paginação -->
          <Pagination
            :offset="currentPage - 1"
            :total="totalProducts"
            :limit="10"
            @change-page="handlePageChange"
            class="mt-4"
          />
        </v-card-item>
      </v-card>

      <!-- Elemento de notificação -->
      <NotificationDefault ref="notificationRef" />

      <!-- Modal Editar Produto -->
      <ModalEditarProduto v-if="modalEditarProduto" @closeModal="closeModalEditarProduto()" :produto="produtoSelected" />
    </v-container>
  </base-layout>
</template>

<script>
import BaseLayout from '../../layouts/BaseLayout.vue';
import { useNotificationsStore } from '@/stores/notifications';
import NotificationDefault from '@/components/NotificationDefault.vue';
import ItemProduto from '@/components/Produtos/ItemProduto.vue';
import ModalCriarProduto from '@/components/Produtos/ModalCriarProduto.vue';
import ModalEditarProduto from '@/components/Produtos/ModalEditarProduto.vue';
import Pagination from '@/components/Pagination.vue'; // Importação do componente de paginação
import { api } from '@/plugins/api';

export default {
  components: { BaseLayout, NotificationDefault, ItemProduto, ModalCriarProduto, ModalEditarProduto, Pagination },
  name: "Produtos",

  data() {
    return {
      pesquisar: null,
      modalCriarProduto: false,
      modalEditarProduto: false,
      produtoSelected: null,
      produtos: [],
      loading: false,
      currentPage: 1,
      totalProducts: 0, // Total de produtos obtido da API
    }
  },

  created() {
    this.fetchPage(this.currentPage);
  },

  methods: {
    async fetchPage(page) {
      console.log("Fetching page:", page);
      this.loading = true;
      await api(this)
        .get(`produto/pagination/${page}`)
        .then((res) => {
          this.produtos = res.data.data.produtos;
          this.totalProducts = res.data.data.count; // Total de produtos obtido da API
        })
        .catch((e) => console.log(e))
        .finally(() => {
          this.loading = false;
        });
    },

    handlePageChange(page) {
      this.currentPage = page;
      this.fetchPage(page);
    },

    async updateLista() {
      this.fetchPage(this.currentPage);
    },

    showMessage() {
      useNotificationsStore().messages.forEach(message => {
        this.$refs.notificationRef.addNotification(message);
      });
      useNotificationsStore().clearMessage();
    },

    chamaModalCriarProduto() {
      this.modalCriarProduto = true;
    },

    closeModalEditarProduto() {
      this.modalEditarProduto = false;
      this.updateLista();
      this.showMessage();
    },

    onSelected(produto) {
      console.log(produto);
      this.produtoSelected = produto;
      this.modalEditarProduto = true;
    },

    onCloseModal() {
      this.modalCriarProduto = false;
      this.updateLista();
      this.showMessage();
    }
  }
}
</script>
