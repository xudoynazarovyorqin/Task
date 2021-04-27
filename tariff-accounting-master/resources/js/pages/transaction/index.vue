<template  ref="SalesList">
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
        <div class="crm-content-header-left-item mr-3" style="width:140px">
          <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Transactions") }}</h5>
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
            v-can="'transactions.create'"
            type="primary"
            size="mini"
            @click="incomingPaymentCreate = true"
            icon="el-icon-circle-plus-outline"
          >{{ $t('message.Cash payment') }}</el-button>
        </div>

        <div class="crm-content-header-right-item">
          <el-button
            v-can="'transactions.create'"
            type="primary"
            size="mini"
            @click="openDialogUploadFile()"
            icon="el-icon-upload2"
            plain
          >{{ $t('message.Upload file') }}</el-button>
        </div>
        <!-- <div class="crm-content-header-right-item">
                <el-button v-can="'transactions.create'" size="mini" @click="outgoingPaymentCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.Outgoing payment') }} </el-button>
        </div>-->
        <!-- <div class="crm-content-header-right-item">
                <crm-edit-dropdown v-can="'transactions.delete'" :items="selectedItems" @delete="multipleDelete"></crm-edit-dropdown>
        </div>-->
        <div class="crm-content-header-right-item">
          <export-excel
            :data="list"
            :fields="excel_fields"
            @fetch="controlExcelData()"
            :worksheet="$t('message.transactions')"
            :name="$t('message.transactions')"
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
    <div class="d-flex justify-content-center w-100">
      <div @click="filterForm.payment_system = 'payme'" style="cursor: pointer" class="itme_pelap">
        <img src="/images/logos/download.svg" />
        <div>{{ transaction_amounts.amount_payme | formatMoney }}</div>
      </div>
      <div @click="filterForm.payment_system = 'click'" style="cursor: pointer" class="itme_pelap">
        <img src="/images/logos/logo.png" style="    width: 56px;" />
        <div>{{ transaction_amounts.amount_click | formatMoney }}</div>
      </div>
      <div @click="filterForm.payment_system = 'paynet'" style="cursor: pointer" class="itme_pelap">
        <img src="/images/logos/logo-paynet.png" style="    width: 82px;" />
        <div>{{ transaction_amounts.amount_paynet | formatMoney }}</div>
      </div>
      <div @click="filterForm.payment_system = 'cash'" style="cursor: pointer" class="itme_pelap">
        <i class="iconstyle iconitme_blocks_pages4"></i>
        <div>{{ transaction_amounts.amount_cash | formatMoney }}</div>
      </div>
      <div class="itme_pelap_select">
          <districts size="mini" v-model="filterForm.district_id"></districts>
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
          <!-- <th></th> -->
          <crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.client_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.console_number" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.payment_system" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.system_transaction_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.amount" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.state" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.comment" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.transactionable_type" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :column="columns.transactionable_id" :sort="sort" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.return" @c-change="updateSort"></crm-th>
          <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
        </tr>
        <tr>
          <!-- <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" v-model="checkAll" id="checkAll" @change="handleCheckAllChange" />
                        <label class="custom-control-label cursor-pointer" for="checkAll"></label>
                    </div>
          </th>-->
          <th v-if="columns.id.show">
            <el-input
              clearable
              size="mini"
              class="id_input"
              v-model="filterForm.id"
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
          <th v-if="columns.payment_system.show">
            <payment-systems v-model="filterForm.payment_system" size="mini"></payment-systems>
          </th>
          <th v-if="columns.system_transaction_id.show">
            <el-input
              v-model="filterForm.system_transaction_id"
              clearable
              size="mini"
              :placeholder="columns.system_transaction_id.title"
            ></el-input>
          </th>
          <th v-if="columns.amount.show">
            <el-input v-model="filterForm.amount" :placeholder="columns.amount.title" size="mini"></el-input>
          </th>
          <th v-if="columns.state.show">
            <transaction-states v-model="filterForm.state" size="mini"></transaction-states>
          </th>
          <th v-if="columns.comment.show">
            <el-input v-model="filterForm.comment" :placeholder="columns.comment.title" size="mini"></el-input>
          </th>
          <th v-if="columns.transactionable_type.show">
            <el-input
              v-model="filterForm.transactionable_type"
              :placeholder="columns.transactionable_type.title"
              size="mini"
            ></el-input>
          </th>
          <th v-if="columns.transactionable_id.show">
            <el-input
              v-model="filterForm.transactionable_id"
              :placeholder="columns.transactionable_id.title"
              size="mini"
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
          <th v-if="columns.return.show"></th>
          <th v-if="columns.settings.show"></th>
        </tr>
      </thead>
      <transition-group name="flip-list" tag="tbody">
        <tr
          v-for="transaction in list"
          :key="'transactions-'+transaction.id"
          class="cursor-pointer"
          :class="{'table-active': (selectedItems.indexOf(transaction) > -1), 'table-secondary font-style-italic' : transaction.is_child}"
          @dblclick="edit(transaction)"
          :title="$t('message.Double tap to view')"
        >
          <!-- <td>
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="transaction" :id="'customCheck'+transaction.id" @change="handleCheckedItemsChange" />
                        <label class="custom-control-label cursor-pointer" :for="'customCheck'+transaction.id"></label>
                    </div>
          </td>-->
          <td v-if="columns.id.show">{{ transaction.id }}</td>
          <td v-if="columns.client_id.show">{{ (transaction.transactionable) ? (transaction.transactionable.client) ? transaction.transactionable.client.name : '' : '' }}</td>
          <td v-if="columns.console_number.show">{{ (transaction.transactionable) ? transaction.transactionable.console_number : '' }}</td>
          <td v-if="columns.payment_system.show">{{ payment_systems[transaction.payment_system] }}</td>
          <td v-if="columns.system_transaction_id.show">{{ transaction.system_transaction_id }}</td>
          <td v-if="columns.amount.show">{{ transaction.amount | formatNumber(2) }}</td>
          <td v-if="columns.state.show">
              <div :class="getRowClass(transaction)">
                  {{ transaction_states[transaction.state] }}
              </div>
          </td>
          <td v-if="columns.comment.show">{{ transaction.comment }}</td>
          <td v-if="columns.transactionable_type.show">
            <template
              v-if="transaction.transactionable_type == 'applications'"
            >{{ $t("message.application") }}</template>
            <template v-else>{{ transaction.transactionable_type }}</template>
          </td>
          <td v-if="columns.transactionable_id.show">{{ transaction.transactionable_id }}</td>
          <td v-if="columns.created_at.show">{{ transaction.created_at | dateFormat }}</td>
          <td v-if="columns.updated_at.show">{{ transaction.updated_at | dateFormat }}</td>
          <td v-if="columns.return.show">
            <el-button
                v-if="transaction.payment_system == 'cash' || transaction.payment_system == 'click'"
              :title="$t('message.Return transaction')"
              type="success"
              icon="el-icon-refresh"
              size="mini"
              @click="openDialogReturnTransaction(transaction)"
            ></el-button>
          </td>
          <td v-if="columns.settings.show" class="settings-td">
            <crm-setting-dropdown
              name="transactions"
              :model="transaction"
              :actions="actions"
              @edit="edit"
            ></crm-setting-dropdown>
          </td>
        </tr>
      </transition-group>
    </table>
    <el-drawer
      :with-header="false"
      :visible.sync="incomingPaymentCreate"
      size="70%"
      ref="incomingPaymentCreate"
      @closed="drawerClosed('incomingPaymentCreateChild')"
      @opened="drawerOpened('incomingPaymentCreateChild')"
    >
      <incoming drawer="incomingPaymentCreate" ref="incomingPaymentCreateChild"></incoming>
    </el-drawer>
    <el-drawer
      :with-header="false"
      :visible.sync="incomingPaymentUpdate"
      size="95%"
      ref="incomingPaymentUpdate"
      @closed="drawerClosed('incomingPaymentUpdateChild')"
      @opened="drawerOpened('incomingPaymentUpdateChild')"
    >
      <incoming-update
        drawer="incomingPaymentUpdate"
        :transaction="selectedItem"
        ref="incomingPaymentUpdateChild"
      ></incoming-update>
    </el-drawer>
    <el-dialog :title="$t('message.Return transaction')" :visible.sync="dialogTableVisible">
      <el-form
        ref="return_form"
        :model="return_form"
        label-width="120px"
        size="medium"
        v-loading="loadReturnTransaction"
        :rules="{
           comment: [
            { required: true, message: $t('message.This field is required'), trigger: 'change' },
          ],
       }"
      >
        <el-form-item :label="$t('message.comment')" prop="comment">
          <el-input type="textarea" v-model="return_form.comment"></el-input>
        </el-form-item>
        <div class="modal__foterbtu">
          <el-button @click="submitReturnTransaction()" type="primary">{{ $t('message.save') }}</el-button>
          <el-button @click="dialogTableVisible = false">{{ $t('message.close') }}</el-button>
        </div>
      </el-form>
    </el-dialog>

    <el-dialog :title="$t('message.Upload file for create transaction')" :visible.sync="dialogUploadFile" v-loading="loadUploadFile">
        <el-upload
            class="upload-demo"
            ref="upload_transaction_file"
            action="/api/transactions/create/transaction/from/file"
            :headers="upload_headers"
            accept=" .xls, .xlsx"
            :auto-upload="false"
            :multiple="false"
            :limit="1"
            :before-upload="beforeUpload"
            :on-error="uploadError"
            :on-success="uploadSuccess">
            <el-button slot="trigger" size="small" type="primary">{{ $t("message.Select file") }}</el-button>
            <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUploadFile()">{{ $t("message.save") }}</el-button>
            <div class="el-upload__tip" slot="tip">xls/xlsx</div>
        </el-upload>
    </el-dialog>
  </div>
</template>
<script>
import incoming from "./components/crm-create-incoming";
import incomingUpdate from "./components/crm-update-incoming";
import paymentSystems from "@inventory/crm-payment-system-select";
import transactionStates from "@inventory/crm-transaction-state-select";
import districts from "@inventory/crm-district-select";
import clients from "@inventory/crm-client-select";
import { mapActions, mapGetters } from "vuex";
import { i18n } from '@/utils/modules/i18n';
import list from "@/utils/mixins/list";

export default {
  mixins: [list],
  data() {
    return {
      incomingPaymentCreate: false,
      incomingPaymentUpdate: false,
      dialogTableVisible: false,
      dialogUploadFile: false,
      loadReturnTransaction: false,
      loadUploadFile: false,
      return_form: {
        comment: ""
      },
      upload_file_form: {
        file: ""
      },
      upload_headers: {
          Authorization: "Bearer " + this.$store.getters.token
      }
    };
  },
  components: { incoming, incomingUpdate, paymentSystems, transactionStates, districts, clients },
  computed: {
    ...mapGetters({
      list: "transactions/list",
      columns: "transactions/columns",
      pagination: "transactions/pagination",
      filter: "transactions/filter",
      sort: "transactions/sort",
      payment_systems: "transactions/payment_systems",
      transaction_states: "transactions/transaction_states",
      transaction_amounts: "transactions/transaction_amounts",
    }),
    actions: function() {
      return ["edit"];
    }
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
      updateSort: "transactions/updateSort",
      updateFilter: "transactions/updateFilter",
      updateColumn: "transactions/updateColumn",
      updateList: "transactions/index",
      updatePagination: "transactions/updatePagination",
      empty: "transactions/empty",
      delete: "transactions/destroy",
      refreshData: "transactions/refreshData",
      multiDelete: "transactions/multiDelete",
      saveReturnTransaction: "transactions/saveReturnTransaction"
    }),
    edit(model) {
      this.selectedItem = model;
      this.incomingPaymentUpdate = true;
    },
    openDialogReturnTransaction(model) {
      this.selectedItem = model;
      this.return_form.comment = "";
      this.dialogTableVisible = true;
    },
    openDialogUploadFile() {
        if( this.$refs.upload_transaction_file ) {
            this.$refs.upload_transaction_file.uploadFiles = []
        }

        this.upload_file_form.file = "";
        this.dialogUploadFile = true;
    },
    submitReturnTransaction() {
      this.$refs["return_form"].validate(valid => {
        if (valid) {
          this.loadReturnTransaction = true;
          this.saveReturnTransaction({
            transaction_id: this.selectedItem.id,
            comment: this.return_form.comment
          })
            .then(res => {
              this.$alert(res);
              this.loadReturnTransaction = false;
              this.dialogTableVisible = false;
              this.fetchData();
            })
            .catch(err => {
              this.$alert(err);
              this.loadReturnTransaction = false;
            });
        }
      });
    },
    submitUploadFile() {
        this.$refs.upload_transaction_file.submit();
    },
    beforeUpload(){
        this.loadUploadFile = true
    },
    uploadSuccess(response, file, fileList){
        this.loadUploadFile = false;
        this.dialogUploadFile = false;
        this.fetchData();
        this.$notify({
            title: 'Success',
            message: 'Файл успешно загружен',
            type: 'success'
        });
    },
    uploadError(err, file, fileList){
        this.loadUploadFile = false
        //this.dialogUploadFile = false
        this.$notify({
            title: 'Error',
            message: 'Не могу загрузить файл',
            type: 'error'
        });
    },

    controlExcelData() {
      this.excel_fields = {};
      for (let key in this.columns) {
        if (this.columns.hasOwnProperty(key)) {
          let column = this.columns[key];
          if (column.show && column.column !== "settings") {
            switch (column.column) {
                case 'payment_system':
                    this.excel_fields[column.title] = this.excel_fields[column.title] = {
                        field: 'payment_system',
                        callback: (value) => {
                            return this.payment_systems[value];
                        }
                    };
                    break;
                case 'transactionable_type':
                    this.excel_fields[column.title] = this.excel_fields[column.title] = {
                        field: 'transactionable_type',
                        callback: (value) => {
                            return (value == 'applications') ? i18n.t('message.application') : value;
                        }
                    };
                    break;
                case 'state':
                    this.excel_fields[column.title] = this.excel_fields[column.title] = {
                        field: 'state',
                        callback: (value) => {
                            return this.transaction_states[value];
                        }
                    };
                    break;
                default:
                    this.excel_fields[column.title] = column.column;
                    break;
            }
          }
        }
      }
    },
    getRowClass(transaction = null) {
        let rowClass = '';

        if( transaction ) {
            switch(transaction.state) {
                case 1:
                    rowClass = 'divr_status_warning';
                    break;
                case 2:
                    rowClass = 'divr_status_succes';
                    break;
                case -1:
                    rowClass = 'divr_status_danger';
                    break;
                case -2:
                    rowClass = 'divr_status_danger';
                    break;
            }
        }

        return rowClass;
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
<style lang="scss">
.itme_pelap {
  display: flex;
  background-color: #fff;
  height: 28px;
  padding: 3px 10px;
  border-radius: 5px;
  margin: 10px 10px;
  img {
    width: 48px;
    margin-right: 10px;
  }
  div {
    color: #000;
  }
}
.itme_pelap {
  .iconstyle {
    width: 20px;
    height: 20px;
    transition: 0.7s;
    display: inline-block;
    margin-top: 0px !important;
  }

  .iconitme_blocks_pages4 {
    -webkit-mask: url(/images/logos/money.svg);
    mask: url(/images/logos/money.svg);
    background-color: #92c819;
    -webkit-mask-size: cover;
    mask-size: cover;
    margin-right: 10px;
  }
}

.itme_pelap_select {
  display: flex;
  height: 28px;
  margin: 10px 10px;
  div {
    color: #000;
  }
}
</style>
