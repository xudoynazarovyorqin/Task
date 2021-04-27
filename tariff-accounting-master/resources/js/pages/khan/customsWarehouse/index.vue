<template>
    <div class="app-container">
        <div class="mytabel table-sm mr-0 ml-0 p-0">
            <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
                <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
                    <h5 class="d-inline mr-2 font-weight-bold">Заезд и отъезд вагона</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                    <div class="text-center d-flex sorddata ml-3">
                        <el-input       class="ml-3" size="mini" placeholder="Найти" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                        <el-date-picker class="ml-3" size="mini" v-model="filterForm.start_date" type="date" format="yyyy-MM-dd" :value-format="date_format" placeholder="от дата"></el-date-picker>
                        <el-date-picker class="ml-3" size="mini" v-model="filterForm.end_date" type="date" format="yyyy-MM-dd" :value-format="date_format" placeholder="до дата"></el-date-picker>
                    </div>
                </div>
                <div class="col-5 align-items-center align-self-center text-right pr-0 d-flex justify-content-end" >
                   <el-button class="mr-2" size="mini" @click="tableID = true" icon="el-icon-circle-plus-outline"> Создать </el-button>
                    <!-- <export-excel  class="btn excel_btn  mr-2" :data="list" 
                        worksheet="Пользователи" name="Пользователи.xls">
                        <el-button size="mini" icon="el-icon-document-delete"> Excel </el-button>
                    </export-excel> -->
                    <el-dropdown class="mr-2">
                        <el-button size="mini" icon="el-icon-setting">
                            <i class="el-icon-arrow-down el-icon--right"></i>
                        </el-button>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item v-for="(column,index) in columns" :key="index">
                                <el-checkbox :checked="column.show" @change="column.show = !column.show">{{ column.title }}</el-checkbox>
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>

            </div>
            <table class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white" v-loading="loadingData">
                <thead>
                    
                    <tr>
                        <th v-if="columns.id.show">{{ columns.id.title }}</th>
                        <th v-if="columns.id_cod.show">{{ columns.id_cod.title }}</th>
                        <th v-if="columns.receiverProduct.show">{{ columns.receiverProduct.title }}</th>
                        <th v-if="columns.contractNumber.show">{{ columns.contractNumber.title }}</th>
                        <th v-if="columns.typeCar.show">{{ columns.typeCar.title }}</th>
                        <th v-if="columns.railwayNumber.show">{{ columns.railwayNumber.title }}</th>
                        <th v-if="columns.innings.show">{{ columns.innings.title }}</th>
                        <th v-if="columns.unloading.show">{{ columns.unloading.title }}</th>
                        <th v-if="columns.arrivalWag.show">{{ columns.arrivalWag.title }}</th>
                        <th v-if="columns.status.show">{{ columns.status.title }}</th>
                        <th v-if="columns.settings.show">{{ columns.settings.title }}</th>
                    </tr>

                    <tr>
                        <th v-if="columns.id.show">
                            <el-input clearable size="mini" v-model="filterForm.id" class="id_input"
                                :placeholder="columns.id.title"></el-input>
                        </th>
                        <th v-if="columns.id_cod.show">
                            <el-input clearable size="mini" v-model="filterForm.id_cod" class="id_input"
                                :placeholder="columns.id_cod.title"></el-input>
                        </th>
                        <th v-if="columns.receiverProduct.show">
                            <el-select v-model="filterForm.receiverProduct" filterable clearable :placeholder="columns.receiverProduct.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.contractNumber.show">
                            <el-input clearable size="mini" v-model="filterForm.contractNumber" :placeholder="columns.contractNumber.title">
                            </el-input>
                        </th>
                        <th v-if="columns.typeCar.show">
                             <el-select v-model="filterForm.typeCar" filterable clearable :placeholder="columns.typeCar.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.railwayNumber.show">
                             <el-input clearable size="mini" v-model="filterForm.railwayNumber" :placeholder="columns.railwayNumber.title">
                            </el-input>
                        </th>
                        <th v-if="columns.innings.show">
                            <el-date-picker :placeholder="columns.innings.title" v-model="filterForm.innings" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.unloading.show">
                            <el-date-picker :placeholder="columns.unloading.title" v-model="filterForm.unloading" size="mini">
                            </el-date-picker>
                        </th>
                         <th v-if="columns.arrivalWag.show">
                            <el-input clearable size="mini" v-model="filterForm.arrivalWag" :placeholder="columns.arrivalWag.title">
                            </el-input>
                        </th>
                        <th v-if="columns.status.show">
                            <el-select v-model="filterForm.status" filterable clearable :placeholder="columns.status.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.settings.show"></th>
                    </tr>

                </thead>

                <transition-group name="flip-list" tag="tbody">
                    <tr v-for="user in list" :key="user.id" class="cursor-pointer">

                        <td v-if="columns.id.show">{{ user.id }}</td>
                        <td v-if="columns.id_cod.show">{{ user.id_cod }}</td>
                        <td v-if="columns.receiverProduct.show">{{ user.receiverProduct }}</td>
                        <td v-if="columns.contractNumber.show">{{ user.contractNumber }}</td>
                        <td v-if="columns.typeCar.show">{{ user.typeCar }}</td>
                        <td v-if="columns.railwayNumber.show">{{ user.railwayNumber }}</td>
                        <td v-if="columns.innings.show">{{ user.innings }}</td>
                        <td v-if="columns.unloading.show">{{ user.unloading }}</td>
                        <td v-if="columns.arrivalWag.show">{{ user.arrivalWag }}</td>
                        <td v-if="columns.status.show">{{ (user.status === 'active') ? 'Активний' : 'Не астивний' }}</td>
                        <td v-if="columns.settings.show" class="settings-td">
                            <el-dropdown szie="mini">
                            <el-button size="mini" icon="el-icon-setting" round>
                                <i class="el-icon-arrow-down"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown" size="mini">
                                <el-dropdown-item command="edit" icon="el-icon-edit el-icon--left">
                                    Изменить</el-dropdown-item>
                                <el-dropdown-item command="print"
                                    icon="el-icon-printer el-icon--left">Печать</el-dropdown-item>
                                <!-- <el-dropdown-item v-if="permissions.includes(name+'.'+'show')" command="show"  icon="el-icon-view el-icon--left"> Показать</el-dropdown-item> -->
                                <el-dropdown-item  command="back_material"
                                    icon="el-icon-refresh-left el-icon--left">Возврат сырья</el-dropdown-item>
                                <el-dropdown-item command="comments"
                                    icon="el-icon-chat-line-square el-icon--left">Комментарий</el-dropdown-item>
                                <el-dropdown-item  command="delete"
                                    icon="el-icon-delete el-icon--left" @click="open"><el-button class="uadlit" @click="open">Удалить</el-button></el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>

                        </td>
                    </tr>
                </transition-group>
            </table> 
          
        </div> 

        <el-drawer  size="80%" :visible.sync="tableID" :with-header="false">
            <CreateApplication></CreateApplication>
        </el-drawer>

    </div>  
</template>
<script>
import CreateApplication from './components/crm-create-modal'
export default {
    data() {
        return {
            data_s:'',
            loadingData: false,
            tableID: false,
            filterForm:{
                search: '',
                id : '',
                id_cod:'',
                receiverProduct: '',
                contractNumber: '',
                typeCar: '',
                railwayNumber: '',
                innings: '',               
                unloading: '',               
                arrivalWag: '',     
                status: '',          
            },
            list: [
                {
                    "id":'1',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
                 {
                    "id":'2',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
                 {
                    "id":'3',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
                 {
                    "id":'4',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
                 {
                    "id":'5',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
                 {
                    "id":'6',
                    "id_cod": 123455,
                    "receiverProduct":"OOO Asnovi Biznes",
                    "contractNumber":"313U/19",
                    "typeCar":"oddiy",
                    "railwayNumber":"553 19 339",
                    "innings":"30.окт",
                    "unloading":"30.окт",
                    "arrivalWag":"350 000",
                    "status":"active",
                },
            ],
            columns : {
                id : {
                    show: true,
                    title: '№',
                    sortable: true,
                    column: 'id'
                },
                id_cod : {
                    show: false,
                    title: 'ID',
                    sortable: true,
                    column: 'id_cod'
                },
                receiverProduct : {
                    show: true,
                    title: 'Получатель товара',
                    sortable: true,
                    column: 'receiverProduct'
                },
                contractNumber : {
                    show: true,
                    title: 'Номер договор',
                    sortable: true,
                    column: 'contractNumber'
                },
                typeCar : {
                    show: true,
                    title: 'Тип вагона',
                    sortable: true,
                    column: 'typeCar'
                },
                railwayNumber : {
                    show: true,
                    title: 'Д /Ж №',
                    sortable: true,
                    column: 'railwayNumber'
                },
                innings : {
                    show: true,
                    title: 'Подача',
                    sortable: true,
                    column: 'innings'
                },
                unloading : {
                    show: true,
                    title: 'Выгрузка',
                    sortable: true,
                    column: 'unloading'
                },
                arrivalWag : {
                    show: true,
                    title: 'Заезд Ваг',
                    sortable: true,
                    column: 'arrivalWag'
                },
                status : {
                    show: true,
                    title: 'Статус',
                    sortable: true,
                    column: 'status'
                },
                settings : {
                    show: true,
                    title: 'Настройки',
                    sortable: false,
                    column: 'settings'
                },

            },
            tableID: false,
            tableUser: false
        };
    },
    components:{
        CreateApplication,
    },
     methods: {
        open() {
            this.$confirm('Это навсегда удалит файл. Продолжить?', 'Предупреждение', {
                confirmButtonText: 'Да',
                cancelButtonText: 'Отмена',
                type: 'warning'
            })
            .then(() => {
                this.$message({
                    type: 'success',
                    message: 'Удалить завершено'
                });
            })
            .catch(() => {
                this.$message({
                    type: 'info',
                    message: 'Удалить отменено'
                });          
            });
        }
    },
    
}
</script>
