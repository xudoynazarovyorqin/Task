<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:400px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.payments") }}</h5>
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
                    <export-excel
                        :data="list"
                        :fields="excel_fields"
                        @fetch="controlExcelData()"
                        :worksheet="$t('message.payments')"
                        :name="$t('message.payments')"
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
        <table class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white" v-loading="loadingData">
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
            <tr>
                <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.client_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.console_number" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.application_id" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.start_date" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.stop_date" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.amount" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :column="columns.paid" :sort="sort" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
            </tr>

            <tr>
                <th v-if="columns.id.show">
                <el-input
                    clearable
                    size="mini"
                    v-model="filterForm.id"
                    class="id_input"
                    :placeholder="columns.id.title"
                ></el-input>
                </th>
                <th v-if="columns.client_id.show">
                <clients size="mini" v-model="filterForm.client_id"></clients>
                </th>
                <th v-if="columns.console_number.show">
                <el-input
                    clearable
                    size="mini"
                    v-model="filterForm.console_number"
                    :placeholder="columns.console_number.title"
                ></el-input>
                </th>
                <th v-if="columns.application_id.show">
                <el-input
                    clearable
                    size="mini"
                    v-model="filterForm.application_id"
                    :placeholder="columns.application_id.title"
                ></el-input>
                </th>
                <th v-if="columns.start_date.show">
                <el-date-picker
                v-model="filterForm.start_date"
                :placeholder="columns.start_date.title"
                size="mini"
                :format="date_format"
                :value-format="date_format"
                ></el-date-picker>
                </th>
                <th v-if="columns.stop_date.show">
                <el-date-picker
                v-model="filterForm.stop_date"
                :placeholder="columns.stop_date.title"
                size="mini"
                :format="date_format"
                :value-format="date_format"
                ></el-date-picker>
                </th>
                <th v-if="columns.amount.show">
                    <el-input
                    size="mini"
                    v-model="filterForm.amount"
                    :placeholder="columns.amount.title"
                    clearable
                    ></el-input>
                </th>
                <th v-if="columns.paid.show">
                    <el-input
                    size="mini"
                    v-model="filterForm.paid"
                    :placeholder="columns.paid.title"
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
            </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
            <tr v-for="applicationPart in list" :key="applicationPart.id" class="cursor-pointer">
                <td v-if="columns.id.show">{{ applicationPart.id }}</td>
                <td v-if="columns.client_id.show">{{ (applicationPart.application) ? (applicationPart.application.client) ? applicationPart.application.client.name : '' : '' }}</td>
                <td v-if="columns.console_number.show">{{ (applicationPart.application) ? applicationPart.application.console_number : '' }}</td>
                <td v-if="columns.application_id.show">{{ applicationPart.application_id }}</td>
                <td v-if="columns.start_date.show">{{ applicationPart.start_date }}</td>
                <td v-if="columns.stop_date.show">{{ applicationPart.stop_date }}</td>
                <td v-if="columns.amount.show">{{ applicationPart.amount | formatMoney }}</td>
                <td v-if="columns.paid.show">{{ applicationPart.paid | formatMoney }}</td>
                <td v-if="columns.created_at.show">{{ applicationPart.created_at | dateFormat }}</td>
                <td v-if="columns.updated_at.show">{{ applicationPart.updated_at | dateFormat }}</td>
            </tr>
            </transition-group>
        </table>
    </div>
</template>
<script>

import clients from "@inventory/crm-client-select";
import { mapActions, mapGetters } from "vuex";
import list from "@/utils/mixins/list";

export default {
  mixins: [list],
  components: { clients },
  data() {
    return {

    };
  },
  computed: {
    ...mapGetters({
      list: "applicationParts/list",
      columns: "applicationParts/columns",
      pagination: "applicationParts/pagination",
      filter: "applicationParts/filter",
      sort: "applicationParts/sort"
    }),
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
      updateList: "applicationParts/index",
      updateSort: "applicationParts/updateSort",
      updateFilter: "applicationParts/updateFilter",
      updateColumn: "applicationParts/updateColumn",
      updatePagination: "applicationParts/updatePagination",
      delete: "applicationParts/destroy",
      refreshData: "applicationParts/refreshData",
      multiDelete: "applicationParts/multiDelete",
      printModel: "applicationParts/print",
      empty: "applicationParts/empty"
    }),

    controlExcelData() {
      this.excel_fields = {};
      for (let key in this.columns) {
        if (this.columns.hasOwnProperty(key)) {
          let column = this.columns[key];
          if (column.show && column.column !== "settings") {
            switch (column.column) {
              case "client_id":
                this.excel_fields[column.title] = "application.client.name";
                break;
              case "console_number":
                this.excel_fields[column.title] = "application.console_number";
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
