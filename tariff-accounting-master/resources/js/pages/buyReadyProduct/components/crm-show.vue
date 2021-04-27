<template>
    <section class="invoice" v-loading="loading" id="print">
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    <i class="el-icon-goods"></i> {{ $t('message.buy_products')}}.
                    <small class="float-right">{{ $t('message.date')}}: {{ model.created_at }}</small>
                </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                {{ $t('message.provider')}}
                <address>
                    <strong>{{ (model.provider) ? model.provider.name : '' }}</strong><br>
                    {{ $t('message.phone') }}: {{ (model.provider) ? model.provider.phone : '' }}<br>
                    {{ $t('message.email') }}: {{ (model.provider) ? model.provider.email : '' }} <br>
                    {{ $t('message.actual_address')}}: {{ (model.provider) ? model.provider.actual_address : '' }}
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
                <b>{{ $t('message.buy_ready_product')}} #{{model.id}}</b><br>
                <br>
                <b>{{ $t('message.contract')}}:</b>
                <template v-if="model.contract_provider">
                    № {{ model.contract_provider.number }} от  {{ model.contract_provider.begin_date }}
                </template>
                <template v-else>
                    {{ $t('message.not_contract')}}
                </template>
                <br>
                <b>{{ $t('message.paid')}}:</b> {{ model.paid_price | formatMoney }}<br>
                <b>{{ $t('message.date_receive')}}:</b> {{ model.date }}<br>
            </div>
        </div>
        <div class="row">
            <hr>
            <div class="col-12 table-responsive">
                <table class="table">
                    <thead>
                        <tr class="crm-table-header-border-0">
                            <td>{{ $t('message.name') }}</td>
                            <td>{{ $t('message.quantity') }}</td>
                            <td>{{ $t('message.measurements') }}</td>
                            <td>{{ $t('message.buy_price') }}</td>
                            <td>{{ $t('message.total_amount') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(buy_product,index) in buy_products" :key="index">
                            <td>{{ buy_product.product ? buy_product.product.name : '' }}</td>
                            <td>{{ buy_product.qty_weight | formatNumber }}</td>
                            <td>{{ buy_product.product ? buy_product.product.measurement ? buy_product.product.measurement.name : '' : '' }}</td>
                            <td>{{ buy_product.price | formatNumber }} {{ (buy_product.currency) ? buy_product.currency.symbol : '' }}</td>
                            <td>{{ (buy_product.qty_weight * buy_product.price) | formatNumber }} {{ (buy_product.currency) ? buy_product.currency.symbol : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <el-row>
            <el-col :span="9" :offset="15">
                <el-col :span="10">
                    <h4 class="font-weight-bold">{{ $t('message.total')}}:</h4>
                </el-col>
                <el-col :span="14">
                    <h4 class="font-weight-bold">{{ buy.total_price | formatMoney}}</h4>
                </el-col>
            </el-col>
        </el-row>
        <div class="row no-print">
            <div class="col-12">
                <el-button @click="print('print')" type="primary" size="small" plain icon="el-icon-printer">{{ $t('message.print')}}</el-button>
            </div>
        </div>
    </section>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import drawer from "@/utils/mixins/includes/drawer";

    export default {
        mixins: [drawer],
        props:['buy', 'open'],
        data() {
            return {
            };
        },
        watch: {
            open: {
                handler: function(newValue,oldValue) {
                    if (newValue === true) {
                        this.loadModel();
                    }
                },
                immediate: true,
                deep: true
            },
        },
        computed:{
            ...mapGetters({
                model: 'buyReadyProducts/model',
                buy_products: 'buyReadyProducts/buy_products',
                statuses: 'buyReadyProducts/statuses',
            }),
        },
        methods:{
            ...mapActions({
                showBuy: "buyReadyProducts/show",
            }),
            loadModel(){
                if (!this.loading && this.buy) {
                    this.changeLoading(true);
                    this.showBuy(this.buy.id)
                    .then(res => {this.changeLoading();})
                    .catch(err => {this.changeLoading();})
                }
            }
        }
    }
</script>
