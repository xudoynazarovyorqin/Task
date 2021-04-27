<template>
  <div class="app-container">
    <div class="mytabel table-sm mr-0 ml-0 p-0">
      <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
          <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.services") }}</h5>
          <crm-refresh @c-click="refresh()"></crm-refresh>
          <div class="text-center d-flex sorddata ml-3">
            <el-input
              class="ml-3"
              size="mini"
              placeholder="Найти"
              prefix-icon="el-icon-search"
              v-model="filterForm.search"
              clearable
            ></el-input>
          </div>
        </div>
        <div
          class="col-5 align-items-center align-self-center text-right pr-0 d-flex justify-content-end"
        >
          <el-button
            v-can="'services.create'"
            type="primary"
            class="mr-2"
            size="mini"
            @click="drawerCreate = true"
            icon="el-icon-circle-plus-outline"
          >{{ $t("message.create") }}</el-button>

          <div class="crm-content-header-right-item mr-2">
              <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.services')" :name="$t('message.services')">
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
      <table class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white" v-loading="loadingData" :element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.name" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.price" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.measurement_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>

            <tr>
                <th v-if="columns.id.show">
                    <el-input clearable size="mini" class="id_input" v-model="filterForm.id" :placeholder="columns.id.title"></el-input>
                </th>
                <th v-if="columns.name.show">
                    <el-input size="mini" v-model="filterForm.name" :placeholder="columns.name.title" clearable></el-input>
                </th>
                <th v-if="columns.price.show">
                    <el-input size="mini" v-model="filterForm.price" :placeholder="columns.price.title" clearable></el-input>
                </th>
                <th v-if="columns.measurement_id.show">
                    <measurements v-model="filterForm.measurement_id" size="mini"></measurements>
                </th>
                <th v-if="columns.created_at.show">
                    <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"
                        :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.updated_at.show">
                    <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini"
                        :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.settings.show"></th>
            </tr>
        </thead>

        <transition-group name="flip-list" tag="tbody">
            <tr v-for="service in list" :key="service.id" class="cursor-pointer" @dblclick="edit(service)" :title="$t('message.Double tap to edit')">
                <td v-if="columns.id.show"> {{ service.id }} </td>
                <td v-if="columns.name.show"> {{ service.name | truncate }} </td>
                <td v-if="columns.price.show"> {{ service.price | formatMoney }}</td>
                <td v-if="columns.measurement_id.show"> {{ (service.measurement) ? service.measurement.name : '' }}</td>
                <td v-if="columns.created_at.show">{{ service.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ service.updated_at | dateFormat }}</td>
                <td v-if="columns.settings.show" class="settings-td">
                    <crm-setting-dropdown :model="service" name="services" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
                </td>
            </tr>
        </transition-group>
      </table>
    </div>

    <el-drawer :with-header="false" :visible.sync="drawerCreate" size="80%" ref="drawerCreate" @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
        <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="80%" ref="drawerUpdate" @closed="drawerClosed('drawerUpdateChild')" @opened="drawerOpened('drawerUpdateChild')">
        <crm-update drawer="drawerUpdate" :open="drawerUpdate" :service="selectedItem" ref="drawerUpdateChild"></crm-update>
    </el-drawer>
  </div>
</template>
<script>
import CrmCreate from "./components/crm-create";
import CrmUpdate from "./components/crm-update";
import measurements from '@inventory/crm-measurement-select';
import list from '@/utils/mixins/list'
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [list],
  name: "services",
  components: { CrmCreate, CrmUpdate, measurements },
  computed:{
      ...mapGetters({
          list: 'services/list',
          columns: "services/columns",
          pagination: "services/pagination",
          filter: "services/filter",
          sort: "services/sort",
      }),
      actions: function(){
          return ['edit','delete'];
      },
  },
  methods: {
      ...mapActions({
              updateList: 'services/index',
              updateSort: "services/updateSort",
              updateFilter: "services/updateFilter",
              updateColumn: "services/updateColumn",
              updatePagination: "services/updatePagination",
              delete: 'services/destroy',
              refreshData: 'services/refreshData',
      }),
      controlExcelData(){
          this.excel_fields = {};
          for (let key in this.columns){
              if (this.columns.hasOwnProperty(key)) {
                  let column = this.columns[key];
                  if (column.show && column.column !== "settings") {
                      switch (column.column) {
                      case "measurement_id":
                          this.excel_fields[column.title] = "measurement.name";
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
