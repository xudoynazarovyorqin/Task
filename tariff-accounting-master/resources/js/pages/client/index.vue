<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3 d-inline">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.clients") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'clients.create'" type="primary" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline">{{ $t('message.create') }}</el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.clients')" :name="$t('message.clients')">
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
                    <crm-th :column="columns.phone" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.email" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_name" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.district_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.quarter_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_street" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_home" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_corps" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_flat" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.actual_address" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.type_id" :sort="sort" @c-change="updateSort"></crm-th>
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
                    <th v-if="columns.phone.show">
                        <el-input size="mini" v-model="filterForm.phone" :placeholder="columns.phone.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.email.show">
                        <el-input size="mini" v-model="filterForm.email" :placeholder="columns.email.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.object_name.show">
                        <el-input
                        size="mini"
                        v-model="filterForm.object_name"
                        :placeholder="columns.object_name.title"
                        clearable
                        ></el-input>
                    </th>
                    <th v-if="columns.district_id.show">
                        <districts size="mini" v-model="filterForm.district_id"></districts>
                    </th>
                    <th v-if="columns.quarter_id.show">
                        <quarters size="mini" v-model="filterForm.quarter_id"></quarters>
                    </th>
                    <th v-if="columns.object_street.show">
                        <el-input
                        size="mini"
                        v-model="filterForm.object_street"
                        :placeholder="columns.object_street.title"
                        clearable
                        ></el-input>
                    </th>
                    <th v-if="columns.object_home.show">
                        <el-input
                        size="mini"
                        v-model="filterForm.object_home"
                        :placeholder="columns.object_home.title"
                        clearable
                        ></el-input>
                    </th>
                    <th v-if="columns.object_corps.show">
                        <el-input
                        size="mini"
                        v-model="filterForm.object_corps"
                        :placeholder="columns.object_corps.title"
                        clearable
                        ></el-input>
                    </th>
                    <th v-if="columns.object_flat.show">
                        <el-input
                        size="mini"
                        v-model="filterForm.object_flat"
                        :placeholder="columns.object_flat.title"
                        clearable
                        ></el-input>
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
                <tr v-for="client in list" :key="client.id" class="cursor-pointer" @dblclick="edit(client)" :title="$t('message.Double tap to edit')">
                    <td v-if="columns.id.show">{{ client.id }}</td>
                    <td v-if="columns.name.show">{{ client.name }}</td>
                    <td v-if="columns.phone.show">{{ client.phone }}</td>
                    <td v-if="columns.email.show">{{ client.email }}</td>
                    <td v-if="columns.object_name.show">{{ client.object_name }}</td>
                    <td v-if="columns.district_id.show">{{ (client.district) ? client.district.name : '' }}</td>
                    <td v-if="columns.quarter_id.show">{{ (client.quarter) ? client.quarter.name : '' }}</td>
                    <td v-if="columns.object_street.show">{{ client.object_street }}</td>
                    <td v-if="columns.object_home.show">{{ client.object_home }}</td>
                    <td v-if="columns.object_corps.show">{{ client.object_corps }}</td>
                    <td v-if="columns.object_flat.show">{{ client.object_flat }}</td>
                    <td v-if="columns.comment.show">{{ client.comment }}</td>
                    <td v-if="columns.actual_address.show">{{ client.actual_address | truncate}}</td>
                    <td v-if="columns.type_id.show">{{ ((types) ? (types[client.type_id]) ? types[client.type_id].name : '' : '') }}</td>
                    <td v-if="columns.inn.show">{{ client.inn }}</td>
                    <td v-if="columns.mfo.show">{{ client.mfo }}</td>
                    <td v-if="columns.okonx.show">{{ client.okonx }}</td>
                    <td v-if="columns.oked.show">{{ client.oked }}</td>
                    <td v-if="columns.rkp_nds.show">{{ client.rkp_nds }}</td>
                    <td v-if="columns.created_at.show">{{ client.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ client.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="client"  name='clients' :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="70%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="70%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :client="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import types from "@inventory/crm-agent-type-select";
    import districts from "@inventory/crm-district-select";
    import quarters from "@inventory/crm-quarter-select";
    import list from '@/utils/mixins/list'

    export default {
        mixins:[list],
        name: "ClientsList",
        components: { CrmCreate , CrmUpdate, types, districts, quarters},
        computed:{
            ...mapGetters({
                list: 'clients/list',
                columns: "clients/columns",
                pagination: "clients/pagination",
                filter: "clients/filter",
                sort: "clients/sort",
                types: 'clients/types'
            }),
            actions: function() {
                return ['edit','delete'];
            }
        },
        methods: {
            ...mapActions({
                updateList: 'clients/index',
                updateSort: "clients/updateSort",
                updateFilter: "clients/updateFilter",
                updateColumn: "clients/updateColumn",
                updatePagination: "clients/updatePagination",
                empty: 'clients/empty',
                delete: 'clients/destroy',
                refreshData: 'clients/refreshData',
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
            },
        }
    };
</script>
