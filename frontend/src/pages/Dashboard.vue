<template>
  <base-layout titlecard="Dashboard">
    <v-container>
      <v-card class="pa-5 elevation-0">
        <v-card-title>
          <span class="headline">Dashboard</span>
        </v-card-title>
        <!-- Indicador de Carregamento -->
        <v-row v-if="loading" class="d-flex justify-center">
          <v-progress-circular indeterminate color="primary" size="64"></v-progress-circular>
        </v-row>

        <!-- Gráficos de Dados -->
        <v-row v-else>
          <!-- Gráfico de Usuários -->
          <v-col cols="12" md="4">
            <v-card>
              <v-card-title>
                <span class="headline">Usuários</span>
              </v-card-title>
              <v-card-subtitle>
                Distribuição de Usuários
              </v-card-subtitle>
              <v-card-text>
                <Bar :data="formattedUserData" :options="chartOptions" />
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Gráfico de Produtos -->
          <v-col cols="12" md="4">
            <v-card>
              <v-card-title>
                <span class="headline">Produtos</span>
              </v-card-title>
              <v-card-subtitle>
                Distribuição de Produtos
              </v-card-subtitle>
              <v-card-text>
                <Pie :data="formattedProductData" :options="chartOptions" />
              </v-card-text>
            </v-card>
          </v-col>

          <!-- Gráfico de Categorias -->
          <v-col cols="12" md="4">
            <v-card>
              <v-card-title>
                <span class="headline">Categorias</span>
              </v-card-title>
              <v-card-subtitle>
                Distribuição de Categorias
              </v-card-subtitle>
              <v-card-text>
                <Doughnut :data="formattedCategoryData" :options="chartOptions" />
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-card>
    </v-container>
  </base-layout>
</template>

<script>
import { Bar, Pie, Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement } from 'chart.js';
import { api, except } from '@/plugins/api';

// Registra os componentes do Chart.js
ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement);

export default {
  name: 'Dashboard',
  components: { Bar, Pie, Doughnut },
  data() {
    return {
      loading: true,
      userData: {
        labels: [],
        datasets: [
          {
            label: 'Número de Usuários',
            data: [],
            backgroundColor: ['#FF6384'],
            borderColor: '#fff',
            borderWidth: 1
          }
        ]
      },
      productData: {
        labels: [],
        datasets: [
          {
            label: 'Número de Produtos',
            data: [],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            borderColor: '#fff',
            borderWidth: 1
          }
        ]
      },
      categoryData: {
        labels: [],
        datasets: [
          {
            label: 'Número de Categorias',
            data: [],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
            borderColor: '#fff',
            borderWidth: 1
          }
        ]
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false
      }
    };
  },
  computed: {
    formattedUserData() {
      // Exemplo de transformação de dados para o gráfico de usuários
      return {
        labels: this.userData.labels,
        datasets: [{
          label: 'Número de Usuários',
          data: this.userData.datasets[0]?.data || [],
          backgroundColor: ['#FF6384'],
          borderColor: '#fff',
          borderWidth: 1
        }]
      };
    },
    formattedProductData() {
      // Exemplo de transformação de dados para o gráfico de produtos
      return {
        labels: this.productData.labels,
        datasets: [{
          label: 'Número de Produtos',
          data: this.productData.datasets[0]?.data || [],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
          borderColor: '#fff',
          borderWidth: 1
        }]
      };
    },
    formattedCategoryData() {
      // Exemplo de transformação de dados para o gráfico de categorias
      return {
        labels: this.categoryData.labels,
        datasets: [{
          label: 'Número de Categorias',
          data: this.categoryData.datasets[0]?.data || [],
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
          borderColor: '#fff',
          borderWidth: 1
        }]
      };
    }
  },
  async created() {
    try {
      const response = await api(this).get('/dashboard/get');
      const data = response.data;

      this.userData = data.userData;
      this.productData = data.productData;
      this.categoryData = data.categoryData;
    } catch (erro) {
      except(this,erro)
      console.error('Erro ao buscar dados do dashboard:', error);
    } finally {
      this.loading = false;
    }
  }
};
</script>

<style scoped>
.v-card {
  margin-bottom: 20px;
}

.v-progress-circular {
  margin: auto;
}
</style>
