<template>
    <div>
         <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Создание объекта
            </span>
            <el-button type="success" size="small" class="mr-1"> Создать</el-button>
            <!-- <el-button type="primary" size="small" class="mr-1"> Создать закрыть</el-button> -->
            <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <el-form ref="form" :model="form" size="small" class="style__label pa20">
            <el-row :gutter="20">
                <el-col :span="24">
                    <el-radio-group v-model="radio3" size="small">
                        <el-radio-button label="Жилой комплекс"></el-radio-button>
                        <el-radio-button label="Микрорайон"></el-radio-button>
                        <el-radio-button label="Квартал" disabled></el-radio-button>
                    </el-radio-group>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Название объекта">
                        <el-input v-model="form.name1"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Регион">
                        <el-input v-model="form.name2" paplaceholder=" Например, Ташкентской область"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Район">
                        <el-input v-model="form.name3" paplaceholder="Например, Камышловский Ташкент"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Город">
                        <el-input v-model="form.name4" paplaceholder="Например, город Ташкент"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Бренд застройщика">
                        <el-input v-model="form.name5"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-form-item label="Сайт">
                        <el-input v-model="form.name6"></el-input>
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
         
        }
      }
    },
    methods: {
      onSubmit() {
        console.log('submit!');
      }
    }
  }
</script>