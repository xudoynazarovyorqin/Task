<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.edit') }} {{ $t('message.Outgoing payment') | lowerFirst }}</span>
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
                        <el-form-item :label="columns.transactionable_id.title" prop="amount" size="small">
                             <el-select v-model="form.transactionable_id" :placeholder="$t('message.cost')" class="d-block">
                                <el-option v-for="item in costs" :key="item.id" :label="item.name" :value="item.id"></el-option>
                            </el-select>
                        </el-form-item>
                        <payment-types v-model="form.payment_type_id" :payment_type_id="form.payment_type_id"></payment-types>
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
        </el-main>
    </el-row>
</template>

<script>
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';
    import cost from '@/utils/mixins/models/cost-transaction';
    import { mapGetters, mapActions } from 'vuex';

    export default {
           props:['transaction'],
           mixins:[form,drawer,cost],
           methods: {
                ...mapActions({
                    edit: 'transactions/show',
                    save: 'transactions/update',
                }),
                afterOpen(){
                    this.form = this.getForm;
                    this.load();
                },
                load(){
                    if (!this.loading && this.transaction) {
                        this.changeLoading(true);
                        this.edit(this.transaction.id)
                        .then(res =>{
                            this.changeLoading(false);
                            this.form = this.getForm;
                        })
                        .catch(err => {
                            this.changeLoading(false);
                            this.$alert(err);
                        })
                    }
                },
                afterLeave(){
                },
           }
    }
</script>