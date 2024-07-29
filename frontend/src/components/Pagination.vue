<template>
  <div class="pagination">
    <button
      v-for="(page, index) in pages"
      :key="page"
      class="item"
      :class="{ current: page === current }"
      @click="changePage(index + 1)"
    >
      {{ page }}
    </button>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    offset: {
      type: [String, Number],
      default: 0,
    },
    total: {
      type: [String, Number],
      required: true,
    },
    limit: {
      type: [String, Number],
      default: 10,
    },
  },
  computed: {
    current() {
      return this.offset ? this.offset + 1 : 1;
    },
    pages() {
      const qty = Math.ceil(this.total / this.limit);
      if (qty <= 1) return [1];
      return Array.from(Array(qty).keys(), (i) => i + 1);
    },
  },
  methods: {
    changePage(offset) {
      this.$emit('change-page', offset);
    },
  },
};
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}
.item {
  margin: 0 5px;
  padding: 10px;
  cursor: pointer;
}
.current {
  font-weight: bold;
}
</style>
