<template>
<div class="row table-sm mr-0 ml-0 p-0">
    <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
     <div class="crm-content-header-left d-flex w-50">
               <div class="crm-content-header-left-item mr-3" style="width:400px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.user_auth_logs")  }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
               </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search"
                    clearable></el-input>
                </div>
                <div class="crm-content-header-left-item">
                     <el-date-picker
                        v-model="filterForm.from_date"
                        type="date"
                        format="yyyy-MM-dd"
                        :value-format="date_format"
                        size="mini"
                        :placeholder="$t('message.from')">
                        </el-date-picker>
                </div>
                <div class="crm-content-header-left-item">
                    <el-date-picker
                        v-model="filterForm.to_date"
                        type="date"
                        format="yyyy-MM-dd"
                        :value-format="date_format"
                        size="mini"
                        :placeholder="$t('message.to')">
                        </el-date-picker>
                </div>
           </div>
           <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                  <export-excel v-can="'userAuthLogs.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()"
                        :worksheet="$t('message.user_auth_logs') " :name="$t('message.user_auth_logs')+'.xls'">
                        <el-button size="mini" icon="el-icon-document-delete"> {{ $t('message.download_excel') }}</el-button>
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
                <crm-th :column="columns.user_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.ip_address" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.status" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>
            <tr>
                <th v-if="columns.id.show">
                    <el-input clearable size="mini" class="id_input" v-model="filterForm.id"
                        :placeholder="columns.id.title"></el-input>
                </th>
                <th v-if="columns.user_id.show">
                    <users v-model="filterForm.user_id" size="mini"></users>
                </th>
                <th v-if="columns.ip_address.show">
                    <el-input size="mini" v-model="filterForm.ip_address" :placeholder="columns.ip_address.title"
                        clearable></el-input>
                </th>
                <th v-if="columns.status.show">
                    <el-select filterable clearable :placeholder="columns.status.title" size="mini"
                        v-model="filterForm.status">
                        <el-option :label="'Вход'" :value="1"></el-option>
                        <el-option :label="'Выход'" :value="0"></el-option>
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
            <tr v-for="userAuthLog in list" :key="userAuthLog.id" class="cursor-pointer">
                <td v-if="columns.id.show">{{ userAuthLog.id }}</td>
                <td v-if="columns.user_id.show">{{ (userAuthLog.user) ? (userAuthLog.user.name) : '' }}</td>
                <td v-if="columns.ip_address.show">{{ userAuthLog.ip_address }}</td>
                <td v-if="columns.status.show">
                    <template v-if="userAuthLog.status">Вход</template>
                    <template v-else>Выход</template>
                </td>
                <td v-if="columns.created_at.show">{{ userAuthLog.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ userAuthLog.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown name="userAuthLogs" :model="userAuthLog" :actions="actions" @delete="destroyModel">
                    </crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
    </table>
</div>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import users from '@inventory/crm-user-select';
    export default {
        components:{users},
        name: "UserAuthLogList",
        data(){
            return {
                filterForm: {},
                excel_data: [],
                excel_fields: {},
                loadingData: false
            }
        },
        computed:{
            ...mapGetters({
                list: 'userAuthLogs/list',
                columns: "userAuthLogs/columns",
                pagination: "userAuthLogs/pagination",
                filter: "userAuthLogs/filter",
                sort: "userAuthLogs/sort",
                users: "users/list",
            }),
            actions: function () {
              return ['delete']
            }
        },
        async created() {
            this.filterForm = JSON.parse(JSON.stringify( this.filter ));
        },
        async mounted(){
            this.fetchData();
            this.controlExcelData();

            if (this.users && this.users.length === 0)
              await this.loadUsers();
        },
        watch: {
            filterForm: {
                handler: async function(newVal, oldVal) {
                    await this.updateFilter(newVal);
                    this.fetchData();
                },
                deep: true
            },
            sort: {
                handler: async function(newVal, oldVal) {
                    await this.fetchData();
                }
            },
            pagination: {
                handler: async function(newVal, oldVal) {
                    await this.fetchData();
                },
                deep: true
            },
            columns: {
                handler: function () {
                    this.controlExcelData()
                },
                deep: true
            }
        },
  methods: {
      ...mapActions({
          updateList: 'userAuthLogs/index',
          updateSort: "userAuthLogs/updateSort",
          updateFilter: "userAuthLogs/updateFilter",
          updateColumn: "userAuthLogs/updateColumn",
          updatePagination: "userAuthLogs/updatePagination",
          loadUsers: "users/index",
          delete: 'userAuthLogs/destroy',
          refreshData: 'userAuthLogs/refreshData',
      }),
      fetchData() {
          const query = { ...this.filter, ...this.pagination, ...this.sort };
          if (!this.loadingData) {
              this.loadingData = true;
              this.updateList(query).then(res => {
                  this.loadingData = false
              }).catch(err => {
                  this.loadingData = false
              });
          }
      },
      refresh() {
          this.refreshData()
              .then(res => {
                  this.filterForm = JSON.parse( JSON.stringify( this.filter ))
                  this.fetchData();
              })
              .catch(err => {

              })
      },
      closeDrawer(obj){
          if (obj.reload){
              this.fetchData()
          }
          if (obj.drawer){
              this[obj.drawer] = false
          }
      },
      controlExcelData(){
          this.excel_fields = {};
          for (let key in this.columns){
              if (this.columns.hasOwnProperty(key)){
                  let column = this.columns[key];
                  if (column.show && column.column !== 'settings'){
                      if (column.column == 'user_id') {
                        this.excel_fields[column.title] = 'user.name';                          
                      }else
                        this.excel_fields[column.title] = column.column;
                  }
              }
          }
      },
      destroyModel(model){
          this.delete(model.id)
              .then(res => {
                  this.$alert(res);
                  this.fetchData()
              })
              .catch(err => {
                  alert(err)
              })
      },
      emptyModel(){
          this.empty()
      },
  }
};
</script>
