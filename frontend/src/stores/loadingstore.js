// store/loading.js
import { reactive } from 'vue'

const state = reactive({
  loading: false,
})

const setLoading = (value) => {
  state.loading = value
}

export default {
  state,
  setLoading,
}
