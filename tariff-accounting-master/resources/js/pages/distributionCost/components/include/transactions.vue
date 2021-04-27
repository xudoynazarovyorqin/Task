<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.Bind Cost transactions') }}</span>
			<el-button type="primary" size="small" class="mr-1" :loading="loading" @click="submit()"> {{ $t('message.Snap') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main class="p-2" v-loading="loading">
            <el-card shadow="never" class="p-0 m-0 border-0">
                <el-col :span="24">
                    <el-col :span="4">
                        <el-date-picker prefix-icon="el-icon-date" v-model="from_date" type="date" size="small" :format="date_format" :value-format="date_format" :placeholder="$t('message.from_date')"></el-date-picker>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <el-date-picker prefix-icon="el-icon-date" v-model="to_date" type="date" size="small" :format="date_format" :value-format="date_format" :placeholder="$t('message.to_date')"></el-date-picker>
                    </el-col>
                    <el-col :span="6">
                        <el-button @click="loadTransactions()" icon="el-icon-search" type="primary" size="small"> {{ $t('message.Search') }} </el-button>
                    </el-col>
                </el-col>
                <el-col :span="24" class="mt-2">
                    <p>{{ $t('message.Select the transactions you want to distribute')}}</p>
                    <p>{{ $t('message.Total amount costs') }}: {{ totalTransactionsAmount | formatMoney(2) }}</p>
                </el-col>

                <el-col v-if="is_edit" :span="24" class="mt-2">
                    <el-collapse v-model="activeNames">
                      <el-collapse-item name="1">
                        <template slot="title">
                            {{ $t('message.history') }} ({{ totalOldTransactionsAmount | formatMoney(2) }})
                        </template>
                        <el-table
                        :data="old_transactions"
                        size="small"                        
                        style="width: 100%">
                            <el-table-column
                                :label="$t('message.cost')">
                                <template slot-scope="item">
                                    {{ (item.row.transaction) ? item.row.transaction.transaction ? item.row.transaction.transaction.name : '' : '' }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.datetime')">
                                <template slot-scope="item">
                                    {{  (item.row.transaction) ? item.row.transaction.datetime : '' }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.distribution_amount')">
                                <template slot-scope="item">
                                    {{ item.row.price | formatMoney(2) }}                                     
                                </template>
                            </el-table-column>
                        </el-table>
                      </el-collapse-item>
                    </el-collapse>
                </el-col>
            </el-card>
            <el-card shadow="never" class="p-0 m-0 border-0">
                <el-row>
                    <el-col :span="24">
                        <el-table
                        ref="multipleTable"
                        :data="items"
                        size="small"
                        :row-class-name="tableRowClassName"
                        @selection-change="handleSelectionChange"
                        style="width: 100%">
                            <el-table-column
                                type="selection"
                                width="55">
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.cost')">
                                <template slot-scope="item">
                                    {{ (item.row.transactionable) ? item.row.transactionable.name : '' }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.datetime')">
                                <template slot-scope="item">
                                    {{ item.row.datetime }}
                                </template>
                            </el-table-column>
                            <el-table-column
                                :label="$t('message.amount')">
                                <template slot-scope="item">
                                    {{ item.row.real_amount | formatMoney(2) }} 
                                    <template v-if="item.row.rate != 1">
                                        ({{ item.row.amount }} {{ (item.row.currency) ? item.row.currency.symbol : '' }})
                                    </template>
                                </template>
                            </el-table-column>                            
                            <el-table-column
                                :label="$t('message.distributioning_amount')">
                                <template slot-scope="item">
                                    <amount v-model="item.row.distributioning_amount" :old="item.row.distributioning_amount" size="mini"></amount>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-col>
                </el-row>
            </el-card>
        </el-main>
    </el-row>
</template>
<script>
    import drawer from '@/utils/mixins/includes/drawer';
    import amount from '@inputs/crm-amount-input';
  	import { mapGetters, mapActions } from 'vuex';

    export default {
        mixins:[drawer],
        props: {
            is_edit: {
                type: Boolean,
                default: false,
            },
            distribution_cost_id: {
                type: Number,
                default: null,
            },
        },
        components: { amount },
        data() {
               return {
                    from_date: '',
                    to_date: '',
                    items: [],                    
                    multipleSelection: [],
                    activeNames: [],
               }
        },
        computed: {
            ...mapGetters({
                old_transactions: 'distributionCosts/old_transactions',
            }),
            totalTransactionsAmount: function() {
                return _.sumBy(this.multipleSelection, 'distributioning_amount')
            },
            totalOldTransactionsAmount: function() {
                return _.sumBy(this.old_transactions, 'distributioning_amount')
            },
        },        
        methods: {
            ...mapActions({
                getCostTransactions:'distributionCosts/getCostTransactions'
            }),
            submit(){
                if (_.find(this.multipleSelection, function(o) { return ((_.round(+o.max_distributioning_amount, 2) < _.round(+o.distributioning_amount, 2)) || (+o.distributioning_amount) <= 0) })) {
                    this.$message({
                        message: this.$t('message.distributioning_amount_validation_error'),
                        type: 'warning'
                    });
                    return false;
                }
                else {
                    this.$store.commit('distributionCosts/SET_TRANSACTIONS',this.multipleSelection);
                    this.close();
                }

            },
            afterOpen(){
                
            },
            loadTransactions(){
                if( this.from_date && this.to_date ) {
                    this.changeLoading(true);
                    let filter = {};
                    if( this.is_edit ) {
                        
                        filter = {
                            from_date: this.from_date,
                            to_date: this.to_date,
                            is_edit: true,
                            distribution_cost_id: this.distribution_cost_id,
                        }
                    }
                    else {
                        filter = {
                            from_date: this.from_date,
                            to_date: this.to_date,
                            is_edit: false,
                        }
                    }
                    this.getCostTransactions(filter)
                    .then(res => {
                        this.items = [];
                        if( res.data && res.data.cost_transactions) {
                            res.data.cost_transactions.forEach(cost_transaction => {
                                cost_transaction.distributioning_amount = (+cost_transaction.real_amount) - (+cost_transaction.distribution_amount);
                                cost_transaction.max_distributioning_amount = (+cost_transaction.real_amount) - (+cost_transaction.distribution_amount);
                                this.items.push(cost_transaction);
                            });
                        }
                        this.changeLoading(false)
                    }).catch(err => {
                        this.items = [];
                        this.changeLoading(false)
                        this.$alert(err)
                    })
                }
                else {
                    this.$message({
                        message: this.$t('message.please_choose_period'),
                        type: 'warning'
                    });
                    return false;
                }
            },
            tableRowClassName({ row, rowIndex }) {
                if( _.round(+row.max_distributioning_amount, 2) < _.round(+row.distributioning_amount, 2) ) {
                    return 'warning-row';
                }
                return '';
            },
            parent() {
                return this.$parent;
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            }
        }
    }
</script>