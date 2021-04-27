<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
              Создать Договор
            </span>
            <el-button type="success" size="small" class="mr-1"> Сохранить</el-button>
            <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button>
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <div class="body__modal_client pa20">
            <el-form ref="form" :model="form"  size="small" class="style__label">

                <el-row :gutter="30">
                    <el-col :span="8">
                        <el-form-item label="№ Номер Договора">
                            <el-select v-model="form.region" filterable clearable class="width-select"  placeholder="№">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Клиент">
                            <el-select v-model="form.region" filterable clearable class="width-select"  placeholder="Клиент">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Дата Договора">
                                <el-date-picker type="date" placeholder="Дата Договора" v-model="form.date2" ></el-date-picker>
                        </el-form-item>
                    </el-col>
                </el-row>

                

                <el-tabs type="border-card">
                    <el-tab-pane  label="Тариф">
                        <span slot="label"><i class="el-icon-s-grid"></i> Тариф</span>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Наименование услуг</th>
                                    <th>Ед. изм.</th>
                                    <th>Тариф (сум)</th>
                                    <th>НДС (15%)</th>
                                    <th>Тариф с учетом НДС (сум)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Contextual classes can be used to color the whole table</td>
                                    <td>
                                        <el-select v-model="form.region" size="small" filterable clearable class="width-select"  placeholder="Клиент">
                                            <el-option label="Zone one" value="shanghai"></el-option>
                                            <el-option label="Zone two" value="beijing"></el-option>
                                        </el-select>
                                    </td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Contextual classes can be used to color the whole table</td>
                                    <td>
                                        <el-select v-model="form.region" size="small" filterable clearable class="width-select"  placeholder="Клиент">
                                            <el-option label="Zone one" value="shanghai"></el-option>
                                            <el-option label="Zone two" value="beijing"></el-option>
                                        </el-select>
                                    </td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                    <td> <el-input v-model="form.name" size="small"></el-input></td>
                                </tr>
                            </tbody>
                        </table>
                    </el-tab-pane>
                </el-tabs>


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