<template>
  <v-div>
    <v-dialog v-model="dialog" max-width="600" persistent>
      <v-card prepend-icon="mdi-pencil" title="Alterar Usuário">
        <v-card-item>
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="name"
                :rules="nameRules"
                label="Nome completo"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-text-field
                v-model="email"
                :rules="emailRules"
                label="E-mail"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12">
              <v-checkbox
                v-model="showPasswordFields"
                label="Atualizar Senha"
                class="mb-4"
              ></v-checkbox>
            </v-col>

            <v-col v-if="showPasswordFields" cols="12">
              <v-text-field
                type="password"
                v-model="newPassword"
                :rules="passwordRules"
                label="Nova Senha"
                hint="A nova senha deve ter pelo menos 6 caracteres"
                append-icon="mdi-eye"
                :type="passwordVisible ? 'text' : 'password'"
                @click:append="togglePasswordVisibility"
              >
              </v-text-field>
            </v-col>

            <v-col v-if="showPasswordFields" cols="12">
              <v-text-field
                type="password"
                v-model="confirmPassword"
                :rules="confirmPasswordRules"
                label="Confirmar Senha"
                append-icon="mdi-eye"
                :type="passwordVisible ? 'text' : 'password'"
                @click:append="togglePasswordVisibility"
              >
              </v-text-field>
            </v-col>
          </v-row>
        </v-card-item>
        <template v-slot:actions>
          <v-spacer></v-spacer>

          <v-btn class="bg-red" @click="$emit('closeModal')" :disabled="loading">
            Cancelar
          </v-btn>

          <v-btn class="bg-success" @click="salvar" :disabled="loading">
            <v-icon v-if="loading">mdi-loading</v-icon> <!-- Ícone de loading -->
            Salvar
          </v-btn>
        </template>
      </v-card>
    </v-dialog>
  </v-div>
</template>

<script>
import { api, except } from '@/plugins/api'
import { useNotificationsStore } from '@/stores/notifications';

export default {
  name: 'ModalEditarUsuario',
  emits: ['closeModal'],
  props: {
    usuario: Object
  },
  data() {
    return {
      dialog: true,
      name: '',
      email: '',
      newPassword: '',
      confirmPassword: '',
      showPasswordFields: false,
      passwordVisible: false,
      loading: false, // Adicionado estado de carregamento
      id: 0,
      // ------------------ validações
      nameRules: [
        value => value ? true : "O nome é obrigatório!"
      ],
      emailRules: [
        value => value ? true : 'E-mail é obrigatório',
        value => /.+@.+\..+/.test(value) ? true : 'E-mail é inválido!'
      ],
      passwordRules: [
        value => value.length >= 6 ? true : 'A nova senha deve ter pelo menos 6 caracteres'
      ],
      confirmPasswordRules: [
        value => value === this.newPassword ? true : 'As senhas não coincidem'
      ]
    };
  },
  created() {
    this.name = this.usuario.name;
    this.email = this.usuario.email;
    this.id = this.usuario.id;
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async salvar() {
      var erros = [];
      var mensagem = [];

      // Validação adicional para senhas
      if (this.showPasswordFields) {
        if (!this.newPassword || !this.confirmPassword) {
          erros.push({
            type: 'error',
            title: 'Erro',
            message: 'A nova senha e a confirmação de senha são obrigatórias'
          });
        } else if (this.newPassword !== this.confirmPassword) {
          erros.push({
            type: 'error',
            title: 'Erro',
            message: 'As senhas não coincidem'
          });
        }
      }

      if (erros.length) {
        useNotificationsStore().clearMessage();
        useNotificationsStore().addMessagesAll(erros);
        return;
      }

      // Inicia o carregamento
      this.loading = true;

      const updateData = {
        name: this.name,
        email: this.email,
        id: this.id
      };

      if (this.showPasswordFields) {
        updateData.password = this.newPassword;
      }

      await api(this).post("user/update", updateData)
        .then(response => {
          if (response.data.status === 200) {
            mensagem.push({
              type: 'success',
              title: 'Sucesso: editar usuário',
              message: response.data.msg
            });
            useNotificationsStore().clearMessage();
            useNotificationsStore().addMessagesAll(mensagem);
          } else {
            if (response.data.hasOwnProperty('data')) {
              for (const key in response.data.data) {
                if (response.data.data.hasOwnProperty(key)) {
                  erros.push({
                    type: 'error',
                    title: 'Erro: editar usuário',
                    message: `${response.data.data[key]}`
                  });
                }
              }
              useNotificationsStore().clearMessage();
              useNotificationsStore().addMessagesAll(erros);
            }
          }
        })
        .catch((erro) => {
          except(this, erro);
          console.log(erro);
        })
        .finally(() => {
          // Finaliza o carregamento
          this.loading = false;
          this.$emit('closeModal');
        });
    }
  }
}
</script>

