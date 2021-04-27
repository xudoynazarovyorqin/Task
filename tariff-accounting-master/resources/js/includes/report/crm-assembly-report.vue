<template>
    <div>
        <el-col :span="24"  id="reportProduction" v-loading="loadingReport">
            <el-card class="box-card mt-2" shadow="never">
                <div slot="header" class="clearfix">
                    <b>{{ $t('message.total_amount') }}:</b> <strong> {{ (totalAmountOfProducts + totalAmountOfMaterials + totalAmountOfCosts ) | formatMoney(2) }}</strong>
                    <el-button style="float: right" size="mini" type="primary" icon="el-icon-document el-icon-left" @click="print('reportProduction')">  {{ $t('message.print') }}</el-button>
                </div>
                <el-row>
                    <el-col :span="24" class="text-center">
                        <span>{{ $t('message.semi_product_cost') }}</span>
                    </el-col>
                    <el-col :span="24">
                        <table class="table">
                            <thead>
                                <tr class="crm-table-header-border-0">
                                    <td>#</td>
                                    <td>{{ $t('message.name') }}</td>
                                    <td>{{ $t('message.quantity') }}</td>
                                    <td>{{ $t('message.buy_price') }}</td>
                                    <td>{{ $t('message.rate') }}</td>
                                    <td>{{ $t('message.warehouse') }} </td>
                                    <td>{{ $t('message.Sum') }}  </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in report_products" :key="'report_material' + index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ (item.product) ? item.product.name : '' }}</td>
                                    <td>{{ item.quantity | formatNumber(3) }} {{ (item.product) ? (item.product.measurement) ? item.product.measurement.name : '' : '' }}</td>
                                    <template v-if="item.status">
                                        <td>{{ item.price | formatNumber(2) }} {{ item.currency ? item.currency.symbol : '' }}</td>
                                        <td><crm-rate :row="item"></crm-rate></td>
                                        <td>{{ (item.warehouse) ? item.warehouse.name : '' }}</td>
                                        <td>{{ (+item.quantity * +item.price) | formatNumber(2)  }} {{ item.currency ? item.currency.symbol : '' }}</td>
                                    </template>
                                    <template v-else>
                                        <td :colspan="4" class="text-center"><span class="text-danger">{{ item.message }}</span></td>
                                    </template>
                                </tr>
                                <tr>
                                    <td :colspan="5"></td>
                                    <td><b>Итого: </b></td>
                                    <td><b> {{ totalAmountOfProducts | formatMoney(2) }}</b>  </td>
                                </tr>
                            </tbody>
                        </table>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="24" class="text-center">
                        <span>{{ $t('message.cost_material')}}</span>
                    </el-col>
                    <el-col :span="24">
                        <table class="table">
                            <thead>
                                <tr class="crm-table-header-border-0">
                                    <td>#</td>
                                    <td>{{ $t('message.name') }}</td>
                                    <td>{{ $t('message.quantity') }}</td>
                                    <td>{{ $t('message.buy_price') }} </td>
                                    <td>{{ $t('message.rate') }}</td>
                                    <td>{{ $t('message.warehouse') }} </td>
                                    <td>{{ $t('message.Sum') }} </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in report_materials" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ (item.material) ? item.material.name : '' }}</td>
                                    <td>
                                        {{ item.quantity | formatNumber(3) }} {{ item.material ? item.material.measurement ? item.material.measurement.name : '': '' }}
                                        {{ item.material | addMeasurement(item.quantity) }}
                                    </td>
                                    <template v-if="item.status">
                                        <td>{{ item.price | formatNumber(2) }} {{ item.currency ? item.currency.symbol : '' }}</td>
                                        <td><crm-rate :row="item"></crm-rate></td>
                                        <td>{{ (item.warehouse) ? item.warehouse.name : '' }}</td>
                                        <td>{{ (+item.quantity * +item.price) | formatNumber(2)  }} {{ item.currency ? item.currency.symbol : '' }}</td>
                                    </template>
                                    <template v-else>
                                        <td :colspan="4" class="text-center"><span class="text-danger">{{ item.message }}</span></td>
                                    </template>
                                </tr>
                                <tr>
                                    <td :colspan="5"></td>
                                    <td><b>{{ $t('message.total') }}: </b></td>
                                    <td><strong> {{ totalAmountOfMaterials | formatMoney(2) }}</strong>  </td>
                                </tr>
                            </tbody>
                        </table>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="24" class="text-center">
                        <span>{{ $t('message.adm_and_add_cost')}}</span>
                    </el-col>
                    <el-col :span="24">
                        <el-table :data="costs">
                            <el-table-column
                                type="index"
                                width="50">
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.name')"
                            >
                                <template slot-scope="item">
                                    {{ item.row.cost ? item.row.cost.name : '' }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.Sum')"
                            >
                                <template slot-scope="item">
                                   {{  item.row.amount | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol : ''}}
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-col>
                </el-row>
            </el-card>
        </el-col>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    export default {
        props:{
            products:{
                type: Array,
                default: []
            },
            additional_materials:{
                type: Array,
                default: []
            },
            costs: {
                type: Array,
                default: []
            },
            is_order:{
                type: Boolean,
                default: false
            },
            is_edit:{
                type: Boolean,
                default: false
            },
            order_id: {
                type: Number,
                default: null,
            },
            assembly_id:{
                type: Number,
                default: null,
            }
        },
        data() {
            return {
                report_products: [],
                report_materials: [],
                loadingReport: false,
            }
        },
        computed: {
            totalAmountOfMaterials: function() {
                return _.sumBy(this.report_materials, function(o) {
                    let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                    return o.status ? (+o.price * +o.quantity * +rate) : 0;
                })
            },
            totalAmountOfCosts: function() {
                return _.sumBy(this.costs, function (o) {
                    let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                    return _.round((+o.amount * +rate), 2);
                });
            },
            totalAmountOfProducts: function() {
                return _.sumBy(this.report_products, function(o) {
                    let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                    return o.status ? (+o.price * +o.quantity * +rate) : 0;
                });
            }
        },
        methods: {
            ...mapActions({
                loadReport: 'assembly/loadReport'
            }),
            loadData(){
                    const req = {
                        products: this.products.map((item) => {return {product_id: item.product.id, quantity: item.quantity}}),
                        additional_materials: this.additional_materials.map((item) => {return {material_id: item.material.id, quantity:item.quantity}}),
                        assembly_id: this.assembly_id,
                        order_id: this.order_id,
                    };
                    this.loadingReport = true;
                    this.report_materials = [];
                    this.report_products = [];
                    this.loadReport(req)
                        .then(res => {
                                this.loadingReport = false;
                                let data = res.data.result.data;
                                data.report_materials.forEach(item => {
                                    item.rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                                    this.report_materials.push(item);
                                });
                                data.report_products.forEach(item => {
                                    item.rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
                                    this.report_products.push(item);
                                });
                        })
                        .catch(err => {
                            this.loadingReport = false;
                        })
            },
            print(){
                const req = {
                    products: this.products.map((item) => {return {product_id: item.product.id, quantity: item.quantity}}),
                    additional_materials: this.additional_materials.map((item) => {return {material_id: item.material.id, quantity:item.quantity}}),
                    assembly_id: this.assembly_id,
                    order_id: this.order_id,
                    for_print: true,
                };
                this.loadReport(req)
                    .then(res => {
                        const WinPrint = window.open("", "", "left=0,top=0,toolbar=0,scrollbars=0,status=0");
                        WinPrint.document.write(res.data);
                        WinPrint.document.close();
                        WinPrint.focus();
                        WinPrint.print();
                        WinPrint.close();
                    })
                    .catch(err => {
                    })                
            }
        },
    }
</script>