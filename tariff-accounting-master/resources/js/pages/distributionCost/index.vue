<template  ref="SalesList">
<div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:250px">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Distribution of costs") }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
            </div>
            <div class="crm-content-header-left-item">
                <el-date-picker v-model="filterForm.from_date" type="date" :format="date_format" :value-format="date_format" size="mini" :placeholder="$t('message.from')">
                </el-date-picker>
            </div>
            <div class="crm-content-header-left-item">
                <el-date-picker v-model="filterForm.to_date" type="date" :format="date_format" :value-format="date_format" size="mini" :placeholder="$t('message.to')">
                </el-date-picker>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <el-button v-can="'distribution_costs.create'" size="mini" @click="drawerDistributionWarehouseProductCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.warehouse_products') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <el-button v-can="'distribution_costs.create'" size="mini" @click="drawerDistributionWarehouseMaterialCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.warehouse_materials') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <crm-edit-dropdown v-can="'distribution_costs.delete'" :items="selectedItems" @delete="multipleDelete"></crm-edit-dropdown>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel v-can="'distribution_costs.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.Distribution of costs')" :name="$t('message.Distribution of costs')">
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
    <table class="table table-bordered table-hover" v-loading="loadingData"	:element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <th></th>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.datetime" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.type" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.from_date" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.to_date" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.user_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" v-model="checkAll" id="checkAll" @change="handleCheckAllChange" />
                        <label class="custom-control-label cursor-pointer" for="checkAll"></label>
                    </div>
                </th>
                <th v-if="columns.id.show">
                    <el-input clearable size="mini" class="id_input" v-model="filterForm.id" :placeholder="columns.id.title"></el-input>
                </th>
                <th v-if="columns.datetime.show">
                    <el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.type.show">
                    <el-select v-model="filterForm.type" clearable :placeholder="columns.type.title" size="mini">
                        <el-option :label="$t('message.materials')" value="materials"></el-option>
                        <el-option :label="$t('message.products')" value="products"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.from_date.show">
                    <el-date-picker v-model="filterForm.from_date_attr" :placeholder="columns.from_date.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.to_date.show">
                    <el-date-picker v-model="filterForm.to_date_attr" :placeholder="columns.to_date.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.user_id.show">
                    <users v-model="filterForm.user_id" size="mini"></users>
                </th>
                <th v-if="columns.created_at.show">
                    <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini" :value-format="date_format">
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
            <tr v-for="distribution_cost in list" :key="'distribution_costs-'+distribution_cost.id" class="cursor-pointer" :class="{'table-active': (selectedItems.indexOf(distribution_cost) > -1)}" @dblclick="edit(distribution_cost)" :title="$t('message.Double tap to view')">
                <td>
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="distribution_cost" :id="'customCheck'+distribution_cost.id" @change="handleCheckedItemsChange" />
                        <label class="custom-control-label cursor-pointer" :for="'customCheck'+distribution_cost.id"></label>
                    </div>
                </td>
                <td v-if="columns.id.show">{{ distribution_cost.id }}</td>
                <td v-if="columns.datetime.show">{{ distribution_cost.datetime }}</td>
                <td v-if="columns.type.show">{{ (distribution_cost.type) ? $t('message.' + distribution_cost.type) : '' }}</td>
                <td v-if="columns.from_date.show"> {{ distribution_cost.from_date }} </td>
                <td v-if="columns.to_date.show"> {{ distribution_cost.to_date }} </td>
                <td v-if="columns.user_id.show"> {{ distribution_cost.user ? distribution_cost.user.name : '' }}</td>
                <td v-if="columns.created_at.show">{{ distribution_cost.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ distribution_cost.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown name="distribution_costs" :model="distribution_cost" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerDistributionWarehouseProductCreate" size="95%" ref="drawerDistributionWarehouseProductCreate" @closed="drawerClosed('drawerDistributionWarehouseProductCreateChild')"  @opened="drawerOpened('drawerDistributionWarehouseProductCreateChild')">
        <warehouse-product drawer="drawerDistributionWarehouseProductCreate" ref="drawerDistributionWarehouseProductCreateChild"></warehouse-product>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerDistributionWarehouseProductUpdate" size="95%" ref="drawerDistributionWarehouseProductUpdate" @closed="drawerClosed('drawerDistributionWarehouseProductUpdateChild')"  @opened="drawerOpened('drawerDistributionWarehouseProductUpdateChild')">
        <warehouse-product-update drawer="drawerDistributionWarehouseProductUpdate" :distribution_cost="selectedItem" ref="drawerDistributionWarehouseProductUpdateChild"></warehouse-product-update>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerDistributionWarehouseMaterialCreate" size="95%" ref="drawerDistributionWarehouseMaterialCreate" @closed="drawerClosed('drawerDistributionWarehouseMaterialCreateChild')"  @opened="drawerOpened('drawerDistributionWarehouseMaterialCreateChild')">
        <warehouse-material drawer="drawerDistributionWarehouseMaterialCreate" ref="drawerDistributionWarehouseMaterialCreateChild"></warehouse-material>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerDistributionWarehouseMaterialUpdate" size="95%" ref="drawerDistributionWarehouseMaterialUpdate" @closed="drawerClosed('drawerDistributionWarehouseMaterialUpdateChild')"  @opened="drawerOpened('drawerDistributionWarehouseMaterialUpdateChild')">
        <warehouse-material-update drawer="drawerDistributionWarehouseMaterialUpdate" :distribution_cost="selectedItem" ref="drawerDistributionWarehouseMaterialUpdateChild"></warehouse-material-update>
    </el-drawer>
</div>
</template>
<script>

    import WarehouseProduct from "./components/crm-create-warehouse-product";
    import WarehouseProductUpdate from "./components/crm-update-warehouse-product";
    import WarehouseMaterial from "./components/crm-create-warehouse-material";
    import WarehouseMaterialUpdate from "./components/crm-update-warehouse-material";
    import users from '@inventory/crm-user-select';

    import { mapActions, mapGetters } from "vuex";
    import list from '@/utils/mixins/list';

    export default {
        mixins: [list],
        data() {
            return {
                drawerDistributionWarehouseProductCreate: false,
                drawerDistributionWarehouseProductUpdate: false,
                drawerDistributionWarehouseMaterialCreate: false,
                drawerDistributionWarehouseMaterialUpdate: false,
            }
        },
        components: {WarehouseProduct, WarehouseProductUpdate, WarehouseMaterial, WarehouseMaterialUpdate, users},
        computed: {
            ...mapGetters({
                list: 'distributionCosts/list',
                columns: 'distributionCosts/columns',
                pagination: 'distributionCosts/pagination',
                filter: 'distributionCosts/filter',
                sort: 'distributionCosts/sort',
            }),
            actions: function() {
                return ['edit', 'delete'];
            }
        },
        watch: {
            list: {
                handler: function() {
                    this.checkAll = false;
                    this.selectedItems = [];
                },
            deep: true
            }
        },
        methods: {
            ...mapActions({
                    updateSort: 'distributionCosts/updateSort',
                    updateFilter: 'distributionCosts/updateFilter',
                    updateColumn: 'distributionCosts/updateColumn',
                    updateList: 'distributionCosts/index',
                    updatePagination: 'distributionCosts/updatePagination',
                    empty: 'distributionCosts/empty',
                    delete: 'distributionCosts/destroy',
                    refreshData: 'distributionCosts/refreshData',
                    multiDelete: 'distributionCosts/multiDelete',
            }),
            edit(model) {
                this.selectedItem = model;
                if (model.type == 'products') {
                    this.drawerDistributionWarehouseProductUpdate = true;
                }
                else if(model.type == 'materials'){
                    this.drawerDistributionWarehouseMaterialUpdate = true;
                }
            },
            controlExcelData() {
                this.excel_fields = {};
                for (let key in this.columns) {
                    if (this.columns.hasOwnProperty(key)) {
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings') {
                            switch (column.column) {
                            case "type":
                                this.excel_fields[column.title] = {
                                        field: 'type',
                                        callback: (value) => {
                                            return this.$t('message.' + value);
                                        }
                                    };
                                break;
                            case 'user_id':
                                this.excel_fields[column.title] = 'user.name';
                                break;
                            default:
                                this.excel_fields[column.title] = column.column;
                                break;
                            }
                        }
                    }
                }
            },
            multipleDelete(items) {
                this.multiDelete({
                    items: items.map(item => {
                        return item.id;
                    })
                })
                .then(res => {
                    this.$alert(res);
                    this.fetchData();
                })
                .catch(err => {
                    this.$alert(err);
                });
            },
            handleCheckAllChange() {
                this.selectedItems = this.checkAll ? (this.selectedItems = this.list) : [];
                this.handleCheckedItemsChange();
            },
            handleCheckedItemsChange() {
                this.checkAll = this.selectedItems.length === this.list.length;
            }
        }
    };
</script>
