<template>
<div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3 d-inline">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.products")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search"
                    v-model="filterForm.search" clearable></el-input>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <el-button v-can="'products.create'" size="mini" @click="drawerCreate = true"
                    icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel v-can="'products.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.products')" :name="$t('message.products')">
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
                <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.name" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.code" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.measurement_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.purchase_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.selling_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.vendor_code" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.recycled" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.country_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.warehouse_type_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.production" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.production_type" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.nds" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.minimum_balance" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.description" @c-change="updateSort"></crm-th>
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
                    <el-input clearable size="mini" v-model="filterForm.name" :placeholder="columns.name.title">
                    </el-input>
                </th>
                <th v-if="columns.code.show">
                    <el-input clearable size="mini" v-model="filterForm.code" :placeholder="columns.code.title">
                    </el-input>
                </th>
                <th v-if="columns.measurement_id.show">
                    <measurements v-model="filterForm.measurement_id" size="mini"></measurements>
                </th>
                <th v-if="columns.purchase_price.show">
                    <el-input clearable size="mini" v-model="filterForm.purchase_price"
                        :placeholder="columns.purchase_price.title"></el-input>
                </th>
                <th v-if="columns.selling_price.show">
                    <el-input clearable size="mini" v-model="filterForm.selling_price"
                        :placeholder="columns.selling_price.title"></el-input>
                </th>
                <th v-if="columns.vendor_code.show">
                    <el-input clearable size="mini" v-model="filterForm.vendor_code"
                        :placeholder="columns.vendor_code.title"></el-input>
                </th>
                <th v-if="columns.recycled.show">
                    <el-select filterable clearable :placeholder="columns.recycled.title" size="mini"
                        v-model="filterForm.recycled">
                        <el-option :label="$t('message.recyled')" :value="true"></el-option>
                        <el-option :label="$t('message.ne_recyled')" :value="false"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.country_id.show">
                    <countries v-model="filterForm.country_id" size="mini"></countries>
                </th>
                <th v-if="columns.warehouse_type_id.show">
                    <warehouse-types v-model="filterForm.warehouse_type_id" size="mini"></warehouse-types>
                </th>
                <th v-if="columns.production.show">
                    <el-select filterable clearable :placeholder="columns.production.title" size="mini"
                        v-model="filterForm.production">
                        <el-option :label="$t('message.yes')" :value="1"></el-option>
                        <el-option :label="$t('message.no')" :value="0"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.production_type.show">
                    <el-select filterable clearable :placeholder="columns.production_type.title" size="mini"
                        v-model="filterForm.production_type">
                        <el-option :label="$t('message.semi_product')" :value="'production'"></el-option>
                        <el-option :label="$t('message.ready_product')" :value="'assembly'"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.nds.show">
                    <el-input clearable size="mini" v-model="filterForm.nds" :placeholder="columns.nds.title">
                    </el-input>
                </th>
                <th v-if="columns.minimum_balance.show">
                    <el-input clearable size="mini" v-model="filterForm.minimum_balance"
                        :placeholder="columns.minimum_balance.title"></el-input>
                </th>
                <th v-if="columns.description.show">
                    <el-input clearable size="mini" v-model="filterForm.description"
                        :placeholder="columns.description.title"></el-input>
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
            <tr v-for="product in list" :key="product.id" class="cursor-pointer" @dblclick="edit(product)" :title="$t('message.Double tap to view')">
                <td v-if="columns.id.show"> {{ product.id }}</td>
                <td v-if="columns.name.show"> {{ product.name| truncate }}</td>
                <td v-if="columns.code.show"> {{ product.code }}</td>
                <td v-if="columns.measurement_id.show"> {{ (product.measurement) ? product.measurement.name : '' }}</td>
                <td v-if="columns.purchase_price.show"> {{ product.purchase_price | formatNumber }} {{ product.purchase_currency ? product.purchase_currency.symbol : ''}}</td>
                <td v-if="columns.selling_price.show"> {{ product.selling_price | formatNumber }} {{ product.selling_currency ? product.selling_currency.symbol : ''}}</td>
                <td v-if="columns.vendor_code.show"> {{ product.vendor_code }}</td>
                <td v-if="columns.recycled.show"> {{ product.recycled }}</td>
                <td v-if="columns.country_id.show"> {{ (product.country) ? product.country.name : '' }}</td>
                <td v-if="columns.warehouse_type_id.show">
                    {{ (product.warehouse_type) ? product.warehouse_type.name : '' }}</td>
                <td v-if="columns.production.show"> {{ product.production }}</td>
                <td v-if="columns.production_type.show"> {{ product.production_type }}</td>
                <td v-if="columns.nds.show">{{ product.nds }}</td>
                <td v-if="columns.minimum_balance.show">{{ product.minimum_balance }}</td>
                <td v-if="columns.description.show">{{ product.description }}</td>
                <td v-if="columns.created_at.show">{{ product.created_at }}</td>
                <td v-if="columns.updated_at.show">{{ product.updated_at }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="product" name="products" :actions="actions" @edit="edit" @copy="copy"
                        @delete="destroy">
                    </crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate"
        @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
        <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate"
        @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :open="drawerUpdate" :product="selectedItem" ref="drawerUpdateChild">
        </crm-update>
    </el-drawer>
    
</div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";
    import measurements from '@inventory/crm-measurement-select';
    import countries from '@inventory/crm-country-select';
    import warehouseTypes from '@inventory/crm-warehouse-type-select';

    export default {
        mixins: [list],
        components: { CrmCreate , CrmUpdate, measurements, countries, warehouseTypes},
        computed:{
            ...mapGetters({
                list: 'products/list',
                columns: "products/columns",
                pagination: "products/pagination",
                filter: "products/filter",
                sort: "products/sort",
            }),
            actions: function() {
                return ["copy", "delete", "edit"];
            }
        },
        methods: {
            ...mapActions({
                updateList: 'products/index',
                updateSort: "products/updateSort",
                updateFilter: "products/updateFilter",
                updateColumn: "products/updateColumn",
                updatePagination: "products/updatePagination",
                copyModel: 'products/copy',
                delete: 'products/destroy',
                refreshData: 'products/refreshData',
            }),
            copy(model){
                this.copyModel({id:model.id})
                    .then(res => {this.$alert(res);this.fetchData()})
                    .catch(err => {this.$alert(err);})
            },
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
                            switch (column.column) {
                                case 'measurement_id':
                                    this.excel_fields[column.title] = 'measurement.name'; break;
                                case 'country_id':
                                    this.excel_fields[column.title] = 'country.name'; break;
                                case 'warehouse_type_id':
                                    this.excel_fields[column.title] = 'warehouse_type.name'; break;
                                default :
                                    this.excel_fields[column.title] = column.column; break;
                            }
                        }
                    }
                }
            }
        }
    };
</script>
