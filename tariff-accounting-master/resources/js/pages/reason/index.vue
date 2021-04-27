<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:120px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.reasons") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'reasons.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.reasons')" :name="$t('message.reasons')">
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
                    <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.description" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :column="columns.color" :sort="sort" @c-change="updateSort"></crm-th>
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
                    <th v-if="columns.description.show">
                        <el-input size="mini" v-model="filterForm.description" :placeholder="columns.description.title"
                            clearable></el-input>
                    </th>
                    <th v-if="columns.color.show">
                        <!-- <el-color-picker v-model="filterForm.color" size="mini"></el-color-picker> -->
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
                <tr v-for="reason in list" :key="reason.id">
                    <td v-if="columns.id.show">{{ reason.id }}</td>
                    <td v-if="columns.name.show">{{ reason.name | capitalize }}</td>
                    <td v-if="columns.description.show">{{ reason.description }}</td>
                    <td v-if="columns.color.show">
                        <el-button :style="{'background-color': reason.color}" size="mini"></el-button>
                    </td>
                    <td v-if="columns.created_at.show">{{ reason.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ reason.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="reason" name="reasons" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="60%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="60%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :reason="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
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
        name: "ReasonList",
        components:{
          CrmUpdate,CrmCreate
        },
        computed:{
            ...mapGetters({
                list: 'reasons/list',
                columns: "reasons/columns",
                pagination: "reasons/pagination",
                filter: "reasons/filter",
                sort: "reasons/sort",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: 'reasons/index',
                updateSort: "reasons/updateSort",
                updateFilter: "reasons/updateFilter",
                updateColumn: "reasons/updateColumn",
                updatePagination: "reasons/updatePagination",
                empty: 'reasons/empty',
                delete: 'reasons/destroy',
                refreshData: 'reasons/refreshData',
            }),
        }
    };
</script>
