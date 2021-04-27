<template>
  <div class="crm-login-wrap">
    <div class="crm-login-wrap__right my_flex2">
      <div class="col-12 mb-5 text-center">
        <img src="/images/03.png" width="200" />
        <img src="/images/05.png" width="250" />
      </div>
      <el-form
        :model="ruleForm"
        status-icon
        :rules="rules"
        ref="ruleForm"
        label-width="120px"
        label-position="top"
      >
        <el-form-item prop="phone">
          <el-input
            type="text"
            v-model="ruleForm.phone"
            clearable
            placeholder="Телефон"
            autocomplete="off"
          ></el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input
            type="password"
            v-model="ruleForm.password"
            clearable
            placeholder="Пароль"
            autocomplete="off"
            show-password
          ></el-input>
        </el-form-item>
        <el-form-item class="text-center">
          <el-button type="primary" @click="login()" :loading="loading">Авторизоваться</el-button>
        </el-form-item>
      </el-form>
      <!-- <div class="col-12 text-center">
        <a href="#" class="text-white">Забыли Ваш пароль!</a>
        <br>
        <a href="#" class="text-white">Создать новый аккаунт!</a>
      </div>-->
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";
export default {
  name: "Login",
  data() {
    return {
      ruleForm: {
        password: "",
        phone: "+998"
      },
      rules: {
        phone: [
          {
            required: true,
            message: "Пожалуйста, введите телефон ",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "Пожалуйста, введите пароль",
            trigger: "blur"
          },
          {
            min: 3,
            max: 50,
            message: "Длина должна быть от 3 до 50",
            trigger: "blur"
          }
        ]
      },
      redirect: undefined,
      otherQuery: {},
      errors: [],
      loading: false
    };
  },
  watch: {
    $route: {
      handler: function(route) {
        const query = route.query;
        if (query) {
          this.redirect = query.redirect;
          this.otherQuery = this.getOtherQuery(query);
        }
      },
      immediate: true
    }
  },
  methods: {
    ...mapActions({
      postLogin: "auth/login"
    }),
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== "redirect") {
          acc[cur] = query[cur];
        }
        return acc;
      }, {});
    },
    login() {
      this.$refs["ruleForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          this.postLogin(this.ruleForm)
            .then(res => {
              this.loading = false;
              this.$router.push({
                path: this.redirect || "/",
                query: this.otherQuery
              });
            })
            .catch(err => {
              this.loading = false;
              this.$alert(err.response.data.error);
            });
        }
      });
    }
  }
};
</script>
<style lang="css">
.crm-login-wrap {
  background-color: #3f648c;
  display: flex;
  height: 100vh;
  justify-content: center;
  align-items: center;
}
.crm-login-wrap__right {
  /* width: 20%; */
  color: #ffffff;
}
.my_flex2 {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.my_flex2 .el-form {
  width: 80%;
}
.crm-login-wrap__right .el-form-item__label {
  color: #ffffff;
}
</style>
