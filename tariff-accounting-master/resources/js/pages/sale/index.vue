<template  ref="SalesList">
<div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:180px">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.sales") }}</h5>
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
                <el-button v-can="'sales.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <crm-edit-dropdown v-can="'sales.delete'" :items="selectedItems" @delete="multipleDelete">
                </crm-edit-dropdown>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel v-can="'sales.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.sales')" :name="$t('message.sales')">
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
    <div class="row table-sm ml-0 mr-0 width-100">
        <div class="col-12 p-0">
            <div class="levels-block">
                <button v-for="(item, index) in levels" :key="'abcdef'+index" @click="changeLevel(item.id)" type="button" class="btn btn-primary btn-sm" :class="{'active-level': filterForm.level_id === item.id}" :style="{'background-color': item.color}">
                    {{ item.name }}
                    <span class="badge badge-light">{{ item.sales_count }}</span>
                </button>
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
                <crm-th :column="columns.products" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.owner" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.saleable_type" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.saleable_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.begin_date" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.end_date" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.state_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.priority_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                <crm-th v-can="'sales.manufactured'" :sort="sort" :column="columns.manufactured" @c-change="updateSort"></crm-th>
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
                <th v-if="columns.products.show">
                   <el-input size="mini" disabled></el-input>
                </th>
                <th v-if="columns.owner.show">
                    <el-select filterable clearable :placeholder="columns.owner.title" size="mini" v-model="filterForm.owner">
                        <el-option :label="$t('message.firm')" value="firm"></el-option>
                        <el-option :label="$t('message.client')" value="client"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.saleable_type.show">
                    <el-select filterable clearable :placeholder="columns.saleable_type.title" size="mini" v-model="filterForm.saleable_type">
                        <el-option :label="$t('message.order')" value="orders"></el-option>
                        <el-option :label="$t('message.assembly')" value="assemblies"></el-option>
                        <el-option :label="$t('message.for_warehouse')" value="warehouse"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.saleable_id.show">
                    <el-input clearable size="mini" v-model="filterForm.saleable_id" :placeholder="columns.saleable_id.title"></el-input>
                </th>
                <th v-if="columns.begin_date.show">
                    <el-date-picker v-model="filterForm.begin_date" :placeholder="columns.begin_date.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.end_date.show">
                    <el-date-picker v-model="filterForm.end_date" :placeholder="columns.end_date.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.state_id.show">
                    <states v-model="filterForm.state_id" size="mini"></states>
                </th>
                <th v-if="columns.priority_id.show">
                    <priorities v-model="filterForm.priority_id" size="mini"></priorities>
                </th>
                <th v-if="columns.created_at.show">
                    <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.updated_at.show">
                    <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.manufactured.show" v-can="'sales.manufactured'"></th>
                <th v-if="columns.settings.show"></th>
            </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
            <tr v-for="sale in list" :key="'saleItem-'+sale.id" class="cursor-pointer" :class="{'table-active': (selectedItems.indexOf(sale) > -1), 'table-secondary font-style-italic' : sale.is_child}" @dblclick="show(sale)" :title="$t('message.Double tap to view')">
                <td>
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="sale" :id="'customCheck'+sale.id" @change="handleCheckedItemsChange" />
                        <label class="custom-control-label cursor-pointer" :for="'customCheck'+sale.id"></label>
                    </div>
                    <el-tooltip class="item" effect="dark" content="Поиск родительской продажи" placement="top-start">
                        <i v-if="sale.is_child" class="el-icon-collection-tag parent_icon" @click="filterForm.id = sale.parent_id"></i>
                    </el-tooltip>
                </td>
                <td v-if="columns.id.show">{{ sale.id }}</td>
                <td v-if="columns.datetime.show">{{ sale.datetime }}</td>
                <td v-if="columns.products.show">
                    <span @click="showItems(sale)" style="cursor: pointer; color: #3490dc;">
                        {{ sale.product_name | truncate(15) }} <span class="el-icon-arrow-right"></span>
                    </span>
                </td>
                <td v-if="columns.owner.show"> {{ sale.owner }} </td>
                <td v-if="columns.saleable_type.show"> {{ sale.saleable_type }}</td>
                <td v-if="columns.saleable_id.show">{{ sale.saleable_id }}</td>
                <td v-if="columns.begin_date.show">{{ sale.begin_date }}</td>
                <td v-if="columns.end_date.show">{{ sale.end_date }}</td>
                <td v-if="columns.state_id.show">{{ (sale.state) ? (sale.state.state) : '' }}</td>
                <td v-if="columns.priority_id.show">{{ (sale.priority) ? (sale.priority.name) : '' }}</td>
                <td v-if="columns.created_at.show">{{ sale.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ sale.updated_at | dateFormat }}</td>
                <td v-if="columns.manufactured.show" v-can="'sales.manufactured'" class="settings-td">
                    <el-button size="mini" round :type="readyBtnType(sale)"  @click="manufactured(sale)">{{ columns.manufactured.title }} ({{ Math.ceil(100 * (sale.ready_products / sale.total_products)) }}%)</el-button>
                </td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown name="sales" :model="sale" :actions="actions" @edit="edit" @delete="destroy" @show="show" @print="print" @comments="comments" @manufactured="manufactured" @defect_product="defect_product"></crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-dialog :title="$t('message.products')" :visible.sync="dialogItemsVisible" width="60%" @closed="empty()" >
        <el-table :data="items" v-loading="loadingItems">
            <el-table-column :label="$t('message.name')">
                <template slot-scope="item">
                    {{ (item.row.product ? item.row.product.name : '') | truncate}}
                </template>
            </el-table-column>
            <el-table-column :label="$t('message.quantity')">
                <template slot-scope="item">
                    {{ item.row.quantity | formatNumber }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('message.ready')">
                <template slot-scope="item">
                    {{ item.row.ready | formatNumber }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('message.defect')">
                <template slot-scope="item">
                    {{ item.row.defects_count | formatNumber }}
                </template>
            </el-table-column>
            <el-table-column :label="$t('message.measurement')">
                <template slot-scope="item">
                    {{ (item.row.product ? item.row.product.measurement ? item.row.product.measurement.name :'' : '') | truncate}}
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @closed="drawerClosed('drawerCreateChild')"  @opened="drawerOpened('drawerCreateChild')">
        <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :open="drawerUpdate" :sale="selectedItem" ref="drawerUpdateChild"></crm-update>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerShow" size="90%" ref="drawerShow" @closed="drawerClosed('drawerShowChild')" @opened="drawerOpened('drawerShowChild')">
        <crm-show drawer="drawerShow" :open="drawerShow" :model="selectedItem" ref="drawerShowChild"></crm-show>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerManufactured" size="90%" ref="drawerManufactured" @closed="drawerClosed('drawerManufacturedChild')" @opened="drawerOpened('drawerManufacturedChild')">
        <crm-manufactured drawer="drawerManufactured" :open="drawerManufactured" :sale="selectedItem" ref="drawerManufacturedChild"></crm-manufactured>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerDefectProduct" size="90%" ref="drawerDefectProduct" @closed="drawerClosed('drawerDefectProductChild')" @opened="drawerOpened('drawerDefectProductChild')">
        <crm-defect-product drawer="drawerDefectProduct" :open="drawerDefectProduct" :sale="selectedItem" ref="drawerDefectProductChild"></crm-defect-product>
    </el-drawer>
    <el-drawer :title="$t('message.comments')" :visible.sync="drawerComments" size="85%" :drawer="drawerComments">
        <crm-comments @crm-save="commentStore" :drawer="drawerComments" :model="selectedItem" :comments="commentsList" :loading="loadingComments" :title="$t('message.sales')"></crm-comments>
    </el-drawer>
</div>
</template>
<script>

    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import CrmShow from "./components/crm-show";
    import CrmComments from "@/includes/crm-comments";
    import CrmManufactured from "./components/crm-manufactured";
    import CrmDefectProduct from "./components/crm-defect-product";
    import states from '@inventory/crm-state-select';
    import priorities from '@inventory/crm-priority-select';
    import { mapActions, mapGetters } from "vuex";
    import list from '@/utils/mixins/list';

    export default {
        mixins: [list],
        components: {CrmShow,CrmComments,CrmCreate,CrmUpdate,CrmManufactured,CrmDefectProduct,CrmComments,states,priorities},
        data() {
            return {
                drawerComments: false,
                drawerBackMaterial: false,
                drawerManufactured: false,
                drawerDefectProduct: false,
                dialogItemsVisible: false,
                loadingItems: false,
            };
        },
        computed: {
            ...mapGetters({
                levels: 'levels/inventory',
                list: "sales/list",
                columns: "sales/columns",
                pagination: "sales/pagination",
                filter: "sales/filter",
                sort: "sales/sort",
                commentsList: 'sales/comments',
                items: 'sales/sale_products'
            }),
            actions: function() {
                return ["edit", "show", "delete", "print", "comments", 'manufactured', 'defect_product'];
            }
        },
        async mounted() {
            if (this.levels && this.levels.length == 0 ) this.updateLevelInventory();
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
                    updateSort: "sales/updateSort",
                    updateFilter: "sales/updateFilter",
                    updateColumn: "sales/updateColumn",
                    updateList: "sales/index",
                    updatePagination: "sales/updatePagination",
                    printModel: "sales/print",
                    empty: "sales/empty",
                    delete: "sales/destroy",
                    refreshData: "sales/refreshData",
                    multiDelete: "sales/multiDelete",
                    loadComments: 'sales/loadComments',
                    postComment: 'sales/commentStore',
                    updateLevelInventory: 'levels/inventory',
                    loadItems: 'sales/getSaleProducts'
            }),
            async show(model) {
                this.selectedItem = model;
                this.drawerShow = true;
            },
            async manufactured(model){
                this.selectedItem = model;
                this.drawerManufactured = true;
            },
            async defect_product(model){
                this.selectedItem = model;
                this.drawerDefectProduct = true;
            },
            async comments(model) {
                this.selectedItem = model;
                this.loadComments({sale_id: model.id})
                this.drawerComments = true;
            },
            controlExcelData() {
                this.excel_fields = {};
                for (let key in this.columns) {
                    if (this.columns.hasOwnProperty(key)) {
                        let column = this.columns[key];
                        if (column.show && column.column !== "settings") {
                            switch (column.column) {
                            case "state_id":
                                this.excel_fields[column.title] = "state.state";
                                break;
                            case "level_id":
                                this.excel_fields[column.title] = "level.name";
                                break;
                            case "priority_id":
                                this.excel_fields[column.title] = "priority.name";
                                break;
                            default:
                                this.excel_fields[column.title] = column.column;
                                break;
                            }
                        }
                    }
                }
            },
            commentStore(form){
                this.loadingComments = true;
                this.postComment(form)
                    .then(res => {
                        this.loadingComments = false;
                        this.$alert(res)
                        this.loadComments({sale_id: this.selectedItem.id})
                    })
                    .catch(err => {
                        this.loadingComments = false;
                        this.$alert(err)
                    })
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
            changeLevel(id) {
                this.filterForm.level_id = id;
            },
            handleCheckAllChange() {
                this.selectedItems = this.checkAll
                    ? (this.selectedItems = this.list)
                    : [];
                this.handleCheckedItemsChange();
            },
            readyBtnType(sale){
                if (sale.total_products == sale.ready_products && sale.total_products >= 0) {
                    return 'success';
                }else{
                    return 'warning';
                }
            },
            handleCheckedItemsChange() {
                this.checkAll = this.selectedItems.length === this.list.length;
            },
            showItems(model) {
                this.dialogItemsVisible = true;
                this.loadingItems = true;
                this.loadItems({sale_id: model.id})
                .then(res => {
                    this.loadingItems = false;
                })
                .catch(err => {
                    this.loadingItems = false;
                    this.$alert(err)
                })
            }
        }
    };
</script>
