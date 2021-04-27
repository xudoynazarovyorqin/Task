<template>
<div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.permissions")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search"
                clearable></el-input>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <export-excel  v-can="'permissions.excel'" class="btn excel_btn border-0" :data="list" :fields="excel_fields" @fetch="contpermissionxcelData()"
                    :worksheet="$t('message.permissions') " :name="$t('message.permissions')+'.xls'">
                    <el-button size="mini" icon="el-icon-document-delete"> {{ $t('message.download_excel') }} </el-button>
                </export-excel>
            </div>
            <div class="crm-content-header-right-item">
                <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
            </div>
        </div>
    </div>
    <table class="table table-bordered vld-parent table-hover"  v-loading="loadingData"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.parent_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.slug" :sort="sort" @c-change="updateSort"></crm-th>
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
                <th v-if="columns.parent_id.show">
                    <el-select filterable clearable :placeholder="columns.parent_id.title" size="mini"
                        v-model="filterForm.parent_id">
                        <el-option v-for="item in parent_permissions" :key="item.id" :label="item.name"
                            :value="item.id"></el-option>
                    </el-select>
                </th>
                <th v-if="columns.slug.show">
                    <el-input size="mini" v-model="filterForm.slug" :placeholder="columns.slug.title" clearable>
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
            <tr v-for="permission in list" :key="permission.id" class="cursor-pointer">
                <td v-if="columns.id.show">{{ permission.id }}</td>
                <td v-if="columns.name.show">{{ permission.name | truncate }}</td>
                <td v-if="columns.parent_id.show">{{ (permission.parent) ? permission.parent.name : ''}}</td>
                <td v-if="columns.slug.show">{{ permission.slug }}</td>
                <td v-if="columns.created_at.show">{{ permission.created_at }}</td>
                <td v-if="columns.updated_at.show">{{ permission.updated_at }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="permission" name="permissions" :actions="actions" @edit="edit" @delete="destroy">
                    </crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate"
        @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :permission="selectedItem" ref="drawerUpdateChild">
        </crm-update>
    </el-drawer>
</div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmUpdate from "./components/crm-update";
    import list from '@/utils/mixins/list';

    export default {
        mixins:[list],
        name: "PermissionsList",
        components: {CrmUpdate},
        computed:{
            ...mapGetters({
                list: 'permissions/list',
                columns: "permissions/columns",
                pagination: "permissions/pagination",
                filter: "permissions/filter",
                sort: "permissions/sort",
                parent_permissions: 'permissions/parent_permissions',
            }),
            actions: function() {
                return ['edit']
            }
        },
        async mounted(){
            this.controlexcelData();
            if (!_.size(this.parent_permissions)) this.loadParentPermissions()
        },
        methods: {
            ...mapActions({
                updateList: 'permissions/index',
                updateSort: "permissions/updateSort",
                updateFilter: "permissions/updateFilter",
                updateColumn: "permissions/updateColumn",
                updatePagination: "permissions/updatePagination",
                empty: 'permissions/empty',
                delete: 'permissions/destroy',
                refreshData: 'permissions/refreshData',
                loadParentPermissions: 'permissions/parent_permissions'
            }),
            controlexcelData(){
                this.excel_fields = {};
                for (let key in this.columns){
                    if (this.columns.hasOwnProperty(key)){
                        let column = this.columns[key];
                        if (column.show && column.column !== 'settings'){
                            switch (column.column) {
                                case 'parent_id':
                                    this.excel_fields[column.title] = 'parent.name'; break;
                                default :
                                    this.excel_fields[column.title] = column.column; break;
                            }
                        }
                    }
                }
            },
        }
    };
</script>
