<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.details') }} {{ $t('message.sale') | lowerFirst }}</span>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <div class="row pl-3 pr-3" v-loading="loading">
            <div class="col-12">
                <el-tabs v-model="activeTab" @tab-click="handleTabClick">
                    <el-tab-pane label="Информация о заказе" name="main">
                        <div class="row p-0 m-0">
                            <div class="col-12 pl-0">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="el-icon-shopping-cart-full"></i> {{ $t('message.sales') }} № {{ sale.id }}
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>{{ columns.owner.title }}</b>
                                                        <template v-if="sale.owner == 'client'"> {{ $t('message.client') }}</template>
                                                        <template v-else> {{ $t('message.firm') }}</template>
                                                    </li>
                                                    <li class="list-group-item"><b>{{ columns.state_id.title }}</b> {{ (sale.state) ? sale.state.state : ''}}</li>
                                                    <li class="list-group-item"><b>Дата от: </b> {{ sale.begin_date }} <b> до: </b> {{ sale.end_date}} </li>
                                                </ul>
                                            </div>
                                            <div class="col-6">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item"><b>{{ columns.created_at.title }}</b> {{ sale.created_at }}</li>
                                                    <li class="list-group-item"><b>{{ columns.priority_id.title }}</b> {{ (sale.priority) ? sale.priority.name : ''}}</li>
                                                    <li class="list-group-item"><b>{{ columns.saleable_type.title }}: </b>
                                                        <template v-if="sale.saleable_type == 'orders'"> {{ $t('message.orders') }}</template>
                                                        <template v-if="sale.saleable_type == 'assemblies'"> {{ $t('message.assemblies') }} </template>
                                                        <template v-if="sale.saleable_type == null"> {{ $t('message.for_warehouse') }}</template>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <el-card class="mt-2" shadow="never">
                            <el-tabs>
                                <el-tab-pane>
                                    <span slot="label">
                                        <i class="el-icon-s-goods"></i> {{ $t('message.products')}}
                                    </span>
                                    <el-table size="medium" :data="sale_products" style="width: 100%" class="crm-el-table" :row-class-name="tableRowClassName">
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
                                                {{ item.row.quantity | formatNumber }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.ready')">
                                            <template slot-scope="item">
                                                {{ item.row.ready | formatNumber }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.defect')">
                                            <template slot-scope="item">
                                                {{ item.row.defect_count | formatNumber }}
                                            </template>
                                        </el-table-column>
                                        <el-table-column :label="$t('message.measurement')">
                                            <template slot-scope="item">
                                                {{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
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
                            </el-tabs>
                        </el-card>
                        <crm-history></crm-history>
                    </el-tab-pane>
                    <el-tab-pane :label="$t('message.expense_rate')" name="crm-report">
                        <crm-sale-report ref="crm-sale-report" :products="[]" :costs="[]" :additional_materials="[]" :is_order="false" :is_edit="true" :order_id="null" :sale_id="sale.id"></crm-sale-report>
                    </el-tab-pane>
                    <el-tab-pane :label="$t('message.employees')" name="employee">
                        <crm-employee :old_employee_groups="oldEmployeeGroups" :is_edit="false" :is_show="true"></crm-employee>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="col-12 mt-2">
            </div>
        </div>
    </div>
</template>

<script>
    import CrmHistory from './include/crm-history';
    import CrmSaleReport from '@/includes/report/crm-sale-show-report';
    import CrmEmployee from '@/includes/crm-employee';
    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins: [drawer],
   		props:['open','model'],
        components:{CrmHistory,CrmSaleReport,CrmEmployee},
        data(){
            return {
                activeTab: 'main',
                reportChanged: true,
            }
        },
        computed:{
            ...mapGetters({
                columns: 'sales/columns',
                sale: 'sales/model',
                sale_products: 'sales/sale_products',
                additional_materials: 'sales/additional_materials',
		        oldEmployeeGroups: 'sales/employeeGroups',
            })
        },
        methods:{
            ...mapActions({
                getModel: 'sales/show'
            }),
            afterOpen(){
                this.activeTab = 'main';
                this.load()
            },
            handleTabClick(tab, event) {
                if (tab.name == 'crm-report' && this.reportChanged) {
                    this.$refs['crm-sale-report'].loadData()
                    this.reportChanged = false
                }
            },
            load(){
                if (!this.loading) {
                    this.changeLoading(true);
                    this.reportChanged = true;
                    this.getModel(this.model.id)
                        .then(res => {this.changeLoading()})
                        .catch(err => {this.changeLoading()})
                }
            },
            tableRowClassName({row, rowIndex}) {
                if (row.quantity == row.ready) {
                    return 'success-row';
                } else {
                    return 'warning-row';
                }
                return '';
            }
        }
    }
</script>
<style lang="scss">
    b{
        font-weight: bold !important;
    }
</style>