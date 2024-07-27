<!-- components/Loading.vue -->
<template>
  <v-progress-linear
    v-if="loading"
    color="primary"
    indeterminate
    absolute
    top
  ></v-progress-linear>
</template>

<script>
import { computed } from 'vue'
import store from '@/stores/loadingstore'

export default {
  setup() {
    const loading = computed(() => store.state.loading)
    return { loading }
  }
}
</script>

<style>
.v-progress-linear {
  z-index: 2000;
}
</style>
