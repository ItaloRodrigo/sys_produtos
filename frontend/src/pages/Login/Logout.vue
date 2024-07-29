<template>
  <div class="text-center">
    <v-progress-circular
      v-if="loading"
      indeterminate
      color="primary"
      size="64"
      class="mx-auto"
    ></v-progress-circular>
    <v-alert
      v-if="error"
      type="error"
      class="mt-4"
    >
      Ocorreu um erro ao fazer logout. Tente novamente.
    </v-alert>
  </div>
</template>

<script scoped>
export default {
  name: "Logout",
  data() {
    return {
      loading: true,  // Adiciona variável de estado para controle de loading
      error: false,   // Adiciona variável para controle de erro
    };
  },
  async created() {
    try {
      const vok = await this.$auth.logout(this);
      if (vok) {
        this.$router.replace('/login');
      } else {
        this.error = true;
      }
    } catch (erro) {
      console.error(erro);
      this.error = true;
    } finally {
      this.loading = false;  // Para o loading após a tentativa de logout
    }
  },
}
</script>

<style scoped>
/* Estilos adicionais se necessários */
</style>
