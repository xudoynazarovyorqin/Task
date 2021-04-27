<template  ref="SaleReadyProductsList">
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
        <div class="crm-content-header-left-item mr-3" style="width:400px">
          <h5 class="d-inline mr-2 font-weight-bold">Заявка</h5>
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
            :worksheet="$t('message.sale_ready_products')"
            :name="$t('message.sale_ready_products')"
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
      >{{ $t('message.total_amount') }}: {{ totalAmount | formatMoney(2) }}</el-tag>
      <el-tag
        class="border-0"
        effect="plain"
        type="success"
      >{{ $t('message.paid') }}: {{ totalPaid | formatMoney(2) }}</el-tag>
      <el-tag
        class="border-0"
        effect="plain"
        type="danger"
      >{{ $t('message.not_paid') }}: {{ (totalAmount - totalPaid) | formatMoney(2) }}</el-tag>
    </div>

    <div class="but_for">
      <div class="back-button-wrapper actions but_color2 mr_10">
        <button class="back-button but_color1">
          Все
          <span class="nomer">0</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10">
        <button class="back-button but_color1">
          Новые заказы
          <span class="nomer">0</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10">
        <button class="back-button but_color1">
          Ожидается оплата
          <span class="nomer">0</span>
        </button>
        <span class="back-button-triangle"></span>
      </div>
      <div class="back-button-wrapper but_color2 mr_10">
        <button class="back-button but_color1">
          Просроченные
          <span class="nomer">0</span>
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
          <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.user_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.client_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.datetime" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.contract_client_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.total_price" @c-change="updateSort"></crm-th>
          <!-- <crm-th :sort="sort" :column="columns.paid_price" @c-change="updateSort"></crm-th> -->
          <!-- <crm-th :sort="sort" :column="columns.not_paid" @c-change="updateSort"></crm-th> -->
          <crm-th :sort="sort" :column="columns.state_id" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
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
          <th v-if="columns.user_id.show">
            <users size="mini" v-model="filterForm.user_id"></users>
          </th>
          <th v-if="columns.client_id.show">
            <clients size="mini" v-model="filterForm.client_id"></clients>
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
          <th v-if="columns.contract_client_id.show">
            <contracts
              size="mini"
              :client_id="filterForm.client_id"
              v-model="filterForm.contract_client_id"
            ></contracts>
          </th>
          <th v-if="columns.total_price.show">
            <el-input
              size="mini"
              v-model="filterForm.total_price"
              :placeholder="columns.total_price.title"
              clearable
            ></el-input>
          </th>
          <!-- <th v-if="columns.paid_price.show">
            <el-input
              size="mini"
              v-model="filterForm.paid_price"
              :placeholder="columns.paid_price.title"
              clearable
            ></el-input>
          </th>-->
          <!-- <th v-if="columns.not_paid.show">
            <el-input size="mini" disabled clearable></el-input>
          </th>-->
          <th v-if="columns.state_id.show">
            <states v-model="filterForm.state_id" size="mini"></states>
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
          <th v-if="columns.updated_at.show"></th>
          <th v-if="columns.settings.show"></th>
        </tr>
      </thead>
      <transition-group name="flip-list" tag="tbody">
        <tr
          v-for="sale in list"
          :key="sale.id"
          :class="{'table-active': (selectedItems.indexOf(sale) > -1)}"
          class="cursor-pointer"
          @dblclick="show(sale)"
          :title="$t('message.Double tap to view')"
        >
          <td>
            <div class="custom-control custom-checkbox d-inline-block">
              <input
                type="checkbox"
                class="custom-control-input"
                v-model="selectedItems"
                :value="sale"
                :id="'customCheck'+sale.id"
                @change="handleCheckedItemsChange"
              />
              <label class="custom-control-label cursor-pointer" :for="'customCheck'+sale.id"></label>
            </div>
          </td>
          <td v-if="columns.id.show" @click="show(sale)" class="cursor-pointer">{{ sale.id }}</td>

          <td
            v-if="columns.user_id.show"
            class="cursor-pointer"
          >{{ (sale.user) ? (sale.user.name) : ''}}</td>
          <td
            v-if="columns.client_id.show"
            @click="show(sale)"
            class="cursor-pointer"
          >{{ (sale.client) ? (sale.client.name) : ''}}</td>
          <td v-if="columns.datetime.show">{{ sale.datetime }}</td>
          <td v-if="columns.contract_client_id.show">{{ sale.contract_client }}</td>
          <td v-if="columns.total_price.show">{{ sale.total_price | formatMoney(2) }}</td>
          <!-- <td v-if="columns.paid_price.show">{{ sale.paid_price | formatMoney(2) }}</td> -->
          <!-- <td v-if="columns.not_paid.show">{{ sale.not_paid | formatMoney(2) }}</td> -->
          <td v-if="columns.state_id.show">{{ (sale.state) ? (sale.state.state) : '' }}</td>
          <td v-if="columns.created_at.show">{{ sale.created_at | dateFormat }}</td>
          <td v-if="columns.updated_at.show">{{ sale.updated_at | dateFormat }}</td>
          <td v-if="columns.updated_at.show">
            <router-link slot="title" :to="{ name: 'saleReadyProductList.index' }" class="link_vue">
              <i class="el-icon-view"></i> Просмотр
            </router-link>
          </td>
          <td v-if="columns.settings.show" class="settings-td">
            <crm-setting-dropdown
              :model="sale"
              name="saleReadyProducts"
              :actions="actions"
              @edit="edit"
              @print="print"
              @delete="destroy"
              @show="show"
            ></crm-setting-dropdown>
          </td>
        </tr>
      </transition-group>
    </table>
    <el-dialog
      :title="$t('message.sale_ready_product')"
      :visible.sync="visibleShow"
      width="80%"
      top="5vh"
      @closed="empty()"
    >
      <show drawer="drawerShow" :open="visibleShow" :sale="selectedItem" ref="drawerShowChild"></show>
    </el-dialog>
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
      <update drawer="drawerUpdate" :sale="selectedItem" ref="drawerUpdateChild"></update>
    </el-drawer>
  </div>
</template>
<script>
import create from "./components/crm-create";
import update from "./components/crm-update";
import show from "./components/crm-show";
import clients from "@inventory/crm-client-select";
import states from "@inventory/crm-state-select";
import users from "@inventory/crm-user-select";
import contracts from "@inventory/crm-contract-client-select";
import { mapActions, mapGetters } from "vuex";
import list from "@/utils/mixins/list";

export default {
  mixins: [list],
  components: { create, update, show, clients, states, users, contracts },
  data() {
    return {
      visibleShow: false
    };
  },
  computed: {
    ...mapGetters({
      list: "saleReadyProducts/list",
      columns: "saleReadyProducts/columns",
      pagination: "saleReadyProducts/pagination",
      filter: "saleReadyProducts/filter",
      sort: "saleReadyProducts/sort"
    }),
    actions: function() {
      return ["show", "print", "delete", "edit"];
    },
    totalAmount: function() {
      return _.sumBy(this.list, "total_price");
    },
    totalPaid: function() {
      return _.sumBy(this.list, "paid_price");
    }
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
      updateSort: "saleReadyProducts/updateSort",
      updateFilter: "saleReadyProducts/updateFilter",
      updateColumn: "saleReadyProducts/updateColumn",
      updateList: "saleReadyProducts/index",
      updatePagination: "saleReadyProducts/updatePagination",
      delete: "saleReadyProducts/destroy",
      refreshData: "saleReadyProducts/refreshData",
      multiDelete: "saleReadyProducts/multiDelete",
      printModel: "saleReadyProducts/print",
      empty: "saleReadyProducts/empty"
    }),
    show(model) {
      this.selectedItem = model;
      this.visibleShow = true;
    },
    controlExcelData() {
      this.excel_fields = {};
      for (let key in this.columns) {
        if (this.columns.hasOwnProperty(key)) {
          let column = this.columns[key];
          if (column.show && column.column !== "settings") {
            switch (column.column) {
              case "user_id":
                this.excel_fields[column.title] = "user.name";
                break;
              case "client_id":
                this.excel_fields[column.title] = "client.name";
                break;
              case "state_id":
                this.excel_fields[column.title] = "state.state";
                break;
              case "priority_id":
                this.excel_fields[column.title] = "priority.name";
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
