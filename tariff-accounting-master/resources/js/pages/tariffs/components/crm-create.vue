<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span>Новый Tарифы</span>
      <el-button type="success" size="small" class="mr-1">Сохранить</el-button>
      <el-button type="primary" size="small" class="mr-1">Сохранить и закрыть</el-button>
      <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
    </header>
    <el-form ref="form" :model="form" label-width="220px" class="p-4 pl-0">
      <el-form-item label="Наименование	Tарифы">
        <el-input v-model="form.name"></el-input>
      </el-form-item>
      <el-form-item label="Сумма">
        <el-input v-model="form.name2"></el-input>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: {}
    };
  },
  methods: {
    onSubmit() {
      console.log("submit!");
    }
  }
};
</script>
