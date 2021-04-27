<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3 d-inline">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.currencies") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'currencies.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.currencies')" :name="$t('message.currencies')">
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
        <table class="table table-bordered table-hover"  v-loading="loadingData"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading">
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
                <tr>
                    <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.active" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.symbol" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.rate" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="currency in list" :key="currency.id"  class="cursor-pointer" @dblclick="edit(currency)" :title="$t('message.Double tap to view')">
                    <td v-if="columns.id.show">{{ currency.id }}</td>
                    <td v-if="columns.active.show">
                        <img v-if="currency.active" src="/images/active.png">
                    </td>
                    <td v-if="columns.symbol.show">{{ currency.symbol }}</td>
                    <td v-if="columns.name.show">{{ currency.name }}</td>
                    <td v-if="columns.rate.show">{{ currency.rate | formatNumber }}</td>
                    <td v-if="columns.created_at.show">{{ currency.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ currency.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="currency" name="currencies" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="60%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="60%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :currency="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";

    export default {
        mixins: [list],
        name: "CurrencyList",
        components:{CrmUpdate,CrmCreate},
        computed:{
            ...mapGetters({
                list: 'currencies/list',
                columns: "currencies/columns",
                pagination: "currencies/pagination",
                filter: "currencies/filter",
                sort: "currencies/sort",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: 'currencies/index',
                updateSort: "currencies/updateSort",
                updateFilter: "currencies/updateFilter",
                updateColumn: "currencies/updateColumn",
                updatePagination: "currencies/updatePagination",
                empty: 'currencies/empty',
                delete: 'currencies/destroy',
                refreshData: 'currencies/refreshData',
            }),
        }
    };
</script>
