<template>
    <div class="app-container">
        
        <div class="mytabel table-sm mr-0 ml-0 p-0">
            <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
                <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
                    <h5 class="d-inline mr-2 font-weight-bold">Хранение груза</h5>
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
                        <th v-if="columns.tenant.show">{{ columns.tenant.title }}</th>
                        <th v-if="columns.dogNamber.show">{{ columns.dogNamber.title }}</th>
                        <th v-if="columns.wagonNamber.show">{{ columns.wagonNamber.title }}</th>
                        <th v-if="columns.dateDelivery.show">{{ columns.dateDelivery.title }}</th>
                        <th v-if="columns.rentFrom.show">{{ columns.rentFrom.title }}</th>
                        <th v-if="columns.dateExport.show">{{ columns.dateExport.title }}</th>
                        <th v-if="columns.fopmula.show">{{ columns.fopmula.title }}</th>
                        <th v-if="columns.rate.show">{{ columns.rate.title }}</th>
                        <th v-if="columns.percen.show">{{ columns.percen.title }}</th>
                        <th v-if="columns.status.show">{{ columns.status.title }}</th>
                        <th v-if="columns.total.show">{{ columns.total.title }}</th>
                        <th v-if="columns.settings.show">{{ columns.settings.title }}</th>
                    </tr>

                    <tr>
                        <th v-if="columns.id.show">
                            <el-input clearable size="mini" v-model="filterForm.id" class="id_input"
                                :placeholder="columns.id.title"></el-input>
                        </th>
                        <th v-if="columns.tenant.show">
                            <el-input clearable size="mini" v-model="filterForm.tenant" :placeholder="columns.tenant.title"></el-input>
                        </th>
                        <th v-if="columns.dogNamber.show">
                            <el-input clearable size="mini" v-model="filterForm.dogNamber" :placeholder="columns.dogNamber.title">
                            </el-input>
                        </th>
                        <th v-if="columns.wagonNamber.show">
                            <el-input clearable size="mini" v-model="filterForm.wagonNamber" :placeholder="columns.wagonNamber.title">
                            </el-input>
                        </th>
                        <th v-if="columns.dateDelivery.show">
                            <el-date-picker :placeholder="columns.dateDelivery.title" v-model="filterForm.dateDelivery" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.rentFrom.show">
                            <el-date-picker :placeholder="columns.rentFrom.title" v-model="filterForm.rentFrom" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.dateExport.show">
                            <el-date-picker :placeholder="columns.dateExport.title" v-model="filterForm.dateExport" size="mini">
                            </el-date-picker>
                        </th>
                        <th v-if="columns.fopmula.show">
                            <el-input clearable size="mini" v-model="filterForm.fopmula" :placeholder="columns.fopmula.title">
                            </el-input>
                        </th>
                         <th v-if="columns.rate.show">
                             <el-input clearable size="mini" v-model="filterForm.rate" :placeholder="columns.rate.title">
                            </el-input>
                        </th>
                        <th v-if="columns.percen.show">
                            <el-input clearable size="mini" v-model="filterForm.percen" :placeholder="columns.percen.title">
                            </el-input>
                        </th>
                        <th v-if="columns.status.show">
                            <el-select v-model="filterForm.status" filterable clearable :placeholder="columns.status.title"
                                size="mini">
                                <el-option label="Zone one" value="shanghai"></el-option>
                                <el-option label="Zone two" value="beijing"></el-option>
                            </el-select>
                        </th>
                        <th v-if="columns.total.show">
                            <el-input clearable size="mini" v-model="filterForm.total" :placeholder="columns.total.title">
                            </el-input>
                        </th>
                        <th v-if="columns.settings.show"></th>
                    </tr>

                </thead>

                <transition-group name="flip-list" tag="tbody">
                    <tr v-for="user in list" :key="user.id" class="cursor-pointer">

                        <td v-if="columns.id.show">{{ user.id }}</td>
                        <td v-if="columns.tenant.show">{{ user.tenant }}</td>
                        <td v-if="columns.dogNamber.show">{{ user.dogNamber }}</td>
                        <td v-if="columns.wagonNamber.show">{{ user.wagonNamber }}</td>
                        <td v-if="columns.dateDelivery.show">{{ user.dateDelivery }}</td>
                        <td v-if="columns.rentFrom.show">{{ user.rentFrom }}</td>
                        <td v-if="columns.dateExport.show">{{ user.dateExport }}</td>
                        <td v-if="columns.fopmula.show">{{ user.fopmula }}</td>
                        <td v-if="columns.rate.show">{{ user.rate }}</td>
                        <td v-if="columns.percen.show">{{ user.percen }}</td>
                        <td v-if="columns.status.show">{{ (user.status === 'active') ? 'Активний' : 'Не астивний' }}</td>
                        <td v-if="columns.total.show">{{ user.total }}</td>
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
                tenant:'',
                dogNamber: '',
                wagonNamber: '',
                dateDelivery: '',
                rentFrom: '',
                dateExport: '',               
                fopmula: '',               
                rate: '',
                percen:'',     
                status: '',  
                total:'',     
            },
        list: [
                {
                    "id":'1',
                    "tenant": "OOO Asnovi Biznes",
                    "dogNamber":"016Y/19",
                    "wagonNamber":"22.2.19",
                    "dateDelivery":"01.02.2019",
                    "rentFrom":"03.02.19",
                    "dateExport":"02.03.2019",
                    "fopmula":"-43 786",
                    "rate":"168 000",
                    "percen":"2 530",
                    "status":"active",
                    "total":"-845 600106",
                },
                 {
                    "id":'2',
                    "tenant": "OOO Asnovi Biznes",
                    "dogNamber":"016Y/19",
                    "wagonNamber":"22.2.19",
                    "dateDelivery":"01.02.2019",
                    "rentFrom":"03.02.19",
                    "dateExport":"02.03.2019",
                    "fopmula":"-43 786",
                    "rate":"168 000",
                    "percen":"2 530",
                    "status":"active",
                    "total":"-845 600106",
                },
                 {
                    "id":'3',
                    "tenant": "OOO Asnovi Biznes",
                    "dogNamber":"016Y/19",
                    "wagonNamber":"22.2.19",
                    "dateDelivery":"01.02.2019",
                    "rentFrom":"03.02.19",
                    "dateExport":"02.03.2019",
                    "fopmula":"-43 786",
                    "rate":"168 000",
                    "percen":"2 530",
                    "status":"active",
                    "total":"-845 600106",
                },
                 {
                    "id":'4',
                    "tenant": "OOO Asnovi Biznes",
                    "dogNamber":"016Y/19",
                    "wagonNamber":"22.2.19",
                    "dateDelivery":"01.02.2019",
                    "rentFrom":"03.02.19",
                    "dateExport":"02.03.2019",
                    "fopmula":"-43 786",
                    "rate":"168 000",
                    "percen":"2 530",
                    "status":"active",
                    "total":"-845 600106",
                },
                 {
                    "id":'5',
                    "tenant": "OOO Asnovi Biznes",
                    "dogNamber":"016Y/19",
                    "wagonNamber":"22.2.19",
                    "dateDelivery":"01.02.2019",
                    "rentFrom":"03.02.19",
                    "dateExport":"02.03.2019",
                    "fopmula":"-43 786",
                    "rate":"168 000",
                    "percen":"2 530",
                    "status":"active",
                    "total":"-845 600106",
                },
                
            ],
            columns : {
                id : {
                    show: true,
                    title: '№',
                    sortable: true,
                    column: 'id'
                },
                tenant : {
                    show: true,
                    title: 'Арендатор',
                    sortable: true,
                    column: 'tenant'
                },
                dogNamber : {
                    show: true,
                    title: 'Дог/№',
                    sortable: true,
                    column: 'dogNamber'
                },
                wagonNamber : {
                    show: true,
                    title: '№/вагона',
                    sortable: true,
                    column: 'wagonNamber'
                },
                dateDelivery : {
                    show: true,
                    title: 'Дата Завоза',
                    sortable: true,
                    column: 'dateDelivery'
                },
                rentFrom : {
                    show: true,
                    title: 'Аренда с',
                    sortable: true,
                    column: 'rentFrom'
                },
                dateExport : {
                    show: true,
                    title: 'Дата Вывоза',
                    sortable: true,
                    column: 'dateExport'
                },
                fopmula : {
                    show: true,
                    title: 'Фопмула',
                    sortable: true,
                    column: 'fopmula'
                },
                rate : {
                    show: true,
                    title: 'Ставка',
                    sortable: true,
                    column: 'rate'
                },
                percen : {
                    show: true,
                    title: '20%',
                    sortable: true,
                    column: 'percen'
                },
                status : {
                    show: true,
                    title: 'Статус',
                    sortable: true,
                    column: 'status'
                },
                total : {
                    show: true,
                    title: 'Итого',
                    sortable: true,
                    column: 'total'
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
