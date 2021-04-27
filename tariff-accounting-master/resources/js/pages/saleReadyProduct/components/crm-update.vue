<template>
  <el-col :span="24">
    <header id="el-drawer__title" class="el-drawer__header">
      <span>{{ $t('message.edit') }} {{ $t('message.sale_ready_product') | lowerFirst }}</span>
      <el-button
        v-can="['saleReadyProducts.update']"
        type="primary"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit()"
      >{{ $t('message.save_and_exit') }}</el-button>
      <el-button
        type="warning"
        @click="close()"
        icon="el-icon-close"
        size="small"
      >{{ $t('message.close') }}</el-button>
    </header>
    <el-main
      v-loading="waiting"
      :element-loading-text="$t('message.loading')"
      element-loading-spinner="el-icon-loading"
    >
      <el-col :span="24">
        <el-form ref="form" :model="form" :rules="rules" label-width="100px" label-position="right">
          <el-card class="box-card pb-1 crm-card-pt-1">
            <el-col>
              <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                <span class="document-title">{{ form.number }}</span>
                <template slot="label">
                  <span
                    class="document-title"
                  >{{ $t('message.sale_ready_product') }} {{ $t('message.n') }}</span>
                </template>
              </el-form-item>
              <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                <el-date-picker
                  prefix-icon="el-icon-date"
                  v-model="form.datetime"
                  type="datetime"
                  :format="date_time_format"
                  :value-format="date_time_format"
                ></el-date-picker>
                <template slot="label">
                  <span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
                </template>
              </el-form-item>
              <span class="el-dropdown-link float-right p-4">
                <span class="text-secondary">{{ $t('message.owner') }}:</span>
                <span>{{ auth_name }}</span>
              </span>
            </el-col>
            <el-col :span="8">
              <clients
                v-model="form.client_id"
                :client_id="form.client_id"
                :label="$t('message.client')"
              ></clients>
            </el-col>
            <el-col :span="8">
              <contracts
                v-model="form.contract_client_id"
                :client_id="form.client_id"
                :contract_id="form.contract_client_id"
              ></contracts>
            </el-col>
            <el-col :span="8">
              <states v-model="form.state_id" :state_id="form.state_id"></states>
            </el-col>
          </el-card>
        </el-form>
      </el-col>
      <!-- end col -->
      <el-col :span="24" class="mt-2">
        <el-card class="box-card pb-3">
          <el-table
            v-loading="loading"
            :data="[...old_items,...items]"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading"
            size="medium"
            style="width: 100%"
            :row-class-name="tableRowClassName"
          >
            <el-table-column :label="$t('message.name')">
              <template slot-scope="item">
                <b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.available')">
              <template slot-scope="item">{{ item.row.remainder | formatNumber }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.quantity')">
              <template slot-scope="item">
                <template v-if="item.row.id">{{ item.row.quantity | formatNumber }}</template>
                <template v-else>
                  <el-input-number
                    v-model="item.row.quantity"
                    size="mini"
                    :min="0"
                    :max="item.row.remainder"
                  ></el-input-number>
                </template>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.measurement')">
              <template
                slot-scope="item"
              >{{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.selling_price')">
              <template slot-scope="item">
                <template
                  v-if="item.row.id"
                >{{ item.row.selling_price | formatNumber }} {{ item.currency ? item.currency.symbol : ''}}</template>
                <template v-else>
                  <product-price
                    v-model="item.row.selling_price"
                    :old="item.row.selling_price"
                    size="mini"
                  ></product-price>
                </template>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.currency')">
              <template slot-scope="item">
                <template
                  v-if="item.row.id"
                >{{ (item.row.currency ? item.row.currency.name : '') | truncate}}</template>
                <template v-else>
                  <currencies
                    size="mini"
                    v-model="item.row.currency_id"
                    :currency_id="item.row.currency_id"
                    @c-change="updateCurrency(item.row)"
                  ></currencies>
                </template>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.rate')">
              <template slot-scope="item">
                <template v-if="item.row.id">
                  <crm-rate :row="item.row"></crm-rate>
                </template>
                <template v-else>
                  <el-input
                    :hidden="item.row.currency && item.row.currency.active"
                    type="number"
                    v-model="item.row.rate"
                    size="mini"
                  ></el-input>
                </template>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.total_amount')">
              <template
                slot-scope="item"
              >{{ (item.row.quantity * item.row.selling_price) | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol : '' }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.delete')">
              <template slot-scope="item">
                <el-button
                  @click="item.row.id ? destoryProduct(item.row) : removeProduct(item.row)"
                  type="danger"
                  icon="el-icon-delete"
                  size="mini"
                  circle
                ></el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-col :span="12" class="mt-1">
            <products @append="appendProduct" :plc="$t('message.product_select_plc')"></products>
          </el-col>
          <el-col :span="6" :offset="2" class="p-2">
            <h5 class="float-left font-weight-bold">{{ $t('message.total') }}:</h5>
            <h5 class="float-right font-weight-bold">{{ totalAmount | formatMoney(2) }}</h5>
          </el-col>
        </el-card>
      </el-col>
      <!-- end col -->
    </el-main>
  </el-col>
</template>
<script>
import sale_ready_product_form from "@/utils/mixins/models/sale_ready_product_form";
import drawer from "@/utils/mixins/includes/drawer";
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [sale_ready_product_form, drawer],
  props: ["sale"],
  methods: {
    ...mapActions({
      save: "saleReadyProducts/update",
      show: "saleReadyProducts/show",
      deleteProduct: "saleReadyProducts/deleteProduct"
    }),
    load() {
      if (!this.waiting && this.sale) {
        this.waiting = true;
        this.show(this.sale.id)
          .then(res => {
            this.form = this.getForm;
            this.waitingStop();
          })
          .catch(err => {
            this.waitingStop();
            this.$alert(err);
          });
      }
    },
    afterOpen() {
      this.form = this.getForm;
      this.items = [];
      this.load();
    },
    afterLeave() {
      this.empty();
    },
    destoryProduct(line) {
      if (_.isNumber(line.id)) {
        this.$confirm(
          this.$t("message.delete_confirm"),
          this.$t("message.confirm"),
          {
            confirmButtonText: this.$t("message.yes"),
            cancelButtonText: this.$t("message.cancel"),
            type: "warning"
          }
        )
          .then(() => {
            this.waiting = true;
            this.deleteProduct({ id: line.id })
              .then(res => {
                this.listChanged();
                this.waiting = false;
                this.load();
                this.$alert(res);
              })
              .catch(err => {
                this.waitingStop();
                this.$alert(err);
              });
          })
          .catch(() => {});
      }
    }
  }
};
</script>
