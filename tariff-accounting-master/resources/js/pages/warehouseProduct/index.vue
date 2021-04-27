<template>
 <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:200px">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.warehouse")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
             <div class="crm-content-header-right-item">
                <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.warehouse')" :name="$t('message.warehouse')">
                    <el-button size="mini">
                        <i class="el-icon-document-delete el-icon--left"></i> {{ $t('message.download_excel') }}
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
                <crm-th :sort="sort" :column="columns.vendor_code" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.remainder" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.booked" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.measurement_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.cost_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.total_cost_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.selling_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.total_selling_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>
            <tr>
                <th v-if="columns.id.show" width="40px">
                </th>
                <th v-if="columns.name.show">
                    <products size="mini" v-model="filterForm.id"></products>
                </th>
                <th v-if="columns.code.show">
                    <el-input clearable size="mini" v-model="filterForm.code" :placeholder="columns.code.title">
                    </el-input>
                </th>
                <th v-if="columns.vendor_code.show">
                    <el-input clearable size="mini" v-model="filterForm.vendor_code"
                        :placeholder="columns.vendor_code.title"></el-input>
                </th>
                <th v-if="columns.remainder.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.booked.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.measurement_id.show">
                    <measurements size="mini" v-model="filterForm.measurement_id"></measurements>
                </th>
                <th v-if="columns.cost_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.total_cost_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.selling_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.total_selling_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.settings.show"></th>
            </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
            <tr v-for="product in list" :key="'products_'+product.id" class="cursor-pointer" data-toggle="tooltip" data-placement="top" :title="$t('message.two_click_for_view_details')" @dblclick="show(product)">
                <td v-if="columns.id.show">{{ product.id }}</td>
                <td v-if="columns.name.show">{{ product.name }}</td>
                <td v-if="columns.code.show">{{ product.code }}</td>
                <td v-if="columns.vendor_code.show">{{ product.vendor_code }}</td>
                <td v-if="columns.remainder.show">{{ product.remainder | formatNumber }}</td>
                <td v-if="columns.booked.show">{{ product.booked | formatNumber }}</td>
                <td v-if="columns.measurement_id.show">{{ (product.measurement) ? product.measurement.name : '' }}</td>
                <td v-if="columns.cost_price.show">{{ product.cost_price | formatMoney(2) }}</td>
                <td v-if="columns.total_cost_price.show">{{ product.total_cost_price | formatMoney(4) }}</td>
                <td v-if="columns.selling_price.show">{{ product.selling_price | formatMoney(2) }}</td>
                <td v-if="columns.total_selling_price.show">{{ product.total_selling_price | formatMoney(4) }}</td>
                <td>
                    <el-button size="mini" type="success" @click="show(product)" icon="el-icon-notebook-2 el-icon-left">{{ $t('message.details')}} </el-button>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerComingHistory" size="90%" ref="drawerComingHistory" @closed="drawerClosed('drawerComingHistoryChild')" @opened="drawerOpened('drawerComingHistoryChild')">
        <crm-coming :open="drawerComingHistory" drawer="drawerComingHistory" :product="selectedItem" :warehouse_id="warehouse_id" ref="drawerComingHistoryChild"></crm-coming>
    </el-drawer>
</div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmComing from "./components/crm-coming";
    import list from '@/utils/mixins/list';
    import products from '@inventory/crm-product-select';
    import measurements from '@inventory/crm-measurement-select';

    export default {
        mixins: [list],
        components: { CrmComing, 'products': products, 'measurements': measurements },
        name: "WarehouseProductList",
        data() {
            return {
                drawerComingHistory: false,
                reopenComing: false,
            };
        },
        computed:{
            ...mapGetters({
                list: 'warehouseProducts/list',
                columns: "warehouseProducts/columns",
                pagination: "warehouseProducts/pagination",
                filter: "warehouseProducts/filter",
                sort: "warehouseProducts/sort",
            }),
            warehouse_id: function() {
                return this.$route.params.warehouse_id
            }
        },
        methods: {
            ...mapActions({
                updateList: 'warehouseProducts/index',
                updateSort: "warehouseProducts/updateSort",
                updateFilter: "warehouseProducts/updateFilter",
                updateColumn: "warehouseProducts/updateColumn",
                updatePagination: "warehouseProducts/updatePagination",
                refreshData: 'warehouseProducts/refreshData',
            }),
            fetchData() {
                const add_filter = {warehouse_id: this.warehouse_id};
                const query = { ...this.filter, ...this.pagination, ...this.sort, ...add_filter};
                if (!this.loadingData) {
                    this.loadingData = true;
                    this.updateList(query)
                    .then(res => {
                        this.loadingData = false;
                    })
                    .catch(err => {
                        this.loadingData = false;
                    });
                }
            },
            show(model){
                this.selectedItem = model;
                this.drawerComingHistory = true;
            },
            controlExcelData() {
                this.excel_fields = {};
                for (let key in this.columns) {
                    if (this.columns.hasOwnProperty(key)) {
                        let column = this.columns[key];
                        if (column.show && column.column !== "settings") {
                            switch (column.column) {
                                case "measurement_id":
                                    this.excel_fields[column.title] = "measurement.name";
                                    break;
                                default:
                                    this.excel_fields[column.title] = column.column;
                                    break;
                            }
                        }
                    }
                }
            },
        }
};
</script>
