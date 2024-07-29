<template>
  <v-div>
    <v-list-item :value="categoria.id">
      <template v-slot:prepend="{ isActive }">
        <v-list-item-action start>
          <v-checkbox-btn :model-value="isActive"></v-checkbox-btn>
        </v-list-item-action>
      </template>

      <v-row no-gutters>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ categoria.name }}</v-list-item-title>
          </v-sheet>
        </v-col>
        <v-col>
          <v-sheet class="pa-2 ma-2">
            <v-list-item-title>{{ formatDate(categoria.created_at) }}</v-list-item-title>
          </v-sheet>
        </v-col>
      </v-row>

      <template v-slot:append>
        <DropdowmMenuCategorias :categoria="categoria" @updateLista="updateLista" />
      </template>
    </v-list-item>
  </v-div>
</template>

<script>
import DropdowmMenuCategorias from '@/components/Categorias/DropdowmMenuCategorias.vue';

export default {
  emits: ['updateLista'],
  components: { DropdowmMenuCategorias },
  name: 'ItemCategoria',
  props: {
    categoria: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}/${month}/${year}`;
    },
    updateLista() {
      this.$emit('updateLista');
    }
  }
}
</script>
