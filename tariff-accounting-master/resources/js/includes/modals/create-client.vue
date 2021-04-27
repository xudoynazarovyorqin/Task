<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Новый Клиент
            </span>
            <el-button type="success" size="small" class="mr-1"> Сохранить</el-button>
            <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button>
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <div class="body__modal_client pa20">
            <el-form ref="form" :model="form"  size="small" class="style__label">
                <el-row :gutter="30">
                    <el-col :span="12">
                        <el-form-item label="Фамилия">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                         <el-form-item label="Имя">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                         <el-form-item label=" Отчество">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="E-mail адрес:">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="Комментарий">
                            <el-input  type="textarea"  v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item   label="Юридическое лицо:">
                            <el-switch
                                v-model="form.value1"
                                active-color="#13ce66"
                                >
                            </el-switch>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Код">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="Фактический адрес">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                         <el-form-item label="Полное наименование">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="ИНН">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                        <el-form-item label="Юридический адрес">
                            <el-input  type="textarea"  v-model="form.name"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="24">
                        <el-row :gutter="30">

                            <el-col :span="12">
                                <el-form-item label="МФО">
                                    <el-input v-model="form.name"></el-input>
                                </el-form-item>
                                <el-form-item label="ОКОНХ">
                                    <el-input v-model="form.name"></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="ОКЭД">
                                    <el-input v-model="form.name"></el-input>
                                </el-form-item>
                                <el-form-item label="РКП НДС">
                                    <el-input v-model="form.name"></el-input>
                                </el-form-item>
                            </el-col>

                        </el-row>
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