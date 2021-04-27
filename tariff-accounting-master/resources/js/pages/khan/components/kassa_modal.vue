<template>
    <div>
        
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Приход
            </span>
            <div class="mr-5">
                <el-badge :value="1" class="icon_font item mr-2" type="primary">
                    <i class="el-icon-shopping-cart-full"></i> 
                </el-badge>
                300 000 000
            </div>
            <!-- <el-button type="success" size="small" class="mr-1"> Сохранить</el-button> -->
            <!-- <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button> -->
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>

        <div class="style_cashbox p-3">
            <el-form ref="form" :model="form" size="small"  label-width="100px" class="style__label">
                
                <el-row :gutter="20">
                    
                    <el-col :span="24">
                        <div class="border_mini mb-3  pt-3 pl-3 pr-3 pb-0">
                            <el-row :gutter="20" >
                                <el-col :span="8">
                                    <el-form-item label="Тип проек">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Проект №">
                                        <el-input v-model="form.name2"></el-input>
                                    </el-form-item>
                                </el-col>

                                <el-col :span="8">
                                    <el-button type="success"  size="small">Далее</el-button>
                                </el-col>
                            </el-row>
                        </div>
                    </el-col>

                    <el-col :span="24" class="mb-3">
                        <el-tabs type="border-card">
                            <el-tab-pane>
                                <span slot="label"><i class="el-icon-date"></i> Информатисия</span>

                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Контрагент: ООО "Покупатель"</td>
                                            <td>Общая сумма: 10 000 сум</td>
                                        </tr>
                                        <tr>
                                            <td  class="text-danger">Нет договора</td>
                                            <td  class="text-danger">Оплачено: 0 сум</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </el-tab-pane>
                        </el-tabs>
                        <div class="text-right mt-2">
                            <el-button type="primary"  size="small">Добавить платеж</el-button> 
                            <el-button class="ml-3" type="success"  size="small"  @click="cashsevica = true">Найти  услуги</el-button>
                        </div>
                    </el-col>
                    
                    <el-col :span="24" class="mb-3">
                        <el-tabs type="border-card">
                            <el-tab-pane>
                                <span slot="label"><i class="el-icon-s-grid"></i> История транзакции</span>
                                <table class="table table-bordered color_tab">
                                    <thead>
                                        <tr>
                                            <th>Тип оплаты</th>
                                            <th>Сумма</th>
                                            <th>Валюта</th>
                                            <th>Курс</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Ползаватель</th>
                                            <th>Дата создания</th>
                                            <th>Удалить</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                           <td> Tests Text </td>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                           <td> Test Text </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </el-tab-pane>
                        </el-tabs>
                    </el-col>

                </el-row>
                

            </el-form>
        </div>

        <!-- Components Khan -->
        <el-drawer title="I am the title" :visible.sync="cashsevica"  :append-to-body="true" :with-header="false" size="70%">
            <CashService></CashService>
        </el-drawer>
        <!-- Components Khan -->

        

    </div>
</template>
<script>
    import CashService from "./cashService"
    export default {
         components:{
            CashService,
          
        },
        data() {
            return {
                cashsevica: false,
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