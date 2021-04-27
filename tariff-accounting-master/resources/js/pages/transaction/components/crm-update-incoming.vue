<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.edit') }} {{ $t('message.Incoming payment') | lowerFirst }}</span>
			<el-button v-can="['transactions.update']" type="success" size="small" class="mr-1" :loading="waiting"
				@click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['transactions.update']" type="primary" size="small" class="mr-1" :loading="waiting"
				@click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main class="pt-2" v-loading="loading">
            <el-card class="box-card crm-card-pt-1">
                <el-form ref="form" :model="form" :rules="rules" :label-position="'right'" label-width="100px">
                    <el-col>
                        <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                            <span  class="document-title"> {{ form.id }} </span>
                            <template slot="label">
                                <span class="document-title">{{ $t('message.transaction') }} {{ $t('message.n') }}</span>
                            </template>
                        </el-form-item>
                        <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                            <el-date-picker prefix-icon="el-icon-date" v-model="form.datetime" type="datetime"
                                :format="date_time_format" :value-format="date_time_format">
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
                        <clients v-model="form.transactionable_id" :client_id="form.transactionable_id" prop="transactionable_id"></clients>
                        <payment-types v-model="form.payment_type_id" :payment_type_id="form.payment_type_id"></payment-types>
                        <contracts v-model="form.contractable_id" :contract_id="form.contractable_id" :client_id="form.transactionable_id"></contracts>
                    </el-col>
                    <el-col :span="7">
                        <el-form-item :label="columns.amount.title" prop="amount" size="small">
                            <amount v-model="form.amount" :old="form.amount" size="small"></amount>
                        </el-form-item>
                        <el-form-item :label="columns.currency_id.title" prop="currency_id"  size="small">
                            <currencies v-model="form.currency_id" :currency_id="form.currency_id" @c-change="updateCurrency()"></currencies>
                        </el-form-item>
                        <el-form-item v-if="form.currency && !form.currency.active" :label="columns.rate.title" prop="rate" size="small">
                            <el-input v-model="form.rate" type="number"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8" :offset="1">
                        <scores v-model="form.score_id" :score_id="form.score_id" :currency_id="form.currency_id"></scores>
                        <el-form-item :label="columns.comment.title" size="small" prop="comment">
                            <el-input type="textarea" size="mini" v-model="form.comment"
                                :placeholder="columns.comment.title" clearable  :rows="2"></el-input>
                        </el-form-item>
                    </el-col>
                </el-form>
            </el-card>
            <el-card class="mt-2">
                <el-row>
                    <el-button @click="includeDrawer = true" type="primary" size="medium"> {{ $t('message.Bind Payment') }} </el-button>
                </el-row>
                <el-table
                    :row-class-name="tableRowClassName"
                    :data="relatedItems"
                    size="small"
                    style="width: 100%" class="crm-el-table">
                        <template slot="empty"><span></span></template>
                        <el-table-column
                            width="180"
                            :label="$t('message.type_document')">
                            <template slot-scope="item">
                                {{ $t('message.' + item.row.paymentable_type) | capitalize }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            width="60"
                            :label="$t('message.n')">
                            <template slot-scope="item">
                                {{ item.row.paymentable_id }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.datetime')">
                            <template slot-scope="item">
                                {{ item.row.datetime }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.client')">
                            <template slot-scope="item">
                                {{ item.row.client ? item.row.client.name : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.contract')">
                            <template slot-scope="item">
                                {{ item.row.contract ? $t('message.n') + ' ' +  item.row.contract.number + ' ' + $t('message.from') + ' ' + item.row.contract.begin_date : ''}}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.status')">
                            <template slot-scope="item">
                                {{ item.row.state ? item.row.state.state : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.total_amount')">
                            <template slot-scope="item">
                                {{ item.row.total_amount | formatMoney(2) }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.paid')">
                            <template slot-scope="item">
                                {{ (item.row.paid_amount + item.row.paying_amount) | formatMoney(2) }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            width="200"
                            :label="$t('message.Paid from this payment')">
                            <template slot-scope="item">
                                <amount v-model="item.row.paying_amount" :old="item.row.paying_amount" size="mini"></amount>
                            </template>
                        </el-table-column>
                        <el-table-column
                            width="80"
                            :label="$t('message.delete')">
                            <template slot-scope="item">
                                    <el-button type="danger" icon="el-icon-delete" circle size="mini"
                                    @click.native.prevent="deleteRow(item.$index, relatedItems)"></el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-row class="mt-2">
                        <el-col :span="8" :offset="14">
                            <h5 class="float-left font-weight-bold">{{ $t('message.Tied') }}: </h5>
                            <h5 class="float-right font-weight-bold">{{ relatedAmount | formatMoney(2) }}</h5>
                        </el-col>
                        <el-col :span="8" :offset="14">
                            <h5 class="float-left font-weight-bold">{{ $t('message.Not tied') }}: </h5>
                            <h5 class="float-right font-weight-bold" :class="{'text-danger': (realAmount - relatedAmount < 0)}">{{ (realAmount - relatedAmount) | formatMoney(2) }}</h5>
                        </el-col>
                    </el-row>
            </el-card>
        </el-main>
        <el-drawer size="90%" :with-header="false" :append-to-body="true" :visible.sync="includeDrawer" ref="includeDrawer" @closed="drawerClosed('includeDrawerChild')"  @opened="drawerOpened('includeDrawerChild')">
            <sales drawer="includeDrawer" ref="includeDrawerChild" :client_id="form.transactionable_id" :amount="realAmount"></sales>
        </el-drawer>
    </el-row>
</template>

<script>
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';
    import { mapGetters, mapActions } from 'vuex';
    import incoming from '@/utils/mixins/models/incoming-transaction';

    export default {
           props:['transaction'],
           mixins:[form,drawer,incoming],
           computed: {
                ...mapGetters({
                    payments: 'transactions/payments'
                })
           },
           methods: {
                ...mapActions({
                    save: 'transactions/update',
                    edit: 'transactions/show',
                }),
                afterOpen(){
                    this.form = this.getForm;
                    this.relatedItems = [];
                    this.load();
                },
                load(){
                    if (!this.loading && this.transaction) {
                        this.changeLoading(true);
                        this.edit(this.transaction.id)
                        .then(res =>{
                            this.changeLoading(false);
                            this.form = this.getForm;
                            this.setPaymentsToRelatedItems();
                        })
                        .catch(err => {
                            this.changeLoading(false);
                            this.$alert(err);
                        })
                    }
                },
                afterLeave(){
                   this.$store.commit('transactions/SET_RELATED_ITEMS',[]);
                },
                setPaymentsToRelatedItems(){
                    this.payments.forEach(payment => {
                        let item = {};
                        item.paymentable_type = payment.paymentable_type;
                        item.paymentable_id = payment.paymentable_id;
                        item.datetime = payment.paymentable ? payment.paymentable.datetime : '';
                        item.contract = payment.paymentable ? payment.paymentable.contract : null;
                        item.client = payment.paymentable ? payment.paymentable.client : null;
                        item.state = payment.paymentable ? payment.paymentable.state : null;
                        item.total_amount = payment.paymentable ? payment.paymentable.total_amount : 0;
                        item.paying_amount = payment.amount;
                        item.paid_amount = payment.paymentable ? payment.paymentable.paid_amount : 0;
                        item.paid_amount = item.paid_amount - item.paying_amount;
                        this.relatedItems.push( item );
                    });
                }
           }
    }
</script>