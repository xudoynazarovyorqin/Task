<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span title="Новый заказ">Создать помещение</span>
      <el-button type="success" size="small" class="mr-1">Создать</el-button>
      <el-button type="primary" size="small" class="mr-1">Создать закрыть</el-button>
      <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
    </header>

    <el-form ref="form" :model="form" size="small" class="add_room_form style__label pa20">
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form-item label="Должность ">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Телефон">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Пароль" prop="checkPass">
            <el-input type="password" v-model="form.checkPass" autocomplete="off"></el-input>
          </el-form-item>
          <el-form-item label="Пин код">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Имя ">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Фамилия">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Отчество ">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Эл. адрес ">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Роль" class="width-select2">
            <el-select v-model="form.region">
              <el-option label="Zone one" value="shanghai"></el-option>
              <el-option label="Zone two" value="beijing"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Статус" class="width-select2">
            <el-select v-model="form.region">
              <el-option label="Zone one" value="shanghai"></el-option>
              <el-option label="Zone two" value="beijing"></el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="24">
          <el-form-item>
            <el-switch
              class="svvichLL"
              style="display: block"
              v-model="form.value2"
              active-text
              inactive-text="Сотрудник"
            ></el-switch>
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="20">
        <el-col :span="8">
          <el-form-item label="Статус" class="width-select2">
            <el-select v-model="form.region" multiple filterable allow-create default-first-option>
              <el-option label="Zone one" value="shanghai"></el-option>
              <el-option label="Zone two" value="beijing"></el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Дата Принятия">
            <el-date-picker
              type="date"
              placeholder="Pick a date"
              v-model="form.date1"
              style="width: 100%;"
            ></el-date-picker>
          </el-form-item>
        </el-col>
        <el-col :span="24">
          <el-form-item>
            <el-switch
              class="svvichLL"
              style="display: block"
              v-model="form.value2"
              active-text
              inactive-text="Уволить"
            ></el-switch>
          </el-form-item>
        </el-col>
        <el-col :span="8">
          <el-form-item label="Дата Уволнения">
            <el-date-picker
              type="date"
              placeholder="Pick a date"
              v-model="form.date1"
              style="width: 100%;"
            ></el-date-picker>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      form: {
        name: "",
        region: "",
        date1: "",
        date2: "",
        delivery: false,
        type: [],
        resource: "",
        desc: ""
      }
    };
  },
  methods: {
    onSubmit() {
      console.log("submit!");
    }
  }
};
</script>
