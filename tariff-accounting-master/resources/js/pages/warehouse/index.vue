<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:120px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.warehouses") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'warehouses.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.warehouses')" :name="$t('message.warehouses')">
                        <el-button size="mini">
                            <i class="el-icon-document-delete"></i> {{ $t('message.download_excel') }}
                        </el-button>
                    </export-excel>
                </div>
                <div class="crm-content-header-right-item">
                    <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
                </div>
            </div>
        </div>
        <table class="table table-borderd table-hover" v-loading="loadingData"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading">
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
                <tr>
                    <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.warehouse_type_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
                </tr>
                <tr>
                    <th v-if="columns.id.show">
                        <el-input clearable size="mini" class="id_input" v-model="filterForm.id"
                            :placeholder="columns.id.title"></el-input>
                    </th>
                    <th v-if="columns.name.show">
                        <el-input size="mini" v-model="filterForm.name" :placeholder="columns.name.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.warehouse_type_id.show">
                        <warehouse-types v-model="filterForm.warehouse_type_id" size="mini"></warehouse-types>
                    </th>
                    <th v-if="columns.created_at.show">
                        <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"  :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.updated_at.show">
                        <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.settings.show"></th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="warehouse in list" :key="warehouse.id">
                    <td v-if="columns.id.show">{{ warehouse.id }}</td>
                    <td v-if="columns.name.show">{{ warehouse.name | capitalize }}</td>
                    <td v-if="columns.warehouse_type_id.show">
                        {{ (warehouse.warehouse_type) ? warehouse.warehouse_type.name : '' }}</td>
                    <td v-if="columns.created_at.show">{{ warehouse.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ warehouse.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="warehouse" name="warehouses" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="60%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="60%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :warehouse="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>
<script>
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import { mapActions, mapGetters } from "vuex";
    import list from "@/utils/mixins/list";
    import warehouseTypes from '@inventory/crm-warehouse-type-select';

    export default {
        mixins: [list],
        name: "WarehouseList",
        components: { CrmCreate , CrmUpdate, warehouseTypes},
        computed: {
            ...mapGetters({
                list: "warehouses/list",
                columns: "warehouses/columns",
                pagination: "warehouses/pagination",
                filter: "warehouses/filter",
                sort: "warehouses/sort",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: "warehouses/index",
                updateSort: "warehouses/updateSort",
                updateFilter: "warehouses/updateFilter",
                updateColumn: "warehouses/updateColumn",
                updatePagination: "warehouses/updatePagination",
                empty: 'warehouses/empty',
                delete: 'warehouses/destroy',
                refreshData: 'warehouses/refreshData',
            }),
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                       if (column.show && column.column !== 'settings'){
                            switch (column.column) {
                                case 'warehouse_type_id':
                                    this.excel_fields[column.title] = 'warehouse_type.name'; break;
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
