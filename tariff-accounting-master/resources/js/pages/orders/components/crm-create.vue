<template>
<div>
    <header id="el-drawer__title" class="el-drawer__header">
        <span>
            {{ $t('message.new') }} {{ $t('message.order') | lowerFirst }} <small class="ml-5">
                <el-badge class="item mr-4" :value="order_products.length" type="success"><i
                        class="el-icon-shopping-cart-2"></i></el-badge> <b>{{ totalAmount | formatMoney(2) }}</b>
            </small>
        </span>
        <el-button v-can="['orders.create']" type="success" size="small" class="mr-1" :loading="waiting" @click="submit(false)"> {{ $t('message.save') }}</el-button>
        <el-button v-can="['orders.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
        <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
        </el-button>
    </header>
    <el-main class="pt-1">
        <el-tabs v-model="activeTab" @tab-click="handleTabClick">
            <el-tab-pane :label="$t('message.about_off_order')" name="main">
                <el-card shadow="never" class="crm-card-pt-1">
                    <el-form ref="form" :model="form" :rules="rules" label-width="100px">
                        <el-col>
                            <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                                <span  class="document-title"> {{ form.number }} </span>
                                <template slot="label">
                                    <span class="document-title">{{ $t('message.order') }} {{ $t('message.n') }}</span>
                                </template>
                            </el-form-item>
                            <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                                <el-date-picker  prefix-icon="el-icon-date" v-model="form.datetime" type="datetime" :format="date_time_format" :value-format="date_time_format">
                                </el-date-picker>
                                <template slot="label">
                                    <span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
                                </template>
                            </el-form-item>
                            <el-form-item label-width="0" size="small" class="d-inline-flex ml-2">
                                <el-switch v-model="form.production_type" active-value="assembly" inactive-value="production" :active-text="$t('message.assembly')" :inactive-text="$t('message.sale')" active-color="#13ce66" inactive-color="#2132F0" @change="controlDate()"></el-switch>
                            </el-form-item>
                            <span class="el-dropdown-link float-right p-4">
                                <span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
                            </span>
                        </el-col>
                        <el-col :span="8">
                            <clients v-model="form.client_id" :client_id="form.client_id" :label="$t('message.client')"></clients>
                            <contracts v-model="form.contract_client_id" :client_id="form.client_id" :contract_id="form.contract_client_id"></contracts>
                        </el-col>
                        <el-col :span="8">
                            <states v-model="form.state_id" :state_id="form.state_id"></states>
                            <priorities v-model="form.priority_id" :priority_id="form.priority_id"></priorities>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item :label="columns.begin_date.title" size="small">
                                <el-date-picker v-model="form.begin_date" @change="controlDate()" type="date" :placeholder="columns.begin_date.title" :format="date_format" :value-format="date_format"></el-date-picker>
                            </el-form-item>
                            <el-form-item :label="columns.end_date.title" size="small">
                                <el-date-picker v-model="form.end_date" @change="controlDate()" type="date" :placeholder="columns.end_date.title" :format="date_format" :value-format="date_format">></el-date-picker>
                            </el-form-item>
                        </el-col>
                    </el-form>
                </el-card>
                <el-card shadow="never" class="mt-2">
                    <el-tabs>
                        <el-tab-pane>
                            <span slot="label">
                                <i class="el-icon-s-goods"></i> {{ $t('message.products')}}
                            </span>
                            <el-table size="medium" :data="order_products" style="width: 100%" class="crm-el-table">
                                <template slot="empty">
                                    <span></span>
                                </template>
                                <el-table-column :label="$t('message.name')" :min-width="150">
                                    <template slot-scope="item">
                                        <b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.quantity')">
                                    <template slot-scope="item">
                                        <el-input v-model="item.row.quantity" type="number" size="mini"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.measurement')">
                                    <template slot-scope="item">
                                        {{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.selling_price')">
                                    <template slot-scope="item">
                                        <product-price v-model="item.row.price" :old="item.row.price" size="mini"></product-price>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.currency')">
                                    <template slot-scope="item">
                                        <currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.rate')">
                                    <template slot-scope="item">
                                        <el-input :hidden="item.row.currency && item.row.currency.active" type="number" v-model="item.row.rate" size="mini"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.total_amount')">
                                    <template slot-scope="item">
                                        {{ (item.row.quantity * item.row.price) | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.delete')">
                                    <template slot-scope="item">
                                        <el-button @click="removeProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-col :span="12" class="mt-1">
                                <products @append="appendProduct" :plc="$t('message.product_select_plc')" :filter="{production_type: form.production_type, production: 1}"></products>
                            </el-col>
                            <el-col :span="6" :offset="2" class="p-2">
                                <h5 class="float-left font-weight-bold">{{ $t('message.total') }}: </h5>
                                <h5 class="float-right font-weight-bold">{{ totalAmount | formatMoney(2) }}</h5>
                            </el-col>
                        </el-tab-pane>
                        <el-tab-pane>
                            <span slot="label">
                                <i class="el-icon-circle-plus-outline"></i> {{ $t('message.additional_materials')}}
                            </span>
                            <el-table size="medium" :data="additional_materials" style="width: 100%" class="crm-el-table">
                                <template slot="empty">
                                    <span></span>
                                </template>
                                <el-table-column :label="$t('message.name')">
                                    <template slot-scope="item">
                                        <b>{{ (item.row.material ? item.row.material.name : '') | truncate }}</b>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.quantity')">
                                    <template slot-scope="item">
                                        <el-input type="number" v-model="item.row.quantity" size="mini"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.measurement')">
                                    <template slot-scope="item">
                                        {{ item.row.material ? item.row.material.measurement ? item.row.material.measurement.name : '' : '' }}
                                        {{ item.row.material | addMeasurement(item.row.quantity)}}
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.delete')">
                                    <template slot-scope="item">
                                        <el-button @click="removeAdditionalMaterial(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-col :span="12" class="mt-1">
                                <materials @append="appendAdditionalMaterial" :plc="$t('message.product_select_plc')"></materials>
                            </el-col>
                        </el-tab-pane>
                        <el-tab-pane>
                            <span slot="label">
                                <i class="el-icon-s-shop"></i> {{ $t('message.additional_costs')}}
                            </span>
                            <el-table size="medium" :data="order_costs" style="width: 100%" class="crm-el-table">
                                <template slot="empty">
                                    <span></span>
                                </template>
                                <el-table-column :label="$t('message.name')">
                                    <template slot-scope="item">
                                        <b>{{ (item.row.cost ? item.row.cost.name : '') | truncate }}</b>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.amount')">
                                    <template slot-scope="item">
                                        <cost-price v-model="item.row.amount" :old="item.row.amount" size="mini"></cost-price>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.currency')">
                                    <template slot-scope="item">
                                        <currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.rate')">
                                    <template slot-scope="item">
                                        <el-input :hidden="item.row.currency && item.row.currency.active" type="number" v-model="item.row.rate" size="mini"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column :label="$t('message.delete')">
                                    <template slot-scope="item">
                                        <el-button @click="removeCost(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-col :span="12" class="mt-1">
                                <costs @append="appendCost" :plc="$t('message.cost')"></costs>
                            </el-col>
                        </el-tab-pane>
                    </el-tabs>
                </el-card>
            </el-tab-pane>
            <el-tab-pane :label="$t('message.expense_rate')" name="crm-report">
                <crm-assembly-report v-if="form.production_type == 'assembly'" ref="crm-assembly-report" :products="order_products" :costs="order_costs" :additional_materials="additional_materials" :is_order="true" :is_edit="false" :order_id="null" :assembly_id="null"></crm-assembly-report>
                <crm-sale-report v-if="form.production_type == 'production'" ref="crm-sale-report" :products="order_products" :costs="order_costs" :additional_materials="additional_materials" :is_order="true" :is_edit="false" :order_id="null" :sale_id="null"></crm-sale-report>
            </el-tab-pane>
            <el-tab-pane :label="$t('message.employees')" name="fourth">
                <crm-employee :old_employee_groups="[]" :is_edit="false" @crm-change="changeEmployee"></crm-employee>
            </el-tab-pane>
        </el-tabs>
    </el-main>
</div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';
    import order from '@/utils/mixins/models/order';

    export default {
      mixins:[form,order,drawer],
      data() {
        return {
          is_new: true,
        }
      },
      methods: {
        ...mapActions({
            save: "orders/store",
            getLastId: 'orders/getLastId'
        }),
        controlDate() {
          this.dateIsEmpty({
            begin_date: this.form.begin_date,
            end_date: this.form.end_date,
            production_type: this.form.production_type,
            checkOrder: true
          });
        },
        afterOpen(){
            this.form = this.getForm;
            this.order_products = [];
            this.additional_materials = [];
            this.order_costs = [];
            if (!this.lastId) {
                this.getLastId().then(res => { this.form.number = res.last_id})
            }else{
                this.form.number = this.lastId;
            }
        },
        afterLeave(){
            /**
            * Write code here is run after drawer closed.
            */
        }
      },
    };
</script>
