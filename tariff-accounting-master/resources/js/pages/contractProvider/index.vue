<template  ref="ContractProvidersList">
 <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:280px">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Provider(For Clients)")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search"
                    v-model="filterForm.search" clearable></el-input>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <el-button v-can="'contractProviders.create'" size="mini" @click="drawerCreate = true"
                    icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel v-can="'contractProviders.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.Provider(For Clients)')" :name="$t('message.Provider(For Clients)')">
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
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.number" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.provider_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.parent_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.begin_date" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.status_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.sum" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.paid" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>
            <tr>
                <th v-if="columns.id.show">
                    <el-input clearable size="mini" class="id_input" v-model="filterForm.id"
                        :placeholder="columns.id.title"></el-input>
                </th>
                <th v-if="columns.number.show">
                    <el-input size="mini" v-model="filterForm.number" :placeholder="columns.number.title" clearable>
                    </el-input>
                </th>
                <th v-if="columns.provider_id.show">
                    <providers v-model="filterForm.provider_id" size="mini"></providers>
                </th>
                <th v-if="columns.parent_id.show">
                    <contracts v-model="filterForm.parent_id" size="mini"></contracts>
                </th>
                <th v-if="columns.begin_date.show">
                    <el-date-picker v-model="filterForm.begin_date" :placeholder="columns.begin_date.title" size="mini">
                    </el-date-picker>
                </th>
                <th v-if="columns.status_id.show">
                    <states v-model="filterForm.status_id" size="mini"></states>
                </th>
                <th v-if="columns.sum.show">
                    <el-input size="mini" v-model="filterForm.sum" :placeholder="columns.sum.title" clearable>
                    </el-input>
                </th>
                <th v-if="columns.paid.show">
                    <el-input size="mini" v-model="filterForm.paid" :placeholder="columns.paid.title"
                        clearable></el-input>
                </th>
                <th v-if="columns.comment.show">
                    <el-input size="mini" v-model="filterForm.comment" :placeholder="columns.comment.title" clearable>
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
            <tr v-for="contract in list" :key="contract.id" class="cursor-pointer">
                <td v-if="columns.id.show">{{ contract.id }}</td>
                <td v-if="columns.number.show">{{ contract.number }}</td>
                <td v-if="columns.provider_id.show">{{ (contract.provider) ? contract.provider.name : '' }}</td>
                <td v-if="columns.parent_id.show">{{ (contract.parent) ? contract.parent.number : '' }}
                </td>
                <td v-if="columns.begin_date.show">{{ contract.begin_date }}</td>
                <td v-if="columns.status_id.show">{{ contract.status ? contract.status.state : ''}}</td>
                <td v-if="columns.sum.show">{{ contract.sum | formatMoney(2) }}</td>
                <td v-if="columns.paid.show">{{ contract.paid | formatMoney(2) }}</td>
                <td v-if="columns.comment.show">{{ contract.comment }}</td>
                <td v-if="columns.created_at.show">{{ contract.created_at }}</td>
                <td v-if="columns.updated_at.show">{{ contract.updated_at }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="contract" name="contractProviders" :actions="actions" @edit="edit" @delete="destroy">
                    </crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
        <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
        <crm-update :contract="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
    </el-drawer>
</div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import providers from '@inventory/crm-provider-select';
    import contracts from '@inventory/crm-contract-provider-select';
    import states from '@inventory/crm-state-select';
	import list from '@/utils/mixins/list';

    export default {
        name: "ContractProvidersList",
        mixins:[list],
        components: { CrmCreate , CrmUpdate, providers, contracts, states},
        computed:{
            ...mapGetters({
                list: 'contractProviders/list',
                columns: "contractProviders/columns",
                pagination: "contractProviders/pagination",
                filter: "contractProviders/filter",
                sort: "contractProviders/sort",
            }),
            actions: function() {
                return ['edit','delete'];
            }
        },
        methods: {
            ...mapActions({
                updateList: 'contractProviders/index',
                updateSort: "contractProviders/updateSort",
                updateFilter: "contractProviders/updateFilter",
                updateColumn: "contractProviders/updateColumn",
                updatePagination: "contractProviders/updatePagination",
                empty: 'contractProviders/empty',
                delete: 'contractProviders/destroy',
                refreshData: 'contractProviders/refreshData',
            }),
            controlExcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
      						switch (column.column) {
                                case "provider_id":
                                    this.excel_fields[column.title] = "provider.name";
                                    break;
                                case "status_id":
                                    this.excel_fields[column.title] = "status.state";
                                    break;
                                case "parent_id":
                                    this.excel_fields[column.title] = "parent.number";
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
