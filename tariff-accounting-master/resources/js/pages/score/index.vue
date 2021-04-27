<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:120px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Scores") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'scores.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.Scores')" :name="$t('message.Scores')">
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
                    <crm-th :column="columns.active" :sort="sort" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.name" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.branch_name" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.mfo" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.number" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.incoming" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.outgoing" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.currency_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="score in list" :key="score.id" class="cursor-pointer">
                    <td v-if="columns.id.show">{{ score.id }}</td>
                     <td v-if="columns.active.show" class="text-center">
                        <img v-if="score.active" src="/images/active.png">
                    </td>
                    <td v-if="columns.name.show">{{ score.name | truncate }}</td>
                    <td v-if="columns.branch_name.show">{{ score.branch_name | truncate }}</td>
                    <td v-if="columns.mfo.show">{{ score.mfo }}</td>
                    <td v-if="columns.number.show">{{ score.number }}</td>
                    <td v-if="columns.incoming.show">{{ score.incoming | formatNumber(2) }}</td>
                    <td v-if="columns.outgoing.show">{{ score.outgoing | formatNumber(2) }}</td>
                    <td v-if="columns.currency_id.show">{{ score.currency ? score.currency.symbol : ''}}</td>
                    <td v-if="columns.created_at.show">{{ score.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ score.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="score" name="scores" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="60%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="60%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :score="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
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
        components: { CrmCreate , CrmUpdate},
        name: "ScoreList",
        computed:{
            ...mapGetters({
                list: 'scores/list',
                columns: "scores/columns",
                pagination: "scores/pagination",
                filter: "scores/filter",
                sort: "scores/sort",
            }),
            actions: function() {
              return ['edit','delete'];
            }
        },
        methods: {
            ...mapActions({
                updateList: 'scores/index',
                updateSort: "scores/updateSort",
                updateFilter: "scores/updateFilter",
                updateColumn: "scores/updateColumn",
                updatePagination: "scores/updatePagination",
                empty: 'scores/empty',
                delete: 'scores/destroy',
                refreshData: 'scores/refreshData'
            }),
        }
    };
</script>
