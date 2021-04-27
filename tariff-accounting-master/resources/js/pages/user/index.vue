<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3 d-inline">
                        <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.users")  }}</h5>
                        <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                    <div class="crm-content-header-left-item">
                        <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search"
                        clearable></el-input>
                    </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                    <div class="crm-content-header-right-item">
                        <el-button v-can="'users.create'" type="primary" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                    </div>
                    <div class="crm-content-header-right-item">
                    <export-excel  v-can="'users.excel'" class="btn excel_btn border-0" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                            :worksheet="$t('message.users') " :name="$t('message.users')+'.xls'">
                            <el-button size="mini" icon="el-icon-document-delete"> {{ $t('message.download_excel') }} </el-button>
                        </export-excel>
                    </div>
                    <div class="crm-content-header-right-item">
                    <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
                    </div>
            </div>
        </div>
        <table class="table table-bordered table-hover" v-loading="loadingData"
                :element-loading-text="$t('message.loading')"
                element-loading-spinner="el-icon-loading">
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
                <tr>
                    <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.phone" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.role_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.status" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.first_name" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.surname" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.patronymic" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.email" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.last_login" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.is_employee" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.pin_code" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
                </tr>
                <tr>
                    <th v-if="columns.id.show">
                        <el-input clearable size="mini" v-model="filterForm.id" class="id_input"
                            :placeholder="columns.id.title"></el-input>
                    </th>
                    <th v-if="columns.name.show">
                        <el-input clearable size="mini" v-model="filterForm.name" :placeholder="columns.name.title">
                        </el-input>
                    </th>
                    <th v-if="columns.created_at.show">
                        <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"  :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.updated_at.show">
                        <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.phone.show">
                        <el-input clearable size="mini" v-model="filterForm.phone" :placeholder="columns.phone.title">
                        </el-input>
                    </th>
                    <th v-if="columns.role_id.show">
                        <roles v-model="filterForm.role_id"></roles>
                    </th>
                    <th v-if="columns.status.show">
                        <el-select v-model="filterForm.status" filterable clearable :placeholder="columns.status.title" size="mini">
                            <el-option v-for="(value,index) in statues" :key="index" :label="value" :value="index"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.first_name.show">
                        <el-input v-model="filterForm.first_name" clearable :placeholder="columns.first_name.title" size="mini"></el-input>
                    </th>
                    <th v-if="columns.surname.show">
                        <el-input v-model="filterForm.surname" clearable :placeholder="columns.surname.title" size="mini">
                        </el-input>
                    </th>
                    <th v-if="columns.patronymic.show">
                        <el-input v-model="filterForm.patronymic" clearable :placeholder="columns.patronymic.title"
                            size="mini"></el-input>
                    </th>
                    <th v-if="columns.email.show">
                        <el-input v-model="filterForm.email" clearable :placeholder="columns.email.title" size="mini">
                        </el-input>
                    </th>
                    <th v-if="columns.last_login.show">
                        <el-input v-model="filterForm.last_login" clearable :placeholder="columns.last_login.title"
                            size="mini"></el-input>
                    </th>
                    <th v-if="columns.is_employee.show">
                        <el-select filterable clearable :placeholder="columns.is_employee.title" size="mini"
                            v-model="filterForm.is_employee">
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.pin_code.show">
                        <el-input clearable size="mini" v-model="filterForm.pin_code" :placeholder="columns.pin_code.title">
                        </el-input>
                    </th>
                    <th v-if="columns.settings.show"></th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="user in list" :key="user.id" class="cursor-pointer">
                    <td v-if="columns.id.show">{{ user.id }}</td>
                    <td v-if="columns.name.show">{{ user.name | truncate }}</td>
                    <td v-if="columns.created_at.show">{{ user.created_at }}</td>
                    <td v-if="columns.updated_at.show">{{ user.updated_at }}</td>
                    <td v-if="columns.phone.show">{{ user.phone }}</td>
                    <td v-if="columns.role_id.show">{{ (user.role) ? user.role.name : '' }}</td>
                    <td v-if="columns.status.show">{{ user.status }}</td>
                    <td v-if="columns.first_name.show">{{ user.first_name }}</td>
                    <td v-if="columns.surname.show">{{ user.surname }}</td>
                    <td v-if="columns.patronymic.show">{{ user.patronymic }}</td>
                    <td v-if="columns.email.show">{{ user.email }}</td>
                    <td v-if="columns.last_login.show">{{ user.last_login }}</td>
                    <td v-if="columns.is_employee.show">
                        <template v-if="user.is_employee">{{ $t('message.yes') }}</template>
                        <template v-else>{{ $t('message.no') }}</template>
                    </td>
                    <td v-if="columns.pin_code.show">{{ user.pin_code }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown name="users" :model="user" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :user="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>
<script>
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";
    import roles from "@inventory/crm-role-select";
    import { mapGetters, mapActions, mapState } from "vuex";

    export default {
        mixins: [list],
        components:{ CrmUpdate, CrmCreate, roles},
        computed: {
            ...mapGetters({
                list: 'users/list',
                columns: 'users/columns',
                pagination: 'users/pagination',
                statues: 'users/statues',
                filter: "users/filter",
                sort: 'users/sort',
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: "users/index",
                setPagination: "users/setPagination",
                updateSort: "users/updateSort",
                updateFilter: "users/updateFilter",
                updateColumn: "users/updateColumn",
                updatePagination: "sales/updatePagination",
                empty: 'users/empty',
                delete: 'users/destroy',
                refreshData: 'users/refreshData',
            }),
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
                            switch (column.column) {
                                case 'role_id':
                                    this.excel_fields[column.title] = 'role.name'; break;
                                case 'phone':
                                    this.excel_fields[column.title] = {
                                        field: 'phone',
                                        callback: (value) => {
                                            return `Тел: ${value}`
                                        }
                                    }; break;
                                case 'is_employee':
                                    this.excel_fields[column.title] = {
                                        field: 'is_employee',
                                        callback: (value) => {
                                            return (value === 1) ? this.$t('message.yes') : this.$t('message.no');
                                        }
                                    }; break;
                                default :
                                    this.excel_fields[column.title] = column.column; break;
                            }
                        }
                    }
                }
            },
        }
    };
</script>
