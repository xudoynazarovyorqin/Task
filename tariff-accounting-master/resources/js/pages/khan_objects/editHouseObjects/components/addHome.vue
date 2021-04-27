<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Создание дома
            </span>
            <el-button type="success" size="small" class="mr-1"> Создать</el-button>
            <!-- <el-button type="primary" size="small" class="mr-1"> Создать закрыть</el-button> -->
            <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <div class="form_add-home">
            <el-form ref="form" :model="form" size="small" class="style__label pa20">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="Название дома">
                            <el-input v-model="form.name"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="ЖК">
                            <el-select v-model="form.regions1"  class="width-select">
                            <el-option label="Zone one" value="shanghai"></el-option>
                            <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Тип строения">
                            <el-select v-model="form.region2"  class="width-select">
                            <el-option label="Zone one" value="shanghai"></el-option>
                            <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Тип помещений по умолчанию">
                            <el-select v-model="form.region3" class="width-select">
                            <el-option label="Zone one" value="shanghai"></el-option>
                            <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Улица">
                            <el-input v-model="form.name1"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Номер дома">
                            <el-input v-model="form.name2" paplaceholder="Например, город Ташкент"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Отделка помещений">
                            <el-input v-model="form.name3"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Материал дома">
                            <el-input v-model="form.name4"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Материал дома">
                            <el-select v-model="form.region4" class="width-select">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Офис продаж">
                            <el-select v-model="form.regions1"  class="width-select">
                            <el-option label="Zone one" value="shanghai"></el-option>
                            <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="Начало строительства" class="bg_color">
                            <el-col :span="12">
                                <el-select v-model="form.region5" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="12">
                                <el-select v-model="form.region6" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Завершение строительства" class="bg_color">
                            <el-col :span="12">
                                <el-select v-model="form.region7" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="12">
                                <el-select v-model="form.region8" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Старт продаж" class="bg_color">
                            <el-col :span="12">
                                <el-select v-model="form.region9" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="12">
                                <el-select v-model="form.region10" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Окончание продаж" class="bg_color">
                            <el-col :span="12">
                                <el-select v-model="form.region11" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="12">
                                <el-select v-model="form.region12" class="width-select">
                                    <el-option label="Zone one" value="shanghai"></el-option>
                                    <el-option label="Zone two" value="beijing"></el-option>
                                </el-select>
                            </el-col>
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