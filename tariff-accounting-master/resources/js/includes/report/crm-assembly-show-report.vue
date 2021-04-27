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
                        <el-table
                            size="small"
                            :data="report_products"
                            style="width: 100%">
                            <el-table-column type="expand">
                            <template slot-scope="item">
                                <el-table
                                    size="small"
                                    :data="item.row.data"
                                    style="width: 100%">
                                    <el-table-column
                                        :label="$t('message.warehouse')">
                                        <template slot-scope="item">
                                            {{ (item.row.warehouse ? item.row.warehouse.name : '') | truncate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column 
                                        :label="$t('message.Shipped')">
                                        <template slot-scope="item">
                                            {{ item.row.quantity| formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                        :label="$t('message.price')">
                                        <template slot-scope="props">
                                            {{ props.row.price | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                        :label="$t('message.total_amount')">
                                        <template slot-scope="props">
                                            {{ (props.row.price * props.row.quantity) | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                                        </template>
                                    </el-table-column>
                                </el-table>          
                            </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.name')">
                                <template slot-scope="item">
                                    {{ (item.row.product ? item.row.product.name : '') | truncate }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.quantity')">
                                <template slot-scope="item">
                                    {{ item.row.total| formatNumber }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.Shipped')">
                                <template slot-scope="item">
                                    {{ item.row.shipped| formatNumber }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.price')">
                                <template slot-scope="item">
                                    {{ item.row.price| formatMoney }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.total_amount')">
                                <template slot-scope="item">
                                    {{ item.row.total_amount| formatMoney }}
                                </template>
                            </el-table-column>
                        </el-table>    
                    </el-col>
                </el-row>
                <el-row class="mt-2">
                    <el-col :span="24" class="text-center">
                        <span>{{ $t('message.cost_material')}}</span>
                    </el-col>
                    <el-col :span="24">
                        <el-table
                            size="small"
                            :data="report_materials"
                            style="width: 100%">
                            <el-table-column type="expand">
                            <template slot-scope="item">
                                <el-table
                                    size="small"
                                    :data="item.row.data"
                                    style="width: 100%">
                                    <el-table-column
                                        :label="$t('message.warehouse')">
                                        <template slot-scope="item">
                                            {{ (item.row.warehouse ? item.row.warehouse.name : '') | truncate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column 
                                        :label="$t('message.Shipped')">
                                        <template slot-scope="item">
                                            {{ item.row.quantity| formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                        :label="$t('message.price')">
                                        <template slot-scope="props">
                                            {{ props.row.price | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column
                                        :label="$t('message.total_amount')">
                                        <template slot-scope="props">
                                            {{ (props.row.price * props.row.quantity) | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                                        </template>
                                    </el-table-column>
                                </el-table>          
                            </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.name')">
                                <template slot-scope="item">
                                    {{ (item.row.material ? item.row.material.name : '') | truncate }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.quantity')">
                                <template slot-scope="item">
                                    {{ item.row.total| formatNumber }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.Shipped')">
                                <template slot-scope="item">
                                    {{ item.row.shipped| formatNumber }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.price')">
                                <template slot-scope="item">
                                    {{ item.row.price| formatMoney }}
                                </template>
                            </el-table-column>
                            <el-table-column
                            :label="$t('message.total_amount')">
                                <template slot-scope="item">
                                    {{ item.row.total_amount| formatMoney }}
                                </template>
                            </el-table-column>
                        </el-table>    
                    </el-col>
                </el-row>
                <el-row class="mt-2">
                    <el-col :span="24" class="text-center">
                        <span>{{ $t('message.adm_and_add_cost')}}</span>
                    </el-col>
                    <el-col :span="24">
                        <el-table
                            size="small"
                            :data="costs"
                        >
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
            costs: {
                type: Array,
                default: []
            },
            is_order:{
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
                return _.sumBy(this.report_materials, 'total_amount')
            },
            totalAmountOfProducts: function() {
                return _.sumBy(this.report_products, 'total_amount')
            },
            totalAmountOfCosts: function() {
                return _.sumBy(this.costs, function (o) {
                    let rate = o.currency ? (o.currency.reverse ? _.round(1 / o.rate, 8) : o.rate) : 1;
                    return _.round((+o.amount * +rate), 2);
                });
            },
        },
        methods: {
            ...mapActions({
                loadReport: 'assembly/reportShow'
            }),
            loadData(){
                    const req = {
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