<template>
  <v-div>
    <!-- Spinner de carregamento -->
    <v-progress-circular
      v-if="loading"
      indeterminate
      color="primary"
      class="ma-5"
    ></v-progress-circular>

    <!-- Conteúdo do produto -->
    <v-list-item v-else :value="produto.id">
      <template v-slot:prepend="{ isActive }">
        <v-list-item-action start>
          <v-checkbox-btn :model-value="isActive"></v-checkbox-btn>
        </v-list-item-action>
      </template>

      <v-row no-gutters>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ produto.name }}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ produto.description }}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ produto.price }}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ produto.categoria_name }}</v-list-item-title>
          </v-sheet>
        </v-col>
      </v-row>

      <template v-slot:append>
        <DropdowmMenuProduto :produto="this.produto" @updateLista="updateLista" />
      </template>
    </v-list-item>
  </v-div>
</template>

<script>
import DropdowmMenuProduto from '@/components/Produtos/DropdowmMenuProduto.vue';

export default {
  emits: ['updateLista'],
  components: { DropdowmMenuProduto },
  name: 'ItemProduto',
  props: {
    produto: Object,
    loading: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    updateLista() {
      this.$emit('updateLista');
    },
  }
}
</script>
