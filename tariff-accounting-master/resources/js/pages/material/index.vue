<template>
<div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3 d-inline">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.materials")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search"
                    v-model="filterForm.search" clearable></el-input>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <el-button v-can="'materials.create'" size="mini" @click="drawerCreate = true"
                    icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel v-can="'materials.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.materials')" :name="$t('message.materials')">
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
    <table class="table table-bordered table-hover" v-loading="loadingData"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.sku" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.code" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.price" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.critical_weight" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.type_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.is_active" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.measurement_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.country_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.warehouse_type_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.is_reworking" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.measurement_changeable" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.additional_measurement_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.additional_measurement_rate" :sort="sort" @c-change="updateSort"></crm-th>
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
                <th v-if="columns.sku.show">
                    <el-input size="mini" v-model="filterForm.sku" :placeholder="columns.sku.title" clearable>
                    </el-input>
                </th>
                <th v-if="columns.code.show">
                    <el-input size="mini" v-model="filterForm.code" :placeholder="columns.code.title" clearable>
                    </el-input>
                </th>
                <th v-if="columns.price.show">
                    <el-input size="mini" v-model="filterForm.price" :placeholder="columns.price.title" clearable>
                    </el-input>
                </th>
                <th v-if="columns.critical_weight.show">
                    <el-input size="mini" v-model="filterForm.critical_weight"
                        :placeholder="columns.critical_weight.title" clearable></el-input>
                </th>
                <th v-if="columns.type_id.show">
                    <el-select filterable clearable :placeholder="columns.type_id.title" size="mini"
                        v-model="filterForm.type_id">
                        <el-option v-for="item in types" :key="item.id" :label="item.name" :value="item.id"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.is_active.show">
                    <el-select filterable clearable :placeholder="columns.is_active.title" size="mini"
                        v-model="filterForm.is_active">
                        <el-option :label="$t('message.yes')" :value="1"></el-option>
                        <el-option :label="$t('message.no')" :value="0"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.measurement_id.show">
                    <measurements v-model="filterForm.measurement_id" size="mini"></measurements>
                </th>
                <th v-if="columns.country_id.show">
                    <countries v-model="filterForm.country_id" size="mini"></countries>
                </th>
                <th v-if="columns.warehouse_type_id.show">
                    <warehouse-types v-model="filterForm.warehouse_type_id" size="mini"></warehouse-types>
                </th>
                <th v-if="columns.is_reworking.show">
                    <el-select filterable clearable :placeholder="columns.is_reworking.title" size="mini"
                        v-model="filterForm.is_reworking">
                        <el-option :label="$t('message.yes')" :value="1"></el-option>
                        <el-option :label="$t('message.no')" :value="0"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.measurement_changeable.show">
                    <el-select filterable clearable :placeholder="columns.measurement_changeable.title" size="mini"
                        v-model="filterForm.measurement_changeable">
                        <el-option :label="$t('message.yes')" :value="1"></el-option>
                        <el-option :label="$t('message.no')" :value="0"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.additional_measurement_id.show">
                    <measurements v-model="filterForm.additional_measurement_id" size="mini"></measurements>
                </th>
                <th v-if="columns.additional_measurement_rate.show">
                    <el-input size="mini" v-model="filterForm.additional_measurement_rate"
                        :placeholder="columns.additional_measurement_rate.title" clearable></el-input>
                </th>
                <th v-if="columns.created_at.show">
                    <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"
                        :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.updated_at.show">
                    <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini"
                        :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.settings.show"></th>
            </tr>
        </thead>

        <transition-group name="flip-list" tag="tbody">
            <tr v-for="material in list" :key="material.id" class="cursor-pointer" @dblclick="edit(material)" :title="$t('message.Double tap to view')">
                <td v-if="columns.id.show"> {{ material.id }} </td>
                <td v-if="columns.name.show"> {{ material.name | truncate }} </td>
                <td v-if="columns.sku.show"> {{ material.sku }} </td>
                <td v-if="columns.code.show"> {{ material.code }} </td>
                <td v-if="columns.price.show"> {{ material.price | formatNumber }} {{ material.price_currency ? material.price_currency.symbol : ''}}</td>
                <td v-if="columns.critical_weight.show"> {{ material.critical_weight }} </td>
                <td v-if="columns.type_id.show">
                    {{ types.hasOwnProperty(material.type_id) ? types[material.type_id].name : '' }} </td>
                <td v-if="columns.is_active.show"> {{ material.is_active }} </td>
                <td v-if="columns.measurement_id.show"> {{ (material.measurement) ? material.measurement.name : '' }}
                </td>
                <td v-if="columns.country_id.show"> {{ (material.country) ? material.country.name : '' }}</td>
                <td v-if="columns.warehouse_type_id.show">
                    {{ (material.warehouse_type) ? material.warehouse_type.name : '' }}</td>
                <td v-if="columns.is_reworking.show">{{ material.is_reworking }}</td>
                <td v-if="columns.measurement_changeable.show">{{ material.measurement_changeable }}</td>
                <td v-if="columns.additional_measurement_id.show">
                    {{ (material.additional_measurement) ? material.additional_measurement.name : '' }}</td>
                <td v-if="columns.additional_measurement_rate.show">{{ material.additional_measurement_rate }}</td>
                <td v-if="columns.created_at.show">{{ material.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ material.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="material" name="materials" :actions="actions" @edit="edit"
                        @copy="copy" @delete="destroy">
                    </crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="80%" ref="drawerCreate"
        @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
        <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate"
        @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :open="drawerUpdate" :material="selectedItem" ref="drawerUpdateChild">
        </crm-update>
    </el-drawer>
</div>
</template>
<script>
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import measurements from '@inventory/crm-measurement-select';
    import countries from '@inventory/crm-country-select';
    import warehouseTypes from '@inventory/crm-warehouse-type-select';
    import list from '@/utils/mixins/list'
    import { mapGetters, mapActions } from "vuex";

    export default {
        mixins: [list],
        components: { CrmCreate , CrmUpdate, measurements, countries, warehouseTypes},
        mounted(){
            if (this.types && this.types.length === 0) this.loadTypes();
        },
        computed:{
            ...mapGetters({
                list: 'materials/list',
                types: 'materials/types',
                columns: "materials/columns",
                pagination: "materials/pagination",
                filter: "materials/filter",
                sort: "materials/sort",
            }),
            actions: function(){
                return ['edit','delete','copy'];
            },
        },
        methods: {
            ...mapActions({
                    updateSort: "materials/updateSort",
                    updateFilter: "materials/updateFilter",
                    updateColumn: "materials/updateColumn",
                    updateList: 'materials/index',
                    updatePagination: "materials/updatePagination",
                    copyModel: 'materials/copy',
                    delete: 'materials/destroy',
                    refreshData: 'materials/refreshData',
                    loadTypes: 'materials/getTypes',
            }),
            copy(model){
                this.copyModel({id:model.id})
                .then(res => {this.$alert(res);this.fetchData()})
                .catch(err => {this.$alert(err);})
            },
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)) {
                        let column = this.columns[key];
                        if (column.show && column.column !== "settings") {
                            switch (column.column) {
                            case "measurement_id":
                                this.excel_fields[column.title] = "measurement.name";
                                break;
                            case "additional_measurement_id":
                                this.excel_fields[column.title] = "additional_measurement.name";
                                break;
                            case "warehouse_type_id":
                                this.excel_fields[column.title] = "warehouse_type.name";
                                break;
                            default:
                                this.excel_fields[column.title] = column.column;
                                break;
                            }
                        }
                    }
                }
            }
        }
};
</script>
