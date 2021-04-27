<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:120px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.categories") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <el-button v-can="'categories.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.categories')" :name="$t('message.categories')">
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
                    <crm-th :column="columns.parent_id" :sort="sort" @c-change="updateSort"></crm-th>
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
                    <th v-if="columns.parent_id.show">
                        <el-select filterable clearable :placeholder="columns.parent_id.title" size="mini"
                            v-model="filterForm.parent_id">
                            <el-option v-for="item in list" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
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
                <tr v-for="category in list" :key="category.id" class="cursor-pointer">
                    <td v-if="columns.id.show">{{ category.id }}</td>
                    <td v-if="columns.name.show">{{ category.name | truncate }}</td>
                    <td v-if="columns.parent_id.show">{{ (category.parent) ? category.parent.name : '' }}</td>
                    <td v-if="columns.created_at.show">{{ category.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ category.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="category" name="categories" :actions="actions" @edit="edit" @delete="destroy">
                        </crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="60%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="60%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :category="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>
<script>
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import {mapGetters,mapActions} from 'vuex';
    import list from "@/utils/mixins/list";

    export default {
        mixins:[list],
        name: "CategoryList",
        components: { CrmCreate , CrmUpdate},
        computed:{
            ...mapGetters({
                list: 'category/list',
                columns: "category/columns",
                pagination: "category/pagination",
                filter: "category/filter",
                sort: "category/sort",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        methods: {
            ...mapActions({
                updateList: 'category/index',
                updateSort: "category/updateSort",
                updateFilter: "category/updateFilter",
                updateColumn: "category/updateColumn",
                updatePagination: "category/updatePagination",
                empty: 'category/empty',
                delete: 'category/destroy',
                refreshData: 'category/refreshData'
            })
        }
    };
</script>
