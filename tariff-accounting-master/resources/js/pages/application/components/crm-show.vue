<template>
    <section class="invoice" v-loading="loading" :element-loading-text="$t('message.loading')"
		element-loading-spinner="el-icon-loading" id="print">
        <div class="row">
            <div class="col-12">
                <h2 class="page-header">
                    <i class="el-icon-goods"></i> <small>{{ $t('message.sale_ready_product')}} № {{ model.id }} {{ $t('message.from') | lowerFirst }} {{ model.datetime }}.</small>
                </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
                {{ $t('message.client')}}
                <address>
                    <strong>{{ (model.client) ? model.client.name : '' }}</strong><br>
                    {{ $t('message.phone') }}: {{ (model.client) ? model.client.phone : '' }}<br>
                    {{ $t('message.email') }}: {{ (model.client) ? model.client.email : '' }} <br>
                    {{ $t('message.actual_address')}}: {{ (model.client) ? model.client.actual_address : '' }}
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
                <b>{{ $t('message.sale_ready_product')}}  №{{model.id}}</b><br>
                <br>
                <b>{{ $t('message.contract')}}:</b>
                <template v-if="model.contract_client">
                    № {{ model.contract_client.number }} от  {{ model.contract_client.begin_date }}
                </template>
                <template v-else>
                    {{ $t('message.not_contract')}}
                </template>
                <br>
                <b>{{ $t('message.paid')}}:</b> {{ model.payed_sum | formatMoney }}<br>
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
                            <td>{{ $t('message.measurement') }}</td>
                            <td>{{ $t('message.selling_price') }}</td>
                            <td>{{ $t('message.total_amount') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in items" :key="index">
                            <td>{{ item.product ? item.product.name : '' }}</td>
                            <td>{{ item.quantity | formatNumber }}</td>
                            <td>{{ item.product ? item.product.measurement ? item.product.measurement.name : '' : '' }}</td>
                            <td>{{ item.selling_price | formatNumber(2) }} {{ item.currency ? item.currency.symbol : ''}}</td>
                            <td>{{ (item.quantity * item.selling_price) | formatNumber }} {{ item.currency ? item.currency.symbol : ''}}</td>
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
                    <h4 class="font-weight-bold">{{ model.total_price | formatMoney(2) }}</h4>
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
        props:['sale', 'open'],
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
                model: 'saleReadyProducts/model',
                items: 'saleReadyProducts/items',
            }),
        },
        methods:{
            ...mapActions({
                show: "saleReadyProducts/show",
            }),
            loadModel(){
                if (!this.loading && this.sale) {
                    this.changeLoading(true);
                    this.show(this.sale.id)
                    .then(res => {this.changeLoading();})
                    .catch(err => {this.changeLoading();})
                }
            }
        }
    }
</script>
