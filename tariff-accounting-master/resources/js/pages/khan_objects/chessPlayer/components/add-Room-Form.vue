<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Создать помещение
            </span>
            <el-button type="success" size="small" class="mr-1"> Создать</el-button>
            <el-button type="primary" size="small" class="mr-1"> Создать закрыть</el-button>
            <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <el-form ref="form" :model="form" size="small" class="add_room_form style__label pa20">
            <el-row :gutter="20">
                <el-col :span="8">
                    <el-form-item label="Тип помещения"  class="width-select2">
                        <el-select v-model="form.name1">
                            <el-option label="Квартира" value="beijing2"></el-option>
                            <el-option label="Апартаменты" value="beijing"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Номер помещения">
                        <el-input v-model="form.name2"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Кол-во комнат">
                        <el-input v-model="form.name3"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Площадь, м2">
                        <el-input v-model="form.name4"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                   <el-form-item label="Статус"  class="width-select2">
                        <el-select v-model="form.name5">
                            <el-option label="Свободно" value="beijing3"></el-option>
                            <el-option label="Бронь" value="beijing4"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Полная цена">
                        <el-input v-model="form.name6"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="Код планировки">
                        <el-input v-model="form.name7"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24" class="d-flex">
                    <el-form-item label="Instant delivery">
                        <el-switch v-model="form.delivery2"></el-switch>
                    </el-form-item>
                    <el-form-item label="Instant delivery">
                        <el-switch v-model="form.delivery"></el-switch>
                    </el-form-item>
                </el-col>
            </el-row>


            <el-tabs type="border-card">

                <el-tab-pane label="Основные свойства">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-form-item label="Номер на площадке">
                                <el-input v-model="form.name8"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Жилая площадь, м2">
                                <el-input v-model="form.name9"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Расчетная площадь, м2">
                                <el-input v-model="form.name10"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Площадь кухни, м2">
                                <el-input v-model="form.name11"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Кол-во лоджий">
                                <el-input v-model="form.name12"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Куда выходят окна"  class="width-select2">
                                <el-select v-model="form.name13">
                                    <el-option label="Свободно" value="beijing3"></el-option>
                                    <el-option label="Бронь" value="beijing4"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Кол-во балконов">
                                <el-input v-model="form.name14"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Кол-во совмещенных санузлов">
                                <el-input v-model="form.name15"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Кол-во раздельных санузлов">
                                <el-input v-model="form.name16"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item label="Отделка"  class="width-select2">
                                <el-select v-model="form.name17">
                                    <el-option label="Свободно" value="beijing3"></el-option>
                                    <el-option label="Бронь" value="beijing4"></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-tab-pane>

                <el-tab-pane label="Дополнительные поля">
                     <el-row :gutter="20">
                        <el-col :span="12">
                            <el-form-item label="Номер помещения для БТИ">
                                <el-input v-model="form.name18"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Общая площадь по БТИ">
                                <el-input v-model="form.name19"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Площадь без балкона">
                                <el-input v-model="form.name20"></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="12">
                            <el-form-item label="Площадь прихожей, м2">
                                <el-input v-model="form.name21"></el-input>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-tab-pane>

            </el-tabs>

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
