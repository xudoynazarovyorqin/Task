<template>
    <modal :name="name" width="60%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.contract') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" class="style__label" size="mini">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item :label="columns.number.title" prop="number">
                            <el-input v-model="form.number" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.begin_date.title" prop="begin_date">
                            <el-date-picker type="date" v-model="form.begin_date"></el-date-picker>
                        </el-form-item>
                        <statues size="mini" v-model="form.status_id"></statues>
                        <clients size="mini" v-model="form.client_id"></clients>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="columns.conclusion_date.title">
                            <el-date-picker
                              type="date"
                              v-model="form.conclusion_date"
                              :format="date_format"
                              :value-format="date_format"
                            ></el-date-picker>
                        </el-form-item>
                        <el-form-item :label="columns.termination_date.title">
                            <el-date-picker
                              type="date"
                              v-model="form.termination_date"
                              :format="date_format"
                              :value-format="date_format"
                            ></el-date-picker>
                        </el-form-item>
                        <el-form-item :label="columns.comment.title">
                            <el-input type="textarea" :rows="3" v-model="form.comment" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>
        <div class="el-dialog__footer">
            <span class="dialog-footer">
                <el-button @click="closeModal(name)" round size="mini" :loading="waiting">{{ $t('message.cancel') }}</el-button>
                <el-button type="primary" @click="submit" round size="mini" :loading="waiting">{{ $t('message.save') }}</el-button>
            </span>
        </div>
    </modal>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import statues from '@selects/crm-state';
    import clients from '@selects/crm-client';
    import create from '@mixins/modals/create-modal';

    export default {
        mixins:[create],
        components:{statues,clients},
        data(){
            return {
                name: 'create_contract_client'
            }
        },
        computed: {
            ...mapGetters({
                money: 'money',
                contracts: 'contractClients/inventory',
                rules: 'contractClients/rules',
                model: 'contractClients/model',
                columns: 'contractClients/columns',
            }),
            parents: function () {
                return this.contracts.filter((contract_client) => {return this.form.client_id == contract_client.client_id});
            }
        },
        methods:{
            ...mapActions({
                save: 'contractClients/store',
                updateInventoryList: 'contractClients/inventory',
            }),
            beforeOpen(){
                this.form = JSON.parse( JSON.stringify( this.model ));
                if (this.contracts && this.contracts.length == 0) this.updateInventoryList();
            },
            submit(){
                if (!this.waiting) {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            this.changeWaiting(true);
                            this.save(this.form)
                                .then(async (res) => {
                                    await this.updateInventoryList();
                                    this.$emit('crm-close', {created: true, contract_client: res.data.contractClient});
                                    this.$alert(res);
                                    this.changeWaiting();
                                    this.closeModal(this.name);
                                })
                                .catch(err => {
                                    this.changeWaiting();
                                    this.$alert(err);
                                })
                        }
                    });
                }
            }
        }
    }

</script>
