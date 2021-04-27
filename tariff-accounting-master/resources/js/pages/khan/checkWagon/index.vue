<template>
    <div class="app-container">
        
        <div class="mytabel table-sm mr-0 ml-0 p-0">
            <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
                <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
                    <h5 class="d-inline mr-2 font-weight-bold">Таможенный склад. Погрузка</h5>
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
                        <th v-if="columns.importer.show">{{ columns.importer.title }}</th>
                        <th v-if="columns.cargo.show">{{ columns.cargo.title }}</th>
                        <th v-if="columns.contCar.show">{{ columns.contCar.title }}</th>
                        <th v-if="columns.allTn.show">{{ columns.allTn.title }}</th>
                        <th v-if="columns.acceptance.show">{{ columns.acceptance.title }}</th>
                        <th v-if="columns.pallet.show">{{ columns.pallet.title }}</th>
                        <th v-if="columns.rate.show">{{ columns.rate.title }}</th>
                        <th v-if="columns.storagePeriodC.show">{{ columns.storagePeriodC.title }}</th>
                        <th v-if="columns.storagePeriodUntil.show">{{ columns.storagePeriodUntil.title }}</th>
                        <th v-if="columns.status.show">{{ columns.status.title }}</th>
                        <th v-if="columns.numberStorage.show">{{ columns.numberStorage.title }}</th>
                        <th v-if="columns.totalAmount.show">{{ columns.totalAmount.title }}</th>
                        <th v-if="columns.settings.show">{{ columns.settings.title }}</th>
                    </tr>

                    <tr>
                        <th v-if="columns.id.show">
                            <el-input clearable size="mini" v-model="filterForm.id" class="id_input"
                                :placeholder="columns.id.title"></el-input>
                        </th>
                        <th v-if="columns.importer.show">
                            <el-select v-model="filterForm.importer" filterable clearable :placeholder="columns.importer.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.cargo.show">
                            <el-input clearable size="mini" v-model="filterForm.cargo" :placeholder="columns.cargo.title">
                            </el-input>
                        </th>
                        <th v-if="columns.contCar.show">
                            <el-input clearable size="mini" v-model="filterForm.contCar" :placeholder="columns.contCar.title">
                            </el-input>
                        </th>
                        <th v-if="columns.allTn.show">
                             <el-input clearable size="mini" v-model="filterForm.allTn" :placeholder="columns.allTn.title">
                            </el-input>
                        </th>
                        <th v-if="columns.acceptance.show">
                            <el-date-picker :placeholder="columns.acceptance.title" v-model="filterForm.acceptance" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.pallet.show">
                            <el-input clearable size="mini" v-model="filterForm.pallet" :placeholder="columns.pallet.title">
                            </el-input>
                        </th>
                        <th v-if="columns.rate.show">
                            <el-input clearable size="mini" v-model="filterForm.rate" :placeholder="columns.rate.title">
                            </el-input>
                        </th>
                         <th v-if="columns.storagePeriodC.show">
                            <el-date-picker :placeholder="columns.storagePeriodC.title" v-model="filterForm.storagePeriodC" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.storagePeriodUntil.show">
                            <el-date-picker :placeholder="columns.storagePeriodUntil.title" v-model="filterForm.storagePeriodUntil" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.status.show">
                            <el-select v-model="filterForm.status" filterable clearable :placeholder="columns.status.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.numberStorage.show">
                            <el-input clearable size="mini" v-model="filterForm.numberStorage" :placeholder="columns.numberStorage.title">
                            </el-input>
                        </th>
                        <th v-if="columns.totalAmount.show">
                            <el-input clearable size="mini" v-model="filterForm.totalAmount" :placeholder="columns.totalAmount.title">
                            </el-input>
                        </th>
                   
                        <th v-if="columns.settings.show"></th>
                    </tr>

                </thead>

                <transition-group name="flip-list" tag="tbody">
                    <tr v-for="user in list" :key="user.id" class="cursor-pointer">

                        <td v-if="columns.id.show">{{ user.id }}</td>
                        <td v-if="columns.importer.show">{{ user.importer }}</td>
                        <td v-if="columns.cargo.show">{{ user.cargo }}</td>
                        <td v-if="columns.contCar.show">{{ user.contCar }}</td>
                        <td v-if="columns.allTn.show">{{ user.allTn }}</td>
                        <td v-if="columns.acceptance.show">{{ user.acceptance }}</td>
                        <td v-if="columns.pallet.show">{{ user.pallet }}</td>
                        <td v-if="columns.rate.show">{{ user.rate }}</td>
                        <td v-if="columns.storagePeriodC.show">{{ user.storagePeriodC }}</td>
                        <td v-if="columns.storagePeriodUntil.show">{{ user.storagePeriodUntil }}</td>
                        <td v-if="columns.status.show">{{ (user.status === 'active') ? 'Активний' : 'Не астивний' }}</td>
                        <td v-if="columns.numberStorage.show">{{ user.numberStorage }}</td>
                        <td v-if="columns.totalAmount.show">{{ user.totalAmount }}</td>
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
            drawer: false,
            filterForm:{
                search: '',
                id : '',
                importer:'',
                cargo: '',
                contCar: '',
                allTn: '',
                acceptance: '',
                pallet: '',               
                rate: '',               
                storagePeriodC: '',
                storagePeriodUntil:'',     
                status: '',  
                numberStorage:'',     
                totalAmount:'',     

            },
        list: [
                {
                    "id":'1',
                    "importer": "CHIF 'Jitong' ",
                    "cargo":"Напитки",
                    "contCar":"234 95 084",
                    "allTn":"26,73",
                    "acceptance":"20.02.19",
                    "pallet":"24т",
                    "rate":"3000",
                    "storagePeriodC":"28.08.19",
                    "storagePeriodUntil":"14.09.19",
                    "status":"active",
                    "numberStorage":"18",
                    "totalAmount":"1 350 000",
                },
                {
                    "id":'2',
                    "importer": "CHIF 'Jitong' ",
                    "cargo":"Чой в меш ",
                    "contCar":"234 95 084",
                    "allTn":"26,73",
                    "acceptance":"20.02.19",
                    "pallet":"23т",
                    "rate":"3000",
                    "storagePeriodC":"28.08.19",
                    "storagePeriodUntil":"14.09.19",
                    "status":"active",
                    "numberStorage":"18",
                    "totalAmount":"1 350 000",
                },
                {
                    "id":'3',
                    "importer": "CHIF 'Jitong' ",
                    "cargo":"Чой в меш ",
                    "contCar":"234 95 084",
                    "allTn":"26,73",
                    "acceptance":"20.02.19",
                    "pallet":"21т",
                    "rate":"3000",
                    "storagePeriodC":"28.08.19",
                    "storagePeriodUntil":"14.09.19",
                    "status":"active",
                    "numberStorage":"18",
                    "totalAmount":"1 350 000",
                },
                {
                    "id":'4',
                    "importer": "CHIF 'Jitong' ",
                    "cargo":"Напитки",
                    "contCar":"234 95 084",
                    "allTn":"26,73",
                    "acceptance":"20.02.19",
                    "pallet":"27т",
                    "rate":"3000",
                    "storagePeriodC":"28.08.19",
                    "storagePeriodUntil":"14.09.19",
                    "status":"active",
                    "numberStorage":"18",
                    "totalAmount":"1 350 000",
                }
            ],
            columns : {
                id : {
                    show: true,
                    title: '№',
                    sortable: true,
                    column: 'id'
                },
                importer : {
                    show: true,
                    title: 'Импортер',
                    sortable: true,
                    column: 'importer'
                },
                cargo : {
                    show: true,
                    title: 'Груз крыт Таможенный Склад',
                    sortable: true,
                    column: 'cargo'
                },
                contCar : {
                    show: true,
                    title: '№ конт/вагон/авто ',
                    sortable: true,
                    column: 'contCar'
                },
                allTn : {
                    show: true,
                    title: 'Весь (тн)',
                    sortable: true,
                    column: 'allTn'
                },
                acceptance : {
                    show: true,
                    title: 'Приемка ',
                    sortable: true,
                    column: 'acceptance'
                },
                pallet : {
                    show: true,
                    title: 'Паллет',
                    sortable: true,
                    column: 'pallet'
                },
                rate : {
                    show: true,
                    title: 'Тариф',
                    sortable: true,
                    column: 'rate'
                },
                storagePeriodC : {
                    show: true,
                    title: 'Период  Хранения С',
                    sortable: true,
                    column: 'storagePeriodC'
                },
                storagePeriodUntil : {
                    show: true,
                    title: 'Период  Хранения До',
                    sortable: true,
                    column: 'storagePeriodUntil'
                },
                status : {
                    show: true,
                    title: 'Статус',
                    sortable: true,
                    column: 'status'
                },
                 numberStorage : {
                    show: true,
                    title: 'Кол-во  дней  на  хранении ',
                    sortable: true,
                    column: 'numberStorage'
                },
                totalAmount : {
                    show: true,
                    title: 'Общая сумма',
                    sortable: true,
                    column: 'totalAmount'
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
