<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.edit') }} {{ $t('message.buy_ready_product') | lowerFirst }}  </span>
            <el-button v-can="['buyReadyProducts.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading">
            <el-col :span="24">
                <el-card class="box-card crm-card-pt-1">
                    <el-form ref="form" :model="form" :rules="rules" label-width="120px">
                        <el-col>
                            <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                                <span  class="document-title"> {{ form.number }} </span>
                                <template slot="label">
                                    <span class="document-title">{{ $t('message.buy_ready_product') }} {{ $t('message.n') }}</span>
                                </template>
                            </el-form-item>
                            <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                                <el-date-picker  prefix-icon="el-icon-date" v-model="form.datetime" type="datetime" :format="date_time_format" :value-format="date_time_format">
                                </el-date-picker>
                                <template slot="label">
                                    <span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
                                </template>
                            </el-form-item>
                            <span class="el-dropdown-link float-right p-4">
                                <span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
                            </span>
                        </el-col>
                        <el-col :span="8">
                            <providers v-model="form.provider_id" :provider_id="form.provider_id"></providers>
                            <contracts v-model="form.contract_provider_id" :contract_id="form.contract_provider_id" :provider_id="form.provider_id"></contracts>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item :label="columns.date.title" size="small">
                                <el-date-picker v-model="form.date" type="date" style="width: 100%;" :placeholder="columns.date.title" :value-format="date_format"></el-date-picker>
                            </el-form-item>
                            <statues v-model="form.status_id" :status_id="form.status_id"></statues>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item :label="columns.comment.title" size="small" prop="comment">
                                <el-input type="textarea" size="mini" v-model="form.comment" :placeholder="columns.comment.title" clearable></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.is_warehouse.title" size="small" prop="is_warehouse">
                                <el-select v-model="form.is_warehouse" filterable clearable class="d-block" disabled>
                                    <el-option :label="$t('message.yes')" :value="true"></el-option>
                                    <el-option :label="$t('message.no')" :value="false"></el-option>
                                </el-select>
                            </el-form-item>
                            <template v-if="!form.is_warehouse">
                                <el-form-item :label="columns.object_type.title" size="small" prop="object_type">
                                    <el-select v-model="form.object_type" :placeholder="$t('message.choose')" disabled filterable clearable >
                                        <el-option :label="$t('message.assembly')" :value="'assemblies'"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item :label="columns.object_id.title" size="small" prop="object_id">
                                    <el-input readonly size="mini" v-model="form.object_id" style="width: 100%;" :placeholder="columns.object_id.title" clearable></el-input>
                                </el-form-item>
                            </template>
                        </el-col>
                    </el-form>
                </el-card>
            </el-col>
            <el-col :span="24" class="mt-2">
                <el-card class="box-card pb-1">
                    <el-table size="medium" :data="[...old_items,...items]" style="width: 100%" class="crm-el-table">
                        <template slot="empty">
                            <span></span>
                        </template>
                        <el-table-column :label="$t('message.name')">
                            <template slot-scope="item">
                                <b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.quantity')">
                            <template slot-scope="item">
								<template v-if="item.row.id">
									{{ item.row.qty_weight | formatNumber }}
								</template>
								<template v-else>
	                                <el-input type="number" v-model="item.row.qty_weight" size="mini"></el-input>
								</template>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.measurement')">
                            <template slot-scope="item">
                                {{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.buy_price')">
                            <template slot-scope="item">
                                <template v-if="item.row.id">
                                    <span>{{ item.row.price | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}</span>
                                </template>
                                <template v-else>
                                    <product-price v-model="item.row.price" :old="item.row.price" size="mini"></product-price>
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.currency')">
                            <template slot-scope="item">
                                <template v-if="item.row.id">
                                    {{ item.row.currency ? item.row.currency.name : '' }}
                                </template>
                                <template v-else>
                                    <currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.rate')">
                            <template slot-scope="item">
                                <template v-if="item.row.id">
                                    <crm-rate :row="item.row"></crm-rate>
                                </template>
                                <template v-else>
                                    <el-input :hidden="item.row.currency && item.row.currency.active" type="number" v-model="item.row.rate" size="mini"></el-input>
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.total_amount')">
                            <template slot-scope="item">
                                {{ (item.row.qty_weight * item.row.price) | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : ''}}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.delete')">
                            <template slot-scope="item">
                                <el-button @click="item.row.id ? deleteOldProduct(item.row.id) : removeProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-col :span="12" class="mt-1">
                        <products @append="appendProduct" :plc="$t('message.product_select_plc')"></products>
                    </el-col>
                    <el-col :span="11" :offset="1" class="mt-2">
                        <h4 class="font-weight-bold"> {{ $t('message.total')}}: {{ totalAmount | formatMoney(2) }}</h4>
                    </el-col>
                    <el-col :span="11" :offset="13" class="mt-1">
                        <template>
                            <el-col :span="12" class="mt-1" v-for="(item, index) in amounts" :key="index + 'total_sum_in_currency'">
                                {{ (item.currency) ? item.currency.name : '' }}: {{ item.amount | formatNumber }} {{ (item.currency) ? item.currency.symbol : ''  }}
                            </el-col>
                        </template>
                    </el-col>
                </el-card>
            </el-col>
        </el-main>
    </el-col>
</template>
<script>
    import buy_ready_product_form from "@/utils/mixins/models/buy_ready_product_form";
    import { mapGetters, mapActions } from "vuex";
    import drawer from "@/utils/mixins/includes/drawer";

    export default {
		mixins: [buy_ready_product_form,drawer],
		props: ['buy'],
        methods: {
            ...mapActions({
                showBuy: "buyReadyProducts/show",
                save: 'buyReadyProducts/update',
                deleteItem: 'buyReadyProducts/deleteProduct',
            }),
            afterOpen() {
                this.form = this.getForm;
                this.items = [];
                this.load();
            },
            afterLeave(){
            },
            load(){
                if (!this.loading && this.buy) {
                    this.changeLoading(true)
                    this.showBuy(this.buy.id)
                      .then(res => {
                            this.form = this.getForm;
                            this.changeLoading()
                      })
                      .catch(err => {
                            this.changeLoading()
                      })
                  }
            },
            deleteOldProduct(id){
                this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                    confirmButtonText: this.$t('message.yes'),
                    cancelButtonText: this.$t('message.cancel'),
                    type: 'warning'
                }).then(() => {
                    this.deleteItem({buy_product_id: id})
                        .then(res=> {
                            this.load();
                            this.listChanged();
                            this.$alert(res);
                        })
                        .catch(err => {
                          this.$alert(err)
                        })
                }).catch(() => {});
            },
        },
    }
</script>
