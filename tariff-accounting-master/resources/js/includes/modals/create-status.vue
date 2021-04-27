<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
               Создать статус
            </span>
            <el-button type="success" size="small" class="mr-1"> Сохранить</el-button>
            <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button>
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <div class="body__modal_client pa20">
            <el-form ref="form" :model="form"  size="small" class="style__label">
                <el-row :gutter="30">
                    <el-col :span="12">
                        <el-form-item label="Статус">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Вид">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        form: {}
      }
    },
    methods: {
      onSubmit() {
        console.log('submit!');
      }
    }
  }
</script>