<template>
  <v-div>
    <v-menu>
      <template v-slot:activator="{ props }">
        <v-btn color="dark" v-bind="props" icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>
      <v-list>
        <v-list-item
          v-for="(item, index) in items"
          :key="index"
          :value="index"
          @click="item.click()"
        >
          <template v-slot:prepend>
            <v-icon :icon="item.icon" :color="item.color"></v-icon>
          </template>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>

    <ModalEditarCategoria
      v-if="items[0].modal"
      @closeModal="closeModalEditar()"
      :categoria="categoria"
    />

    <ModalDeletarCategoria
      v-if="items[1].modal"
      @closeModal="closeModalDeletar()"
      :categoria="categoria"
    />
  </v-div>
</template>

<script>
import ModalEditarCategoria from '@/components/Categorias/ModalEditarCategoria.vue';
import ModalDeletarCategoria from '@/components/Categorias/ModalDeletarCategoria.vue';

export default {
  emits: ['updateLista'],
  components: { ModalEditarCategoria, ModalDeletarCategoria },
  name: 'DropdownMenuCategoria',
  props: {
    categoria: Object,
  },
  data() {
    return {
      items: [
        {
          icon: 'mdi-pencil',
          color: 'warning',
          title: 'Editar',
          modal: false,
          click() {
            this.modal = true;
          },
        },
        {
          icon: 'mdi-delete',
          color: 'red',
          title: 'Deletar',
          modal: false,
          click() {
            this.modal = true;
          },
        },
      ],
    };
  },
  methods: {
    closeModalEditar() {
      this.items[0].modal = false;
      this.$emit('updateLista');
    },
    closeModalDeletar() {
      this.items[1].modal = false;
      this.$emit('updateLista');
    },
  },
};
</script>
