<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:280px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.cost_transactions") }}</h5>
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
                    <el-button v-can="'costs.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                <export-excel v-can="'costs.excel'" class="btn excel_btn" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.costs')" :name="$t('message.costs')">
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
    <table class="table table-bordered table-hover" v-loading="loadingData" :element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <th></th>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.debit" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.payment_type_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.datetime" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.transactionable_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.amount" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.currency_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.distribution_amount" :sort="sort" @c-change="updateSort"></crm-th>
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
                <th v-if="columns.debit.show">
                    <el-select v-model="filterForm.debit" clearable :placeholder="columns.debit.title" size="mini">
                        <el-option :label="$t('message.Incoming payment')" :value="1"></el-option>
                        <el-option :label="$t('message.Outgoing payment')" :value="-1"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.payment_type_id.show">
                    <payment-types v-model="filterForm.payment_type_id" size="mini"></payment-types>
                </th>
                <th v-if="columns.datetime.show">
                    <el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini" :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.transactionable_id.show">
                    <costs v-model="filterForm.transactionable_id" size="mini"></costs>
                </th>
                <th v-if="columns.amount.show">
                    <el-input v-model="filterForm.amount" :placeholder="columns.amount.title" size="mini"></el-input>
                </th>
                <th v-if="columns.currency_id.show">
                    <currencies v-model="filterForm.currency_id" size="mini"></currencies>
                </th>
                <th v-if="columns.comment.show">
                    <el-input v-model="filterForm.comment" :placeholder="columns.comment.title" size="mini"></el-input>
                </th>
                <th v-if="columns.distribution_amount.show">
                    <el-input v-model="filterForm.distribution_amount" :placeholder="columns.distribution_amount.title" size="mini"></el-input>
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
            <tr v-for="transaction in list" :key="'transactions-'+transaction.id" class="cursor-pointer" :class="{'table-active': (selectedItems.indexOf(transaction) > -1), 'table-secondary font-style-italic' : transaction.is_child}" @dblclick="edit(transaction)" :title="$t('message.Double tap to view')">
                <td>
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="transaction" :id="'customCheck'+transaction.id" @change="handleCheckedItemsChange" />
                        <label class="custom-control-label cursor-pointer" :for="'customCheck'+transaction.id"></label>
                    </div>
                </td>
                <td v-if="columns.id.show">{{ transaction.id }}</td>
                <td v-if="columns.debit.show">{{ transaction.debit }}</td>
                <td v-if="columns.payment_type_id.show">{{ transaction.payment_type ? transaction.payment_type.name : '' }}</td>
                <td v-if="columns.datetime.show">{{ transaction.datetime }}</td>
                <td v-if="columns.transactionable_id.show"> {{ transaction.transaction ? transaction.transaction.name : '' }} </td>
                <td v-if="columns.amount.show"> {{ transaction.amount | formatNumber(2) }}</td>
                <td v-if="columns.currency_id.show"> {{ transaction.currency ? transaction.currency.symbol : '' }}</td>
                <td v-if="columns.comment.show"> {{ transaction.comment }}</td>
                <td v-if="columns.distribution_amount.show"> {{ transaction.distribution_amount | formatMoney(2) }}</td>
                <td v-if="columns.user_id.show"> {{ transaction.user ? transaction.user.name : '' }}</td>
                <td v-if="columns.created_at.show">{{ transaction.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ transaction.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="transaction" name="costs" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
        <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
        <crm-update :transaction="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
    </el-drawer>
</div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";
    import paymentTypes from '@inventory/crm-payment-type-select';
    import currencies from '@inventory/crm-currency-select';
    import costs from '@inventory/crm-cost-select';
    import users from '@inventory/crm-user-select';

    export default {
        mixins: [list],
        name: "CostTransactionsList",
        components:{ CrmUpdate,CrmCreate,paymentTypes,currencies,users,costs},
        computed:{
            ...mapGetters({
                list: 'costTransactions/list',
                columns: "costTransactions/columns",
                pagination: "costTransactions/pagination",
                filter: "costTransactions/filter",
                sort: "costTransactions/sort",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: 'costTransactions/index',
                updateSort: "costTransactions/updateSort",
                updateFilter: "costTransactions/updateFilter",
                updateColumn: "costTransactions/updateColumn",
                updatePagination: "costTransactions/updatePagination",
                empty: 'transactions/empty',
                delete: 'transactions/destroy',
                refreshData: 'costTransactions/refreshData',
            }),
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
                            switch (column.column) {
                                case "transactionable_id":
                                    this.excel_fields[column.title] = "transaction.name";
                                break;
                                case "payment_type_id":
                                    this.excel_fields[column.title] = "payment_type.name";
                                break;
                                case "currency_id":
                                    this.excel_fields[column.title] = "currency.symbol";
                                default:
                                    this.excel_fields[column.title] = column.column;
                                break;
                            }
                        }
                    }
                }
            },
            handleCheckAllChange() {
                this.selectedItems = this.checkAll
                    ? (this.selectedItems = this.list)
                    : [];
                this.handleCheckedItemsChange();
            },
            handleCheckedItemsChange() {
                this.checkAll = this.selectedItems.length === this.list.length;
            }
        }
    };
</script>
