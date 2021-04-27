<template>
   <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.roles")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search"
                clearable></el-input>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
            <div class="crm-content-header-right-item">
                <el-button v-can="'roles.create'" type="primary" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
            </div>
            <div class="crm-content-header-right-item">
                <export-excel  v-can="'roles.excel'" class="btn excel_btn border-0" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                    :worksheet="$t('message.roles') " :name="$t('message.roles')+'.xls'">
                    <el-button size="mini" icon="el-icon-document-delete"> {{ $t('message.download_excel') }} </el-button>
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
                <crm-th :sort="sort" :column="columns.slug" @c-change="updateSort"></crm-th>
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
            <tr v-for="role in list" :key="role.id" class="cursor-pointer" @dblclick="edit(role)" title="Дважды нажмите, чтобы посмотреть">
                <td v-if="columns.id.show">{{ role.id }}</td>
                <td v-if="columns.name.show">{{ role.name | truncate }}</td>
                <td v-if="columns.slug.show">{{ role.slug | truncate }}</td>
                <td v-if="columns.created_at.show">{{ role.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ role.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown name="roles" :model="role" :actions="actions" @edit="edit"
                        @delete="destroy"></crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="80%" ref="drawerCreate"
        @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
        <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate"
        @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :role="selectedItem" ref="drawerUpdateChild">
        </crm-update>
    </el-drawer>
</div>
</template>
<script>
    import CrmUpdate from "./components/crm-update";
    import CrmCreate from './components/crm-create';
    import {mapGetters,mapActions} from 'vuex';
    import list from '@/utils/mixins/list';

    export default {
        mixins: [list],
        components:{
          CrmCreate, CrmUpdate
        },
        data() {
            return {
            }
        },
        computed: {
            ...mapGetters({
                list: "roles/list",
                columns: "roles/columns",
                pagination: "roles/pagination",
                filter: "roles/filter",
                sort: "roles/sort",
                parent_permissions: "permissions/parent_permissions",
            }),
            actions: function() {
                return ['edit','delete']
            }
        },
        async mounted(){
            if (_.isFunction(this.controlExcelData)) {
                this.controlExcelData();
            }
            if (this.parent_permissions && this.parent_permissions.length === 0) await this.loadParentPermissions();
        },
        methods: {
            ...mapActions({
                updateSort: "roles/updateSort",
                updateFilter: "roles/updateFilter",
                updateColumn: "roles/updateColumn",
                updateList: "roles/index",
                updatePagination: "roles/updatePagination",
                delete: 'roles/destroy',
                empty: 'roles/empty',
                refreshData: 'roles/refreshData',
                loadParentPermissions: 'permissions/parent_permissions'
            }),
        },
    }
</script>
