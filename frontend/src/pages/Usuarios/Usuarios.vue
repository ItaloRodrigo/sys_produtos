<template>
  <base-layout titlecard="Usuários">
    <v-container>
      <h1>Usuários</h1>

      <v-card flat class="border mt-3">
        <v-card-item>
          <template v-slot:append>
            <v-btn color="primary" @click="chamaModalCriarUsuario()">Adicionar Usuário</v-btn>
            <ModalCriarUsuario v-if="modalCriarUsuario" @closeModal="onCloseModal()" />
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
                  <v-list-item-subtitle>Email</v-list-item-subtitle>
                </v-sheet>
              </v-col>
              <v-col>
                <v-sheet class="pa-2 ma-2">
                  <v-list-item-subtitle>Data de cadastro</v-list-item-subtitle>
                </v-sheet>
              </v-col>
            </v-row>
            <v-div v-for="usuario in usuarios" :key="usuario.id">
              <ItemUsuario :usuario="usuario" :loggedInUserId="this.$auth.user.user.id" @dblclick="onSelected(usuario)" @updateLista="updateLista" />
            </v-div>
          </v-list>

          <!-- Paginação -->
          <Pagination
            :offset="currentPage - 1"
            :total="totalUsers"
            :limit="10"
            @change-page="handlePageChange"
            class="mt-4"
          />
        </v-card-item>
      </v-card>

      <!-- Elemento de notificação -->
      <NotificationDefault ref="notificationRef" />

      <!-- Modal Editar Usuário -->
      <ModalEditarUsuario v-if="modalEditarUsuario" @closeModal="closeModalEditarUsuario()" :usuario="usuarioSelected" />
    </v-container>
  </base-layout>
</template>

<script>
import BaseLayout from '../../layouts/BaseLayout.vue';
import { useNotificationsStore } from '@/stores/notifications';
import NotificationDefault from '@/components/NotificationDefault.vue';
import ItemUsuario from '@/components/Usuarios/ItemUsuario.vue';
import ModalCriarUsuario from '@/components/Usuarios/ModalCriarUsuario.vue';
import ModalEditarUsuario from '@/components/Usuarios/ModalEditarUsuario.vue';
import Pagination from '@/components/Pagination.vue'; // Importação do componente de paginação
import { api, except } from '@/plugins/api';

export default {
  components: { BaseLayout, NotificationDefault, ItemUsuario, ModalCriarUsuario, ModalEditarUsuario, Pagination },
  name: "Usuarios",

  data() {
    return {
      pesquisar: null,
      modalCriarUsuario: false,
      modalEditarUsuario: false,
      usuarioSelected: null,
      usuarios: [],
      loading: false,
      currentPage: 1,
      totalUsers: 0, // Total de usuários obtido da API
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
        .get(`user/pagination/${page}`)
        .then((res) => {
          this.usuarios = res.data.data.users;
          this.totalUsers = res.data.data.count; // Total de usuários obtido da API
        })
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        })
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

    chamaModalCriarUsuario() {
      this.modalCriarUsuario = true;
    },

    closeModalEditarUsuario() {
      this.modalEditarUsuario = false;
      this.updateLista();
      this.showMessage();
    },

    onSelected(usuario) {
      console.log(usuario);
      this.usuarioSelected = usuario;
      this.modalEditarUsuario = true;
    },

    onCloseModal() {
      this.modalCriarUsuario = false;
      this.updateLista();
      this.showMessage();
    }
  }
}
</script>
