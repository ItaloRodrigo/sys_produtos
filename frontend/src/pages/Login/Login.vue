<script scoped>
export default {
  name: "Login",
  data() {
    return {
      errors: [],
      user: {
        email: null,
        senha: null
      },
      visible: false,
    }
  },
  mounted() {
    this.user.email = "admin@gmail.com";
    this.user.password = "admin";
  },
  methods: {
    validateEmail() {
      let arroba = -1;
      let pos = -1;
      let pre = -1;
      if (this.user.email != null) {
        arroba = this.user.email.indexOf('@');
        pos = this.user.email.substr(arroba, 1) ? this.user.email.substr(arroba, 1) : -1;
        pre = this.user.email.substr(0, arroba) ? this.user.email.substr(0, arroba) : -1;
      }
      /**
       * return [
            arroba, // validação do arroba
            pos, // validação pós arroba
            pre // validação pré arroba
        ];
       *
      */
      if ((arroba != -1) && (pos != -1) && (pre != -1)) {
        return true;
      }
      return false;
    },
    async autenticacao() {
      this.errors = [];
      if (this.user.email != null && this.user.password != null) {
        try {
          const user = await this.$auth.login({
            email: this.user.email,
            password: this.user.password,
            remember: false
          });
          if (user.logado) {
            this.$router.push({ name: "home" });
          }
          this.errors.push("Falha no login");
          // console.log(user);
        }
        catch (erro) {
          console.log(erro);
          // let data = erro.response.data || {};
          // if (erro.response.status == 400) {
          //   let errors = [];
          //   Object.values(data).forEach(item => item.forEach(men => errors.push(men)));
          //   this.errors = errors;
          // } else except(this, erro);
        }
      } else {
        if (!this.validateEmail()) this.errors[0] = "Email inválido!";
        if ((this.user.password == null) || (this.user.password == "")) this.errors[1] = "Senha em branco!";
      }
    }
  }
}
</script>

<!-- <style src="./styles.scss" lang="scss" scoped></style> -->

<template>
  <div>
    <v-img
      class="mx-auto my-6"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <div class="text-subtitle-1 text-medium-emphasis">Conta</div>

      <v-text-field
        density="compact"
        placeholder="Endereço de email"
        prepend-inner-icon="mdi-email-outline"
        variant="outlined"
        v-model="user.email"
      ></v-text-field>

      <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
        Senha

        <a
          class="text-caption text-decoration-none text-blue"
          href="#"
          rel="noopener noreferrer"
          target="_blank"
        >
          Esqueceu a senha de login?</a>
      </div>

      <v-text-field
        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'"
        density="compact"
        placeholder="Digite a sua senha"
        prepend-inner-icon="mdi-lock-outline"
        variant="outlined"
        @click:append-inner="visible = !visible"
        v-model="user.password"
      ></v-text-field>

      <!-- <v-card
        class="mb-12"
        color="surface-variant"
        variant="tonal"
      >
        <v-card-text class="text-medium-emphasis text-caption">
          Aviso: Após 3 tentativas consecutivas de login malsucedidas, sua conta será temporariamente bloqueada por três horas. Se precisar fazer login agora, você também pode clicar em "Esqueceu a senha de login?" abaixo para redefinir a senha de login
        </v-card-text>
      </v-card> -->

      <v-btn
        class="mb-8"
        color="blue"
        size="large"
        variant="tonal"
        block
        @click="autenticacao()"
      >
        Log In
      </v-btn>

      <v-card-text class="text-center">
        <a
          class="text-blue text-decoration-none"
          href="#"
          rel="noopener noreferrer"
          target="_blank"
        >
          Inscreva-se agora! <v-icon icon="mdi-chevron-right"></v-icon>
        </a>
      </v-card-text>
      <div class="invalid-feedback">
        <ul>
          <li v-for="(item) in errors" :key="item">
            {{ item }}
          </li>
        </ul>
      </div>
    </v-card>
  </div>
</template>
