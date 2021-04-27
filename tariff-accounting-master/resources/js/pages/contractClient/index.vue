<template>
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
        <div class="crm-content-header-left-item mr-3" style="width:280px">
          <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Contracts(For Clients)") }}</h5>
          <crm-refresh @c-click="refresh()"></crm-refresh>
        </div>
        <div class="crm-content-header-left-item">
          <el-input
            size="mini"
            :placeholder="$t('message.search')"
            prefix-icon="el-icon-search"
            v-model="filterForm.search"
            clearable
          ></el-input>
        </div>
      </div>
      <div class="crm-content-header-right d-flex w-50 justify-content-end">
        <div class="crm-content-header-right-item">
          <el-button
            v-can="'contractClients.create'"
            type="primary"
            size="mini"
            @click="drawerCreate = true"
            icon="el-icon-circle-plus-outline"
          >{{ $t('message.create') }}</el-button>
        </div>
        <div class="crm-content-header-right-item">
          <export-excel
            v-can="'contractClients.excel'"
            :data="list"
            :fields="excel_fields"
            @fetch="controlExcelData()"
            :worksheet="$t('message.Contracts(For Clients)')"
            :name="$t('message.Contracts(For Clients)')"
          >
            <el-button size="mini">
              <i class="el-icon-document-delete"></i>
              {{ $t('message.download_excel') }}
            </el-button>
          </export-excel>
        </div>
        <div class="crm-content-header-right-item">
          <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
        </div>
      </div>
    </div>
    <table
      class="table table-bordered table-hover"
      v-loading="loadingData"
      :element-loading-text="$t('message.loading')"
      element-loading-spinner="el-icon-loading"
    >
      <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
      <thead>
        <tr>
          <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.number" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.client_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.begin_date" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.status_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.conclusion_date" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.termination_date" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.sum" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.remainder" :sort="sort" @c-change="updateSort"></crm-th>
          <!-- <crm-th :column="columns.paid" :sort="sort" @c-change="updateSort"></crm-th> -->
          <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
        </tr>
        <tr>
          <th v-if="columns.id.show">
            <el-input
              clearable
              size="mini"
              class="id_input"
              v-model="filterForm.id"
              :placeholder="columns.id.title"
            ></el-input>
          </th>
          <th v-if="columns.number.show">
            <el-input
              size="mini"
              v-model="filterForm.number"
              :placeholder="columns.number.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.client_id.show">
            <clients v-model="filterForm.client_id" size="mini"></clients>
          </th>
          <th v-if="columns.begin_date.show">
            <el-date-picker
              v-model="filterForm.begin_date"
              :placeholder="columns.begin_date.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.status_id.show">
            <states v-model="filterForm.status_id" size="mini"></states>
          </th>
          <th v-if="columns.conclusion_date.show">
            <el-date-picker
              v-model="filterForm.conclusion_date"
              :placeholder="columns.conclusion_date.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.termination_date.show">
            <el-date-picker
              v-model="filterForm.termination_date"
              :placeholder="columns.termination_date.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.sum.show">
            <el-input
              size="mini"
              v-model="filterForm.sum"
              :placeholder="columns.sum.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.remainder.show">
            <el-input
              size="mini"
              v-model="filterForm.remainder"
              :placeholder="columns.remainder.title"
              clearable
            ></el-input>
          </th>
          <!-- <th v-if="columns.paid.show">
            <el-input
              size="mini"
              v-model="filterForm.paid"
              :placeholder="columns.paid.title"
              clearable
            ></el-input>
          </th> -->
          <th v-if="columns.comment.show">
            <el-input
              size="mini"
              v-model="filterForm.comment"
              :placeholder="columns.comment.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.created_at.show">
            <el-date-picker
              v-model="filterForm.created_at"
              :placeholder="columns.created_at.title"
              size="mini"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.updated_at.show">
            <el-date-picker
              v-model="filterForm.updated_at"
              :placeholder="columns.updated_at.title"
              size="mini"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.settings.show"></th>
        </tr>
      </thead>
      <transition-group name="flip-list" tag="tbody">
        <tr v-for="contract in list" :key="contract.id" class="cursor-pointer" @dblclick="edit(contract)" :title="$t('message.Double tap to edit')">
          <td v-if="columns.id.show">{{ contract.id }}</td>
          <td v-if="columns.number.show">{{ contract.number }}</td>
          <td v-if="columns.client_id.show">
              <template v-if="contract.client">
                  {{ contract.client.name }}
              </template>
              <template v-else>
                  <div style="min-width: 100%; min-height: 100%; background-color: red;"></div>
              </template>
          </td>
          <td v-if="columns.begin_date.show">{{ contract.begin_date }}</td>
          <td v-if="columns.status_id.show">
              <template v-if="contract.status && contract.status.status == 'waiting'">
                  <div class="divr_status_warning">
                      {{ contract.status ? contract.status.state : ''}}
                  </div>
              </template>
              <template v-else-if="contract.status && contract.status.status == 'active' && contract.application">
                  <div class="divr_status_completed">
                      {{ contract.status ? contract.status.state : ''}}
                  </div>
              </template>
              <template v-else-if="contract.status && contract.status.status == 'active' && !contract.application">
                  <div class="divr_status_succes">
                      {{ contract.status ? contract.status.state : ''}}
                  </div>
              </template>
              <template v-else>
                {{ contract.status ? contract.status.state : ''}}
              </template>
          </td>
          <td v-if="columns.conclusion_date.show">{{ contract.conclusion_date }}</td>
          <td v-if="columns.termination_date.show">{{ contract.termination_date }}</td>
          <td v-if="columns.sum.show">{{ contract.sum | formatMoney }}</td>
          <td v-if="columns.remainder.show">{{ contract.remainder | formatMoney }}</td>
          <!-- <td v-if="columns.paid.show">{{ contract.paid | formatMoney }}</td> -->
          <td v-if="columns.comment.show">{{ contract.comment }}</td>
          <td v-if="columns.created_at.show">{{ contract.created_at | dateFormat }}</td>
          <td v-if="columns.updated_at.show">{{ contract.updated_at | dateFormat }}</td>
          <td v-if="columns.settings.show" class="settings-td">
            <crm-setting-dropdown
              :model="contract"
              name="contractClients"
              :actions="actions"
              @edit="edit"
              @delete="destroy"
              @print="print"
            ></crm-setting-dropdown>
          </td>
        </tr>
      </transition-group>
    </table>
    <el-drawer
      :with-header="false"
      :visible.sync="drawerCreate"
      size="80%"
      ref="drawerCreate"
      @opened="drawerOpened('drawerCreateChild')"
      @closed="drawerClosed('drawerCreateChild')"
    >
      <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
    </el-drawer>
    <el-drawer
      :with-header="false"
      :visible.sync="drawerUpdate"
      size="80%"
      ref="drawerUpdate"
      @opened="drawerOpened('drawerUpdateChild')"
      @closed="drawerClosed('drawerUpdateChild')"
    >
      <crm-update :contract="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
    </el-drawer>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import CrmCreate from "./components/crm-create";
import CrmUpdate from "./components/crm-update";
import clients from "@inventory/crm-client-select";
import contracts from "@inventory/crm-contract-client-select";
import states from "@inventory/crm-state-select";
import list from "@/utils/mixins/list";

export default {
  mixins: [list],
  components: { CrmCreate, CrmUpdate, clients, contracts, states },
  computed: {
    ...mapGetters({
      list: "contractClients/list",
      columns: "contractClients/columns",
      pagination: "contractClients/pagination",
      filter: "contractClients/filter",
      sort: "contractClients/sort"
    }),
    actions: function() {
      return ["edit", "delete", "print"];
    }
  },
  methods: {
    ...mapActions({
      updateList: "contractClients/index",
      updateSort: "contractClients/updateSort",
      updateFilter: "contractClients/updateFilter",
      updateColumn: "contractClients/updateColumn",
      updatePagination: "contractClients/updatePagination",
      empty: "contractClients/empty",
      delete: "contractClients/destroy",
      refreshData: "contractClients/refreshData",
      printModel: "contractClients/print"
    }),
    controlExcelData() {
      this.excel_fields = {};
      for (let key in this.columns) {
        if (this.columns.hasOwnProperty(key)) {
          let column = this.columns[key];
          if (column.show && column.column !== "settings") {
            switch (column.column) {
              case "client_id":
                this.excel_fields[column.title] = "client.name";
                break;
              case "status_id":
                this.excel_fields[column.title] = "status.state";
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
