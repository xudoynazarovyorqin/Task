<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span title="Новый заказ">Добавить Расписание</span>
      <el-button type="success" size="small" class="mr-1">Создать</el-button>
      <el-button type="primary" size="small" class="mr-1">Создать закрыть</el-button>
      <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
    </header>

    <el-form ref="form" :model="form" size="small" class="add_room_form style__label pa20">
      <el-row :gutter="60">
        <el-col :span="8">
          <el-form-item label="Наименования">
            <el-input v-model="form.name"></el-input>
          </el-form-item>
          <el-form-item label="Родительская группа" class="width-select2">
            <el-select v-model="form.region" multiple filterable allow-create default-first-option>
              <el-option label="Zone one" value="shanghai"></el-option>
              <el-option label="Zone two" value="beijing"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Описания">
            <el-input type="textarea" v-model="form.desc"></el-input>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item>
            <el-switch
              class="svvichLL"
              style="display: block"
              v-model="form.value2"
              active-text
              inactive-text="Назначить правила работы группы"
            ></el-switch>
          </el-form-item>
          <div class="invisible_area">
            <el-form-item label="Рабочая время">
              <el-time-picker
                class="timee w-100"
                is-range
                v-model="form.value1"
                range-separator="До"
                start-placeholder="Время начала"
                end-placeholder="Время окончания"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="check in">
              <el-time-picker
                class="timee w-100"
                is-range
                v-model="form.value1"
                range-separator="До"
                start-placeholder="Время начала"
                end-placeholder="Время окончания"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="check out">
              <el-time-picker
                class="timee w-100"
                is-range
                v-model="form.value1"
                range-separator="До"
                start-placeholder="Время начала"
                end-placeholder="Время окончания"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Допустимое опоздания">
              <el-time-picker
                v-model="form.value1"
                placeholder="Arbitrary time"
                style="width:230px !important;;"
              ></el-time-picker>
            </el-form-item>
            <el-form-item label="Штрафное опоздания">
              <el-time-picker
                v-model="form.value1"
                placeholder="Arbitrary time"
                style="width:230px !important;"
              ></el-time-picker>
            </el-form-item>
          </div>
          <el-form-item>
            <el-switch
              class="svvichLL mt-2 mb-2"
              style="display: block"
              v-model="form.value3"
              active-text
              inactive-text="Короткие дни"
            ></el-switch>
          </el-form-item>
          <div class="invisible_area">
            <el-form-item label="Дни" class="width-select2">
              <el-select
                v-model="form.region"
                multiple
                filterable
                allow-create
                default-first-option
              >
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="Рабочая время">
              <el-time-picker
                class="timee w-100"
                is-range
                v-model="form.value1"
                range-separator="До"
                start-placeholder="Время начала"
                end-placeholder="Время окончания"
              ></el-time-picker>
            </el-form-item>
          </div>
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
