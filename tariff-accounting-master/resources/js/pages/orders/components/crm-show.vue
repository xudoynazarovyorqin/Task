<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span>{{ $t('message.details') }} {{ $t('message.order') | lowerFirst }}  № {{ order.id }} <small class="ml-5"><el-badge class="item mr-4" :value="order_products.length" type="success"><i class="el-icon-shopping-cart-2"></i></el-badge> <b>{{ order.amount | formatMoney }}</b></small> </span>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading" class="p-1">
            <div class="col-12">
                <el-tabs v-model="activeTab" @tab-click="handleTabClick">
                    <el-tab-pane :label="$t('message.about_off_order')" name="main">
                        <el-card shadow="never">
                            <div class="row invoice-info">
                                <div class="col-sm-6 invoice-col">
                                    {{ $t('message.client')}}
                                    <address>
                                        <strong>{{ (order.client) ? order.client.name : '' }}</strong><br>
                                        {{ $t('message.phone') }}: {{ (order.client) ? order.client.phone : '' }}<br>
                                        {{ $t('message.email') }}: {{ (order.client) ? order.client.email : '' }} <br>
                                        {{ $t('message.actual_address')}}: {{ (order.client) ? order.client.actual_address : '' }}
                                    </address>
                                </div>
                                <div class="col-sm-6 invoice-col">
                                    <b>{{ $t('message.order')}}  №{{ order.id }}</b><br>
                                    <br>
                                    <b>{{ $t('message.contract')}}:</b>
                                    <template v-if="order.contract_client">
                                    № {{ order.contract_client.number }} от  {{ order.contract_client.begin_date }}
                                </template>
                                    <template v-else>
                                    {{ $t('message.not_contract')}}
                                </template>
                                    <br>
                                    <b>{{ $t('message.paid')}}:</b> {{ order.paid | formatMoney }}<br>
                                </div>
                            </div>
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
                                                {{ item.row.quantity | formatNumber(2) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.ready')">
                                            <template slot-scope="item">
                                                {{ item.row.ready | formatNumber(2) }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.measurement')">
                                            <template slot-scope="item">
                                                {{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.selling_price')">
                                            <template slot-scope="item">
                                                {{ item.row.price | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol: '' }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.total_amount')">
                                            <template slot-scope="item">
                                                {{ (item.row.quantity * item.row.price) | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}
                                            </template>
                                        </el-table-column>
                                    </el-table>
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
                                                {{ item.row.quantity | formatNumber }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.measurement')">
                                            <template slot-scope="item">
                                                {{ item.row.material ? item.row.material.measurement ? item.row.material.measurement.name : '' : '' }}
                                                {{ item.row.material | addMeasurement(item.row.quantity)}}
                                            </template>
                                        </el-table-column>
                                    </el-table>
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
                                                {{ item.row.amount | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol : ''}}
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                </el-tab-pane>
                            </el-tabs>
                        </el-card>
                        <crm-audit :created_audit="created_audit"></crm-audit>
                    </el-tab-pane>
                    <el-tab-pane :label="$t('message.expense_rate')" name="crm-report">
                        <crm-assembly-report v-if="order.production_type == 'assembly'" ref="crm-assembly-report" :costs="order_costs" :is_order="true" :order_id="order.id" :assembly_id="null"></crm-assembly-report>
                        <crm-sale-report v-if="order.production_type == 'production'" ref="crm-sale-report" :costs="order_costs" :is_order="true" :order_id="order.id" :sale_id="null"></crm-sale-report>
                    </el-tab-pane>
                    <el-tab-pane :label="$t('message.employees')" name="fourth">
                        <crm-employee :old_employee_groups="employeeGroups" :is_edit="false" :is_show="true"></crm-employee>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </el-main>
    </div>
</template>

<script>
    import  CrmAssemblyReport from '@/includes/report/crm-assembly-show-report';
    import  CrmSaleReport from '@/includes/report/crm-sale-show-report';
    import  CrmAudit from "@/includes/crm-audit";
    import  CrmEmployee from '@/includes/crm-employee';
    import { formatNumber, formatMoney } from '@/filters';
    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins: [drawer],
        props:['open','model'],
        components:{CrmAssemblyReport,CrmEmployee,CrmSaleReport,CrmAudit},
        data(){
            return {
                activeTab: 'main',
                reportChanged: true,
            }
        },
        computed:{
            ...mapGetters({
                order: 'orders/model',
                order_products: 'orders/order_products',
                order_costs: 'orders/order_costs',
                additional_materials: 'orders/additional_materials',
                employeeGroups: 'orders/employeeGroups',
                created_audit: 'orders/created_audit'
            })
        },
        methods:{
            ...mapActions({
                getModel: 'orders/show'
            }),
            afterOpen(){
                this.activeTab = 'main';
                this.reportChanged = true;
                this.loadModel()
            },
            async handleTabClick(tab, event) {
                if (tab.name == 'crm-report' && this.reportChanged) {
                    if (this.order.production_type == 'production') {
                        this.$refs['crm-sale-report'].loadData()
                    }
                    if (this.order.production_type == 'assembly') {
                        this.$refs['crm-assembly-report'].loadData()
                    }
                    this.reportChanged = false
                }
            },
            loadModel(){
                if (!this.loading) {
                    this.changeLoading(true);
                    this.getModel(this.model.id)
                        .then(res => {
                            this.changeLoading()
                        })
                        .catch(err => {
                            this.changeLoading()
                        })
                }
            },
            afterLeave(){
                /**
                 * Write code here is run after drawer closed.
                 */
            }
        }
    }
</script>