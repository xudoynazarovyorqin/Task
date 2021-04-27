<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3 d-inline">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.providers") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'providers.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.providers')" :name="$t('message.providers')">
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
                    <crm-th :column="columns.full_name" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.balance" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.sku" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.phone" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.total_buy" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.total_buy_paid" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.total_buy_not_paid" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.fax" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.email" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.actual_address" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.type_id" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.legal_address" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.inn" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.mfo" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.okonx" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.oked" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.rkp_nds" :sort="sort" @c-change="updateSort"></crm-th>
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
                    <th v-if="columns.full_name.show">
                        <el-input size="mini" v-model="filterForm.full_name" :placeholder="columns.full_name.title"
                            clearable></el-input>
                    </th>
                    <th v-if="columns.balance.show">
                        <el-input size="mini" v-model="filterForm.balance" :placeholder="columns.balance.title"
                            clearable></el-input>
                    </th>
                    <th v-if="columns.sku.show">
                        <el-input size="mini" v-model="filterForm.sku" :placeholder="columns.sku.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.phone.show">
                        <el-input size="mini" v-model="filterForm.phone" :placeholder="columns.phone.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.total_buy.show">
                        <el-input size="mini" disabled clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.total_buy_paid.show">
                        <el-input size="mini" disabled clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.total_buy_not_paid.show">
                        <el-input size="mini" disabled clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.fax.show">
                        <el-input size="mini" v-model="filterForm.fax" :placeholder="columns.fax.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.email.show">
                        <el-input size="mini" v-model="filterForm.email" :placeholder="columns.email.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.comment.show">
                        <el-input size="mini" v-model="filterForm.comment" :placeholder="columns.comment.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.actual_address.show">
                        <el-input size="mini" v-model="filterForm.actual_address"
                            :placeholder="columns.actual_address.title" clearable></el-input>
                    </th>
                    <th v-if="columns.type_id.show">
                        <types v-model="filterForm.type_id" size="mini"></types>
                    </th>
                    <th v-if="columns.legal_address.show">
                        <el-input size="mini" v-model="filterForm.legal_address" :placeholder="columns.legal_address.title"
                            clearable></el-input>
                    </th>
                    <th v-if="columns.inn.show">
                        <el-input size="mini" v-model="filterForm.inn" :placeholder="columns.inn.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.mfo.show">
                        <el-input size="mini" v-model="filterForm.mfo" :placeholder="columns.mfo.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.okonx.show">
                        <el-input size="mini" v-model="filterForm.okonx" :placeholder="columns.okonx.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.oked.show">
                        <el-input size="mini" v-model="filterForm.oked" :placeholder="columns.oked.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.rkp_nds.show">
                        <el-input size="mini" v-model="filterForm.rkp_nds" :placeholder="columns.rkp_nds.title" clearable>
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
                    <th v-if="columns.settings.show"></th>
                </tr>
            </thead>

            <transition-group name="flip-list" tag="tbody">
                <tr v-for="provider in list" :key="provider.id" class="cursor-pointer">
                    <td v-if="columns.id.show">{{ provider.id }}</td>
                    <td v-if="columns.name.show">{{ provider.name }}</td>
                    <td v-if="columns.full_name.show">{{ provider.full_name }}</td>
                    <td v-if="columns.balance.show">{{ provider.balance | formatMoney }}</td>
                    <td v-if="columns.sku.show">{{ provider.sku }}</td>
                    <td v-if="columns.phone.show">{{ provider.phone }}</td>
                    <td v-if="columns.total_buy.show">{{ provider.total_buy | formatMoney(2) }}</td>
                    <td v-if="columns.total_buy_paid.show">{{ provider.total_buy_paid | formatMoney(2) }}</td>
                    <td v-if="columns.total_buy_not_paid.show">{{ provider.total_buy_not_paid | formatMoney(2) }}</td>
                    <td v-if="columns.fax.show">{{ provider.fax }}</td>
                    <td v-if="columns.email.show">{{ provider.email }}</td>
                    <td v-if="columns.comment.show">{{ provider.comment }}</td>
                    <td v-if="columns.actual_address.show">{{ provider.actual_address }}</td>
                    <td v-if="columns.type_id.show"> {{ provider.type ? provider.type.name : '' }}</td>
                    <td v-if="columns.legal_address.show">{{ provider.legal_address }}</td>
                    <td v-if="columns.inn.show">{{ provider.inn }}</td>
                    <td v-if="columns.mfo.show">{{ provider.mfo }}</td>
                    <td v-if="columns.okonx.show">{{ provider.okonx }}</td>
                    <td v-if="columns.oked.show">{{ provider.oked }}</td>
                    <td v-if="columns.rkp_nds.show">{{ provider.rkp_nds }}</td>
                    <td v-if="columns.created_at.show">{{ provider.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ provider.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="provider" name="providers" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :provider="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from '@/utils/mixins/list'
    import types from "@inventory/crm-agent-type-select";

    export default {
        mixins:[list],
        name: "ProvidersList",
        components: { CrmCreate , CrmUpdate},
        computed:{
            ...mapGetters({
                list: 'providers/list',
                columns: "providers/columns",
                pagination: "providers/pagination",
                filter: "providers/filter",
                sort: "providers/sort",
            }),
            actions: function(){
                return ['edit','delete'];
            }
        },
        methods: {
            ...mapActions({
                updateList: 'providers/index',
                updateSort: "providers/updateSort",
                updateFilter: "providers/updateFilter",
                updateColumn: "providers/updateColumn",
                updatePagination: "providers/updatePagination",
                empty: 'providers/empty',
                delete: 'providers/destroy',
                refreshData: 'providers/refreshData',
            }),
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
      						switch (column.column) {
                                case "type_id":
                                    this.excel_fields[column.title] = "type.name";
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
