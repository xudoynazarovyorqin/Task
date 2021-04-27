<template ref="ApplicationList">
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
        <div class="crm-content-header-left-item mr-3" style="width:400px">
          <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Client applications") }}</h5>
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
        <div class="crm-content-header-left-item">
          <el-date-picker
            v-model="filterForm.from_date"
            type="date"
            :format="date_format"
            :value-format="date_format"
            size="mini"
            :placeholder="$t('message.from')"
          ></el-date-picker>
        </div>
        <div class="crm-content-header-left-item">
          <el-date-picker
            v-model="filterForm.to_date"
            type="date"
            :format="date_format"
            :value-format="date_format"
            size="mini"
            :placeholder="$t('message.to')"
          ></el-date-picker>
        </div>
      </div>
      <div class="crm-content-header-right d-flex w-50 justify-content-end">
        <div class="crm-content-header-right-item">
          <el-button
            v-can="'applications.create'"
            type="primary"
            size="mini"
            @click="drawerCreate = true"
            icon="el-icon-circle-plus-outline"
          >{{ $t('message.create') }}</el-button>
        </div>
        <div class="crm-content-header-right-item">
          <crm-edit-dropdown
            :items="selectedItems"
            @delete="multipleDelete"
          ></crm-edit-dropdown>
        </div>
        <div class="crm-content-header-right-item">
          <export-excel
            :data="list"
            :fields="excel_fields"
            @fetch="controlExcelData()"
            :worksheet="$t('message.Client applications')"
            :name="$t('message.Client applications')"
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

    <div class="w-100 text-center mb-1 crm">
      <el-tag
        class="border-0"
        effect="plain"
      >{{ $t('message.total_amount') }}: {{ total_amount | formatMoney(2) }}</el-tag>
      <el-tag
        class="border-0"
        effect="plain"
        type="success"
      >{{ $t('message.paid') }}: {{ total_paid | formatMoney(2) }}</el-tag>
      <el-tag
        class="border-0"
        effect="plain"
        type="danger"
      >{{ $t('message.not_paid') }}: {{ (total_amount - total_paid) | formatMoney(2) }}</el-tag>
    </div>

    <div class="crm-content-header-right d-flex w-100 justify-content-end">
        <div class="crm-content-header-right-item">
            <el-button
            type="primary"
            size="mini"
            @click="debitListExcelDownload()"
            icon="el-icon-download"
            >{{ $t('message.Debit list') }}</el-button>
        </div>
    </div>

    <div class="but_for">
      <div class="back-button-wrapper but_color2 mr_10" :class="{'actions': filterForm.status_by_payment == 'all'}">
        <button @click="changeStatusByPayment('all')" class="back-button but_color1">
          {{ $t("message.All") }}
          <span class="nomer">{{ application_counts.count_all }}</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10" :class="{'actions': filterForm.status_by_payment == 'new_applications'}">
        <button @click="changeStatusByPayment('new_applications')" class="back-button but_color1">
          {{ $t("message.New applications") }}
          <span class="nomer">{{ application_counts.count_new }}</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10" :class="{'actions': filterForm.status_by_payment == 'payment_pending'}">
        <button @click="changeStatusByPayment('payment_pending')" class="back-button but_color1">
          {{ $t("message.Payment pending") }}
          <span class="nomer">{{ application_counts.count_pending }}</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10" :class="{'actions': filterForm.status_by_payment == 'overdue'}">
        <button @click="changeStatusByPayment('overdue')" class="back-button but_color1">
          {{ $t("message.Overdue") }}
          <span class="nomer">{{ application_counts.count_overdue }}</span>
        </button>
        <span class="back-button-triangle"></span>
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
          <th></th>
          <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.datetime" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.client_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.client_phone" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.contract_client_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.status_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.console_number" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.amount" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.total_amount" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.total_paid" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.total_not_paid" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_name" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.district_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.quarter_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_street" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_address" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_home" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_corps" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.object_flat" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
          <th>{{ $t("message.view") }}</th>
          <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
        </tr>
        <tr>
          <th>
            <div class="custom-control custom-checkbox">
              <input
                type="checkbox"
                class="custom-control-input"
                v-model="checkAll"
                id="checkAll"
                @change="handleCheckAllChange"
              />
              <label class="custom-control-label cursor-pointer" for="checkAll"></label>
            </div>
          </th>
          <th v-if="columns.id.show">
            <el-input
              clearable
              size="mini"
              class="id_input"
              v-model="filterForm.id"
              :placeholder="columns.id.title"
            ></el-input>
          </th>
          <th v-if="columns.datetime.show">
            <el-date-picker
              v-model="filterForm.datetime"
              :placeholder="columns.datetime.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.client_id.show">
            <clients size="mini" v-model="filterForm.client_id"></clients>
          </th>
          <th v-if="columns.client_phone.show">
            <el-input
              size="mini"
              v-model="filterForm.client_phone"
              :placeholder="columns.client_phone.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.contract_client_id.show">
            <contracts
              size="mini"
              :client_id="filterForm.client_id"
              v-model="filterForm.contract_client_id"
            ></contracts>
          </th>
          <th v-if="columns.status_id.show">
            <states v-model="filterForm.status_id" size="mini"></states>
          </th>
          <th v-if="columns.console_number.show">
            <el-input
              size="mini"
              v-model="filterForm.console_number"
              :placeholder="columns.console_number.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.amount.show">
            <el-input
              size="mini"
              v-model="filterForm.amount"
              :placeholder="columns.amount.title"
              clearable
            ></el-input>
          </th>
          <th v-if="columns.total_amount.show">
            <el-input size="mini" disabled></el-input>
          </th>
          <th v-if="columns.total_paid.show">
            <el-input size="mini" disabled></el-input>
          </th>
          <th v-if="columns.total_not_paid.show">
            <el-input size="mini" disabled></el-input>
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
          <th v-if="columns.object_address.show">
            <el-input
              size="mini"
              v-model="filterForm.object_address"
              :placeholder="columns.object_address.title"
              clearable
            ></el-input>
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
          <th v-if="columns.created_at.show">
            <el-date-picker
              v-model="filterForm.created_at"
              :placeholder="columns.created_at.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th v-if="columns.updated_at.show">
            <el-date-picker
              v-model="filterForm.updated_at"
              :placeholder="columns.updated_at.title"
              size="mini"
              :format="date_format"
              :value-format="date_format"
            ></el-date-picker>
          </th>
          <th></th>
          <th v-if="columns.settings.show"></th>
        </tr>
      </thead>
      <transition-group name="flip-list" tag="tbody">
        <tr
          v-for="application in list"
          :key="application.id"
          :class="{'table-active': (selectedItems.indexOf(application) > -1)}"
        >
          <td>
            <div class="custom-control custom-checkbox d-inline-block">
              <input
                type="checkbox"
                class="custom-control-input"
                v-model="selectedItems"
                :value="application"
                :id="'customCheck'+application.id"
                @change="handleCheckedItemsChange"
              />
              <label class="custom-control-label cursor-pointer" :for="'customCheck'+application.id"></label>
            </div>
          </td>
          <td v-if="columns.id.show" class="cursor-pointer">{{ application.id }}</td>
          <td v-if="columns.datetime.show">{{ application.datetime }}</td>
          <td v-if="columns.client_id.show">
              <template v-if="application.client">
                  {{ application.client.name }}
              </template>
              <template v-else>
                  <div style="min-width: 100%; min-height: 100%; background-color: red;"></div>
              </template>
          </td>
          <td v-if="columns.client_phone.show">
              {{ (application.client) ? application.client.phone : '' }}
          </td>
          <td v-if="columns.contract_client_id.show">
              <template v-if="application.contract_client">
                  <!-- {{ $t("message.n") + ' ' + application.contract_client.number + ' ' + $t("message.from") + ' ' + application.contract_client.begin_date }} -->
                  {{ $t("message.n") + ' ' + application.contract_client.number }}
              </template>
              <template v-else>
                  <div style="min-width: 100%; min-height: 100%; background-color: red;"></div>
              </template>
          </td>
          <td v-if="columns.status_id.show">{{ (application.status) ? (application.status.state) : '' }}</td>
          <td v-if="columns.console_number.show">{{ application.console_number }}</td>
          <td v-if="columns.amount.show">{{ application.amount | formatMoney(2) }}</td>
          <td v-if="columns.total_amount.show">{{ application.total_amount | formatMoney(2) }}</td>
          <td v-if="columns.total_paid.show">{{ application.total_paid | formatMoney(2) }}</td>
          <td v-if="columns.total_not_paid.show">{{ application.total_amount - application.total_paid | formatMoney(2) }}</td>
          <td v-if="columns.object_name.show">{{ application.object_name }}</td>
          <td v-if="columns.district_id.show">{{ (application.district) ? application.district.name : '' }}</td>
          <td v-if="columns.quarter_id.show">{{ (application.quarter) ? application.quarter.name : '' }}</td>
          <td v-if="columns.object_address.show">{{ application.object_street }} {{ application.object_home }}-{{ application.object_flat }}</td>
          <td v-if="columns.object_street.show">{{ application.object_street }}</td>
          <td v-if="columns.object_home.show">{{ application.object_home }}</td>
          <td v-if="columns.object_corps.show">{{ application.object_corps }}</td>
          <td v-if="columns.object_flat.show">{{ application.object_flat }}</td>
          <td v-if="columns.created_at.show">{{ application.created_at | dateFormat }}</td>
          <td v-if="columns.updated_at.show">{{ application.updated_at | dateFormat }}</td>
          <td>
            <router-link slot="title" :to="{ name: 'applications.show', params: {id: application.id} }" class="link_vue">
              <i class="el-icon-view"></i> {{ $t("message.view") }}
            </router-link>
          </td>
          <td v-if="columns.settings.show" class="settings-td">
            <crm-setting-dropdown
              :model="application"
              name="applications"
              :actions="actions"
              @edit="edit"
              @print="print"
              @delete="destroy"
            ></crm-setting-dropdown>
          </td>
        </tr>
      </transition-group>
    </table>

    <el-drawer
      :with-header="false"
      :visible.sync="drawerCreate"
      size="90%"
      ref="drawerCreate"
      @closed="drawerClosed('drawerCreateChild')"
      @opened="drawerOpened('drawerCreateChild')"
    >
      <create drawer="drawerCreate" ref="drawerCreateChild"></create>
    </el-drawer>
    <el-drawer
      :with-header="false"
      :visible.sync="drawerUpdate"
      size="90%"
      ref="drawerUpdate"
      @closed="drawerClosed('drawerUpdateChild')"
      @opened="drawerOpened('drawerUpdateChild')"
    >
      <update drawer="drawerUpdate" :application="selectedItem" ref="drawerUpdateChild"></update>
    </el-drawer>
  </div>
</template>
<script>
import create from "./components/crm-create";
import update from "./components/crm-update";
import clients from "@inventory/crm-client-select";
import contracts from "@inventory/crm-contract-client-select";
import states from "@inventory/crm-state-select";
import districts from "@inventory/crm-district-select";
import quarters from "@inventory/crm-quarter-select";
import { mapActions, mapGetters } from "vuex";
import list from "@/utils/mixins/list";

export default {
  mixins: [list],
  components: { create, update, clients, states, contracts, districts, quarters },
  data() {
    return {

    };
  },
  computed: {
    ...mapGetters({
      list: "applications/list",
      total_amount: "applications/total_amount",
      total_paid: "applications/total_paid",
      application_counts: "applications/application_counts",
      columns: "applications/columns",
      pagination: "applications/pagination",
      filter: "applications/filter",
      sort: "applications/sort"
    }),
    actions: function() {
      return ["print", "delete", "edit"];
    },
    // totalAmount: function() {
    //   return _.sumBy(this.list, "amount");
    // },
    // totalPaid: function() {
    //   return _.sumBy(this.list, "amount");
    // }
  },
  mounted() {
    this.controlExcelData();
  },
  watch: {
    list: {
      handler: function() {
        this.checkAll = false;
        this.selectedItems = [];
      },
      deep: true
    }
  },
  methods: {
    ...mapActions({
      updateList: "applications/index",
      updateSort: "applications/updateSort",
      updateFilter: "applications/updateFilter",
      updateColumn: "applications/updateColumn",
      updatePagination: "applications/updatePagination",
      delete: "applications/destroy",
      refreshData: "applications/refreshData",
      multiDelete: "applications/multiDelete",
      printModel: "applications/print",
      empty: "applications/empty",
      debitListExcel: "applications/debitListExcel"
    }),

    changeStatusByPayment (value) {
        this.filterForm.status_by_payment = value;
    },

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
              case "contract_client_id":
                this.excel_fields[column.title] = this.$t("message.n") + "contract_client.number" + " " + this.$t("message.from") + " " + "contract_client.begin_date";
                break;
              case "status_id":
                this.excel_fields[column.title] = "status.state";
                break;
              case "district_id":
                this.excel_fields[column.title] = "district.name";
                break;
              default:
                this.excel_fields[column.title] = column.column;
                break;
            }
          }
        }
      }
    },
    multipleDelete(items) {
      this.multiDelete({
        items: items.map(item => {
          return item.id;
        })
      })
        .then(res => {
          this.$alert(res);
          this.fetchData();
        })
        .catch(err => {
          this.$alert(err);
        });
    },
    debitListExcelDownload() {
        this.debitListExcel(this.filter)
        .then(res => {
          const url = window.URL.createObjectURL(
            new Blob([res.data], { type: "application/vnd.ms-excel" })
          );

          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "DebitList.xls");
          document.body.appendChild(link);
          link.click();
        })
        .catch(err => {
          this.$alert(err)
        });
    },
    handleCheckAllChange() {
      this.selectedItems = this.checkAll
        ? (this.selectedItems = this.list)
        : [];
      this.handleCheckedItemsChange();
    },
    handleCheckedItemsChange() {
      this.checkAll = this.selectedItems.length === this.list.length;
    }
  }
};
</script>
