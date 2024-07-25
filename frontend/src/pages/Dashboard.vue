<template>
  <base-layout titlecard="Home">

    <v-container>

      <v-card class="mx-auto" prepend-icon="mdi-account" title="Sys User v1.0">
        <template v-slot:prepend>
          <v-icon size="x-large"></v-icon>
        </template>

        <v-card-text class="text-h4 py-2 text-center">
          <strong>Seja bem-vindo ao <span class="text-primary">Sys User v1.0</span> </strong>
        </v-card-text>

        <v-card-text class="text-h6 py-2 text-justify">
          <p class="custom-justified">
            <strong>Resumo Profissional:</strong>
            Atuo há mais de 5 anos como desenvolvedor fullstack, adquiri experiência ao trabalhar com ferramentas
            das stacks de programação back-end e front-end, ao longo dos anos conquistei maturidade em análises de
            processos para abstração de regras de negócios aplicadas à sistemas web e mobile. Desenvolvi habilidades
            no âmbito da Segurança da Informação aplicado à penetração de dados, segurança de redes e planos de
            segurança
            da informação, no âmbito da Ciência de Dados aplicado à sistemasde predição com DNN, RNA, e Algoritmos de
            Otimização.
          </p>
          <p class="custom-justified">Trabalhei em desenvolvimento de web sites e sistemas computacionais atuando no
            back e no front das aplicações, implementação e desenvolvimento de técnicas computacionais para otimização
            de
            processos.
            Trabalhei com as seguintes ferramentas e tecnologias: HTML, CSS, Bootstrap 5, Vue.js, Vuetify, Javascript,
            Node.js,
            Java, Git, GitHub, Figma, Pré Processadores CSS (SCSS), SCRUM. Participando de todas as fases de
            desenvolvimento,
            indo desde o levantamento de requisitos junto ao cliente e
            modelagem de layouts até a implementação, testes e entrega do sistema, utilizando a metodologia ágil
            SCRUM. Os Projetos são relacionados a monitoramento em tempo real, processos produtivos, gestão e
            área administrativa. Também sou pesquisador na área de otimização de processos e computação aplicada, com
            alguns
            artigos e
            capítulos de livros publicados em periódicos internacionais. Professor de cursos de curta duração em
            Segurança da
            Informação e
            exporadicamente participo de elaboração de propostas de projetos de inovação na área de engenharia junto ao
            desenvolvimento de software.</p>
        </v-card-text>

        <v-card-text title="teste" class="text-h6">
          <h3 class="mt-1 subtitle">Formação Acadêmica</h3>
          <v-list-item>
            <v-list-item-title>Mestre em Engenharia com ênfase em Otimização de Processos
              Industriais</v-list-item-title>

            <v-list-item-title>Bacharel em Ciência da Computação</v-list-item-title>
          </v-list-item>
        </v-card-text>


        <v-card-actions>
          <v-list-item class="w-100">
            <template v-slot:prepend>
              <v-avatar color="grey-darken-3" image="@/assets/img/italo-rodrigo.jpg"></v-avatar>
            </template>

            <v-list-item-title>Ítalo Rodrigo</v-list-item-title>

            <v-list-item-subtitle>Dev Fullstack</v-list-item-subtitle>

            <template v-slot:append>
              <div class="justify-self-end">
                <v-btn class="me-2 text-none" color="primary" prepend-icon="mdi-linkedin" target="_blank" href="https://www.linkedin.com/in/italo-computation/" variant="flat">
                  @italo-computation
                </v-btn>
                <span class="me-1"></span>
                <v-icon class="me-1" icon="mdi-share-variant"></v-icon>
                <span class="subheading">130</span>
              </div>
            </template>
          </v-list-item>
        </v-card-actions>
      </v-card>

    </v-container>
  </base-layout>
</template>

<script>
import BaseLayout from '../layouts/BaseLayout.vue'
export default {
  components: { BaseLayout },
  name: "Dashboard",
  data() {
    return {
      // counter: this.$auth.getCounter
    }
  }
}
</script>
