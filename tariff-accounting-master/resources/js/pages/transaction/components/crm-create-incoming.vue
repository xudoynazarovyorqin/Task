<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.new') }} {{ $t('message.Incoming payment') | lowerFirst }}</span>
			<el-button v-can="['transactions.create']" type="success" size="small" class="mr-1" :loading="waiting"
				@click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['transactions.create']" type="primary" size="small" class="mr-1" :loading="waiting"
				@click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main class="pt-2">
            <el-card class="box-card crm-card-pt-1" v-loading="loadApplication">
                <el-form ref="form" :model="form" :rules="rules" :label-position="'right'" label-width="200px">
                    <el-col>
                        <el-form-item size="small" class="d-inline-flex crm-document-number">
                            <span  class="document-title"> {{ form.id }} </span>
                            <template slot="label">
                                <span class="document-title">{{ $t('message.transaction') }} {{ $t('message.n') }}</span>
                            </template>
                        </el-form-item>
                        <span class="el-dropdown-link float-right p-4">
                            <span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
                        </span>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item class="mb-1" :label="$t('message.console_number')">
                            <el-col :span="19">
                                <el-input v-model="console_number" size="small" clearable></el-input>
                            </el-col>
                            <el-col :span="4" :offset="1">
                                <el-button @click="getApplicationByConsoleNumber()" type="primary" icon="el-icon-search" size="small"></el-button>
                                <!-- <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_client')"> </i> -->
                            </el-col>
                        </el-form-item>
                        <clients v-model="client_id" :client_id="client_id"></clients>
                        <contracts v-model="contract_client_id" :contract_id="contract_client_id" :client_id="client_id"></contracts>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="columns.amount.title" prop="amount" size="small">
                            <amount v-model="form.amount" :old="form.amount" size="small"></amount>
                        </el-form-item>
                        <el-form-item :label="columns.comment.title" size="small" prop="comment">
                            <el-input type="textarea" size="mini" v-model="form.comment"
                                :placeholder="columns.comment.title" clearable  :rows="2"></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('message.application') + ' ' + $t('message.n') + ':'" size="small">
                            <span>{{ form.transactionable_id }}</span>
                        </el-form-item>
                        <el-form-item :label="$t('message.not_paid') + ':'" size="small">
                            <span>{{ total_not_paid | formatMoney }}</span>
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
    import incoming from '@/utils/mixins/models/incoming-transaction';
    import { mapGetters, mapActions } from 'vuex';

    export default {
           mixins:[form,drawer,incoming],
           data() {
               return {
                   total_not_paid: 0,
               }
           },
           created() {
                //this.debouncedSearchApplication = _.debounce(this.getApplicationByConsoleNumber, 1000)
            },
           computed: {
               ...mapGetters({
                   lastId: 'transactions/lastId',
               })
           },
           watch: {
               client_id: {
                    handler: function(new_value, old_value) {
                        if( new_value ){
                            //this.contract_client_id = '';
                        }
                    },
                    deep: true
                },
                contract_client_id: {
                    handler: function(new_value, old_value) {
                        if( new_value ){
                            this.getApplicationByContract();
                        }
                    },
                    deep: true
                },
                // console_number(new_v, old_v) {
                //     if( new_v ) {
                //         this.debouncedSearchApplication();
                //     }
                // }
            },
           methods: {
                ...mapActions({
                    save: 'transactions/store',
                    getLastId: 'transactions/getLastId',
                    empty: 'transactions/empty',
                    getApplicationDocument: 'transactions/getApplicationDocument',
                }),
                afterOpen(){
                    this.form = this.getForm;
                    if (!this.last_id)
                        this.getLastId().then(res => { this.form.id = res.last_id})
                    else
                        this.form.id = this.last_id;
                },
                getApplicationByContract() {
                    this.loadApplication = true;
                    this.getApplicationDocument({contract_client_id: this.contract_client_id})
                            .then(res => {
                                this.loadApplication = false;
                                this.console_number = res.application.console_number;
                                this.form.transactionable_id = res.application.id;
                                this.total_not_paid = res.application.total_not_paid;
                            })
                            .catch(err => {
                                this.loadApplication = false;
                                this.form.transactionable_id = null;
                                this.$alert(err);
                            });
                },
                getApplicationByConsoleNumber() {
                    if( this.console_number ) {
                        this.loadApplication = true;
                        this.getApplicationDocument({console_number: this.console_number})
                                .then(res => {
                                    this.loadApplication = false;
                                    this.client_id = res.application.client_id;
                                    this.contract_client_id = res.application.contract_client_id;
                                    this.form.transactionable_id = res.application.id;
                                    this.total_not_paid = res.application.total_not_paid;
                                })
                                .catch(err => {
                                    this.loadApplication = false;
                                    this.form.transactionable_id = null;
                                    this.$alert(err);
                                });
                    }
                },
                afterLeave(){
                    if (_.isFunction(this.empty)) {
                        this.empty();
                    }
                }
           }
    }
</script>
