<template>
  <base-layout titlecard="Erro 404 - Página não encontrada">
    <v-div class="error-container">

    </v-div>
  <div class="error-container">

    <div class="row justify-content-center mb-4">
        <div class="col-auto">
            <img src="../assets/img/error.png" style="width: 200px;">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h2 class="error-container__message">Página Não Encontrada</h2>
            <p class="error-container__description">
                Desculpe, a página que você está procurando não existe.</p>
            <router-link  to="/" class="btn btn-primary">
                <button id="pageHome" class="btn text-white btn-sm">
                    Voltar para a página inicial
                </button>
            </router-link>
        </div>
    </div>

</div>
  </base-layout>
</template>

<script>
import BaseLayout from '../layouts/BaseLayout.vue'
export default {
  components: { BaseLayout },
  name: "NotFound",
  data(){
    return {
      // counter: this.$auth.getCounter
    }
  }
}
</script>

<style scoped>
@import "@/assets/css/pagenotfound.css";
</style>
