<template>
  <base-layout titlecard="Categorias">
    <v-container>
      <h1>Categorias</h1>

      <v-card flat class="border mt-3">
        <v-card-item>
          <template v-slot:append>
            <v-btn color="primary" @click="chamaModalCriarCategoria()">Adicionar Categoria</v-btn>
            <ModalCriarCategoria v-if="modalCriarCategoria" @closeModal="onCloseModal()" />
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

          <!-- Conteúdo da lista -->
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
                  <v-list-item-subtitle>Data de criação</v-list-item-subtitle>
                </v-sheet>
              </v-col>
            </v-row>
            <v-div v-for="categoria in categorias" :key="categoria.id">
              <ItemCategoria :categoria="categoria" @dblclick="onSelected(categoria)" @updateLista="updateLista" />
            </v-div>
          </v-list>

          <!-- Paginação -->
          <Pagination
            :offset="currentPage - 1"
            :total="totalCategories"
            :limit="10"
            @change-page="handlePageChange"
            class="mt-4"
          />
        </v-card-item>
      </v-card>

      <!-- Elemento de notificação -->
      <NotificationDefault ref="notificationRef" />

      <!-- Modal Editar Categoria -->
      <ModalEditarCategoria v-if="modalEditarCategoria" @closeModal="closeModalEditarCategoria()" :categoria="categoriaSelected" />
    </v-container>
  </base-layout>
</template>

<script>
import BaseLayout from '../../layouts/BaseLayout.vue';
import { useNotificationsStore } from '@/stores/notifications';
import NotificationDefault from '@/components/NotificationDefault.vue';
import ItemCategoria from '@/components/Categorias/ItemCategoria.vue';
import ModalCriarCategoria from '@/components/Categorias/ModalCriarCategoria.vue';
import ModalEditarCategoria from '@/components/Categorias/ModalEditarCategoria.vue';
import Pagination from '@/components/Pagination.vue'; // Importação do componente de paginação
import { api } from '@/plugins/api';

export default {
  components: { BaseLayout, NotificationDefault, ItemCategoria, ModalCriarCategoria, ModalEditarCategoria, Pagination },
  name: "Categorias",

  data() {
    return {
      pesquisar: null,
      modalCriarCategoria: false,
      modalEditarCategoria: false,
      categoriaSelected: null,
      categorias: [],
      loading: false,
      currentPage: 1,
      totalCategories: 0, // Total de categorias obtido da API
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
        .get(`categoria/pagination/${page}`)
        .then((res) => {
          this.categorias = res.data.data.categorias;
          this.totalCategories = res.data.data.count; // Total de categorias obtido da API
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

    chamaModalCriarCategoria() {
      this.modalCriarCategoria = true;
    },

    closeModalEditarCategoria() {
      this.modalEditarCategoria = false;
      this.updateLista();
      this.showMessage();
    },

    onSelected(categoria) {
      console.log(categoria);
      this.categoriaSelected = categoria;
      this.modalEditarCategoria = true;
    },

    onCloseModal() {
      this.modalCriarCategoria = false;
      this.updateLista();
      this.showMessage();
    }
  }
}
</script>
