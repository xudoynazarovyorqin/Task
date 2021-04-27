<template>
  <el-col :span="24">
    <header id="el-drawer__title" class="el-drawer__header">
      <span>{{ $t('message.new') }} {{ $t('message.sale_ready_product') | lowerFirst }}</span>
      <el-button
        v-can="['saleReadyProducts.create']"
        type="primary"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit(true)"
      >{{ $t('message.save_and_exit') }}</el-button>
      <el-button
        type="warning"
        @click="close()"
        icon="el-icon-close"
        size="small"
      >{{ $t('message.close') }}</el-button>
    </header>
    <el-main>
      <el-form ref="form" :model="form" :rules="rules" class="style__label" label-position="right">
        <el-card class="box-card pb-1 crm-card-pt-1 mb-4">
          <el-row>
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
          </el-row>
        </el-card>

        <el-row :gutter="20">
          <el-col :span="12">
            <el-card class="box-card clent_calabs">
              <el-collapse v-model="activeNames" @change="handleChange">
                <el-collapse-item title="Объект" name="1">
                  <el-form-item class="mb-1" label="Наименование">
                    <el-input v-model="form.name1" size="small"></el-input>
                  </el-form-item>
                  <!-- end el-form-item-->

                  <el-form-item class="mb-1" label="н/п">
                    <el-input v-model="form.name2" size="small"></el-input>
                  </el-form-item>
                  <!-- end el-form-item-->

                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="улица">
                        <el-input v-model="form.name3" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Дом">
                        <el-input v-model="form.name4" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" label="к">
                        <el-input v-model="form.name5" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="кв">
                        <el-input v-model="form.name6" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                  </el-row>
                </el-collapse-item>
              </el-collapse>
            </el-card>
          </el-col>
          <!-- end col -->

          <el-col :span="12">
            <el-card class="box-card clent_calabs">
              <el-collapse v-model="activeNames" @change="handleChange">
                <el-collapse-item title="Характеристика объекта" name="1">
                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Подъезд">
                        <el-input v-model="form.name7" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Этаж">
                        <el-input v-model="form.name8" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Комната">
                        <el-input v-model="form.name9" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Окна всего">
                        <el-input v-model="form.name11" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Фасад">
                        <el-input v-model="form.name12" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Торец">
                        <el-input v-model="form.name13" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Тil">
                        <el-input v-model="form.name14" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Балкон">
                        <el-select
                          v-model="form.region15"
                          placeholder=".."
                          size="small"
                          class="w-100"
                        >
                          <el-option label="Zone one" value="shanghai"></el-option>
                          <el-option label="Zone two" value="beijing"></el-option>
                        </el-select>
                      </el-form-item>
                      <!-- end el-form-item-->
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" label="Тип настраения">
                        <el-select
                          v-model="form.region16"
                          placeholder=".."
                          size="small"
                          class="w-100"
                        >
                          <el-option label="Zone one" value="shanghai"></el-option>
                          <el-option label="Zone two" value="beijing"></el-option>
                        </el-select>
                      </el-form-item>
                      <!-- end el-form-item-->
                      <!-- end el-form-item-->
                    </el-col>
                  </el-row>
                </el-collapse-item>
              </el-collapse>
            </el-card>
          </el-col>
          <!-- end col -->

          <el-col :span="24">
            <el-card class="box-card clent_calabs">
              <el-collapse v-model="activeNames" @change="handleChange">
                <el-collapse-item title="Тип Объект" name="1">
                  <!-- end el-form-item-->

                  <el-row :gutter="20">
                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Тип Объект">
                        <el-input v-model="form.name17" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <!-- end col -->

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Категория Объект">
                        <el-select
                          v-model="form.region18"
                          placeholder="..."
                          size="small"
                          class="w-100"
                        >
                          <el-option label="Zone one" value="shanghai"></el-option>
                          <el-option label="Zone two" value="beijing"></el-option>
                        </el-select>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <!-- end col -->

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Принял на хранение">
                        <el-select
                          v-model="form.region19"
                          placeholder=".."
                          size="small"
                          class="w-100"
                        >
                          <el-option label="Zone one" value="shanghai"></el-option>
                          <el-option label="Zone two" value="beijing"></el-option>
                        </el-select>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <!-- end col -->

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Код подъезда">
                        <el-input v-model="form.name20" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Код двери лестничной клетки">
                        <el-input v-model="form.name21" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Лечение от обект">
                        <el-input v-model="form.name22" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Пароль">
                        <el-input v-model="form.name23" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Лечение от подъезда">
                        <el-input v-model="form.name24" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>

                    <el-col :span="8">
                      <el-form-item class="mb-1" label="Код">
                        <el-input v-model="form.name25" size="small"></el-input>
                      </el-form-item>
                      <!-- end el-form-item-->
                    </el-col>
                  </el-row>
                </el-collapse-item>
              </el-collapse>
            </el-card>
          </el-col>
          <!-- end col -->
        </el-row>
        <!-- end rl-row -->
      </el-form>

      <el-col :span="24" class="mt-2">
        <el-card class="box-card pb-3">
          <el-table
            v-loading="loading"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading"
            size="medium"
            :data="items"
            style="width: 100%"
            :row-class-name="tableRowClassName"
            class="crm-el-table"
          >
            <template slot="empty">
              <span></span>
            </template>
            <el-table-column :label="$t('message.name')">
              <template slot-scope="item">
                <b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.available') + '/' + $t('message.bron')">
              <template
                slot-scope="item"
              >{{ item.row.remainder | formatNumber }} / {{ item.row.booked | formatNumber }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.quantity')">
              <template slot-scope="item">
                <el-input-number
                  v-model="item.row.quantity"
                  size="mini"
                  :min="0"
                  :max="item.row.remainder"
                ></el-input-number>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.measurement')">
              <template
                slot-scope="item"
              >{{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.selling_price')">
              <template slot-scope="item">
                <product-price
                  v-model="item.row.selling_price"
                  :old="item.row.selling_price"
                  size="mini"
                ></product-price>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.currency')">
              <template slot-scope="item">
                <currencies
                  size="mini"
                  v-model="item.row.currency_id"
                  :currency_id="item.row.currency_id"
                  @c-change="updateCurrency(item.row)"
                ></currencies>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.rate')">
              <template slot-scope="item">
                <el-input
                  :hidden="item.row.currency && item.row.currency.active"
                  type="number"
                  v-model="item.row.rate"
                  size="mini"
                ></el-input>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.total_amount')">
              <template
                slot-scope="item"
              >{{ (item.row.quantity * item.row.selling_price) | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}</template>
            </el-table-column>
            <el-table-column :label="$t('message.delete')">
              <template slot-scope="item">
                <el-button
                  @click="removeProduct(item.row)"
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
    </el-main>
  </el-col>
</template>
<script>
import sale_ready_product_form from "@/utils/mixins/models/sale_ready_product_form";
import drawer from "@/utils/mixins/includes/drawer";
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [sale_ready_product_form, drawer],
  methods: {
    ...mapActions({
      save: "saleReadyProducts/store",
      getLastId: "saleReadyProducts/getLastId"
    }),
    afterOpen() {
      this.form = this.getForm;
      this.items = [];
      if (!this.last_id) {
        this.getLastId().then(res => {
          this.form.number = res.last_id;
        });
      } else {
        this.form.number = this.last_id;
      }
    },
    afterLeave() {
      this.empty();
    }
  },
  data() {
    return {
      activeNames: ["1"]
    };
  }
};
</script>
