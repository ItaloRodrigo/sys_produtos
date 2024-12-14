<template>
  <base-layout titlecard="Sobre Mim">
    <v-container>
      <v-card class="mx-auto my-5" max-width="900" outlined>
        <!-- Título do Card -->
        <v-card-title>
          <v-icon class="mr-2" size="x-large">mdi-account</v-icon>
          <span class="headline">Sys Produtos v1.0</span>
        </v-card-title>

        <!-- Corpo do Card -->
        <v-card-text class="text-center">
          <h2 class="font-weight-bold">Bem-vindo ao <span class="text-primary">Sys Produtos v1.0</span></h2>
          <v-divider class="my-4"></v-divider>

          <p class="text-h6 text-justify">
            <strong>Resumo Profissional:</strong><br>
            Atua há mais de 7 anos no ramo de tecnologia da informação, adquiriu experiência ao trabalhar com
            ferramentas das stacks de programação back-end e front-end, ao longo dos anos conquistou maturidade em
            análises de processos para abstração de regras de negócios aplicadas à sistemas de eficiência energética,
            sistemas financeiros, sistemas acadêmicos e sistemas de rastreabilidade para movimentação de materiais.
            Desenvolveu habilidades no âmbito da Segurança da Informação aplicado à penetração de dados, segurança de
            redes e planos de segurança da informação, no âmbito da Ciência de Dados aplicado à sistemas de predição com
            DNN, RNA, e Algoritmos de Otimização.
          </p>
        </v-card-text>

        <!-- Formação Acadêmica -->
        <v-card-subtitle class="mt-4">
          <h3 class="font-weight-bold">Formação Acadêmica</h3>
        </v-card-subtitle>
        <v-list class="p-0 m-0">
          <v-list-item class="p-0">
            <v-list-item-icon>
              <v-icon>mdi-school</v-icon> Mestre em Engenharia
            </v-list-item-icon>
          </v-list-item>
          <v-list-item class="p-0">
            <v-list-item-icon>
              <v-icon>mdi-school</v-icon> Bacharel em Ciência da Computação
            </v-list-item-icon>
          </v-list-item>
        </v-list>

        <!-- Informações de Contato -->
        <v-card-actions>
          <v-list-item class="w-100 align-center">
            <v-avatar color="grey darken-3" class="mr-3">
              <v-img alt="Italo"
                src="https://media.licdn.com/dms/image/v2/D4D03AQHz6ZyVEznI-A/profile-displayphoto-shrink_200_200/profile-displayphoto-shrink_200_200/0/1731369101461?e=1739404800&v=beta&t=6dc9IFiBVSKN1IY3TZqvW3IL9OtWdcvOBCRJAWENprg"></v-img>
              <!-- <img src="https://media.licdn.com/dms/image/D4D03AQFfB0rlWjaXiw/profile-displayphoto-shrink_200_200/0/1710195953181?e=1727913600&v=beta&t=TYiDrIPLs2cXRK0Xj6HtLA59GWohO2GMBvwlme31gTo" alt="Ítalo Rodrigo"> -->
            </v-avatar>
            <v-list-item-content>
              <v-list-item-title class="text-h6 font-weight-bold">Ítalo Rodrigo</v-list-item-title>
              <v-list-item-subtitle class="text-body-2">Dev Fullstack</v-list-item-subtitle>
            </v-list-item-content>
            <v-spacer></v-spacer>
            <v-btn class="me-2" color="primary" href="https://www.linkedin.com/in/italo-computation/" target="_blank"
              variant="flat">
              <v-icon left>mdi-linkedin</v-icon> @italo-computation
            </v-btn>
            <v-btn icon>
              <v-icon>mdi-share-variant</v-icon>
              <span class="subheading">130</span>
            </v-btn>
          </v-list-item>
        </v-card-actions>
      </v-card>
    </v-container>
  </base-layout>
</template>

<script>
import BaseLayout from '../layouts/BaseLayout.vue';
export default {
  components: { BaseLayout },
  name: "Sobre",
  data() {
    return {
      // counter: this.$auth.getCounter
    };
  },
  mounted() {
    console.log(this.$auth.isAuth);
  }
}
</script>

<style scoped>
.v-card {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.v-card-title {
  background-color: #f5f5f5;
}

.v-list-item {
  align-items: center;
}

.v-list-item-icon {
  color: #1976d2;
}

.v-list-item-content {
  margin-left: 10px;
}

.v-avatar img {
  border-radius: 50%;
}

.text-primary {
  color: #1976d2;
}

.font-weight-bold {
  font-weight: bold;
}

.custom-justified {
  text-align: justify;
}

.align-center {
  display: flex;
  align-items: center;
}

.v-list-item {
  padding: 0;
}
</style>
