<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Новый заказ на производство  
            </span>
            <el-button type="success" size="small" class="mr-1"> Сохранить</el-button>
            <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button>
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>
        <div class="body__modal">
            <el-form ref="form" :model="form"  size="small" class="style__label">
                
                <el-tabs type="border-card" class="mb-4">
                    <el-tab-pane label="Главная">
                        <el-row :gutter="20">
                            <el-col :span="8">

                                <el-form-item label="Клиент" class="flexx">
                                    <el-select v-model="form.region" placeholder="пожалуйста, выберите клиента">
                                        <el-option label="Zone one" value="shanghai"></el-option>
                                        <el-option label="Zone two" value="beijing"></el-option>
                                    </el-select>
                                    <div class="style__icon_plus" @click="cilent__modal = true"><i class="el-icon-plus"></i></div> 
                                </el-form-item>

                            </el-col>
                            <el-col :span="8">

                                <el-form-item label="Статус" class="flexx">
                                    <el-select v-model="form.region" placeholder="пожалуйста, выберите статус">
                                        <el-option label="Zone one" value="shanghai"></el-option>
                                        <el-option label="Zone two" value="beijing"></el-option>
                                    </el-select>
                                    <div class="style__icon_plus"  @click="cilent__status = true"><i class="el-icon-plus"></i></div> 
                                </el-form-item>

                            </el-col>
                            <el-col :span="8">

                                <el-form-item label="Договор" class="flexx">
                                    <el-select v-model="form.region" placeholder="Договор">
                                        <el-option label="Zone one" value="shanghai"></el-option>
                                        <el-option label="Zone two" value="beijing"></el-option>
                                    </el-select>
                                    <div class="style__icon_plus" @click="cilent__priority = true"><i class="el-icon-plus"></i></div> 
                                </el-form-item>

                            </el-col>
                        
                            
                        </el-row>
                    </el-tab-pane>
                </el-tabs>

                <el-tabs type="border-card" class="mb-4">
                    <el-tab-pane label="Главная 2">
                        <div class="add_histoiy_order">
                            <el-row :gutter="20">

                                <el-col :span="8">
                                    <el-form-item label="Тип вагона">
                                        <el-select v-model="form.region"  filterable clearable class="width-select" placeholder="Test">
                                            <el-option label="Zone one" value="shanghai"></el-option>
                                            <el-option label="Zone two" value="beijing"></el-option>
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Д /Ж №">
                                        <el-select v-model="form.region" filterable clearable class="width-select"  placeholder="Test">
                                            <el-option label="Zone one" value="shanghai"></el-option>
                                            <el-option label="Zone two" value="beijing"></el-option>
                                        </el-select>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Подача">
                                        <el-date-picker type="date" placeholder="Pick a date" v-model="form.date1" ></el-date-picker>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Выгрузка">
                                        <el-date-picker type="date" placeholder="Pick a date" v-model="form.date2" ></el-date-picker>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="Заезд Ваг">
                                        <el-input v-model="form.name"></el-input>
                                    </el-form-item>
                                </el-col>
                

                            </el-row>    
                        </div>  
                    </el-tab-pane>
                </el-tabs>
                  

            </el-form>
        </div>

        <el-drawer  size="70%" :append-to-body="true" :visible.sync="cilent__modal" :with-header="false">
             <CreateClientComponents></CreateClientComponents>
        </el-drawer>
        <el-drawer  size="70%" :append-to-body="true" :visible.sync="cilent__status" :with-header="false">
             <CreateStatusComponents></CreateStatusComponents>
        </el-drawer>
        <el-drawer  size="70%" :append-to-body="true" :visible.sync="cilent__priority" :with-header="false">
             <CreatePriorityComponents></CreatePriorityComponents>
        </el-drawer>

    </div>
</template>
<script>
    import CreateClientComponents from '@/includes/modals/create-client'
    import CreateStatusComponents from '@/includes/modals/create-status'
    import CreatePriorityComponents from '@/includes/modals/create-priority'
    export default {
         components:{
            CreateClientComponents,
            CreateStatusComponents,
            CreatePriorityComponents
        },
        data() {
            return {
                form: {

                },
                cilent__modal: false,
                cilent__status: false,
                cilent__priority: false,
            };
        },
        methods: {
            handleClick(tab, event) {
                console.log(tab, event);
            }
        }
    };
</script>
