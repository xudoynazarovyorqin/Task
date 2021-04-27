<template>
    <modal  :name="name" width="60%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.contract') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="150px" size="small">
                <el-row>
                    <el-col :span="12">
                        <el-form-item :label="columns.number.title" prop="number">
                            <el-input v-model="form.number" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.begin_date.title" prop="begin_date">
                            <el-date-picker type="date" v-model="form.begin_date" format="dd.MM.yyyy" value-format="dd.MM.yyyy"></el-date-picker>
                        </el-form-item>
                        <statues size="mini" v-model="form.status_id"></statues>
                        <providers size="mini" v-model="form.provider_id"></providers>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="columns.sum.title">
                            <div class="el-input el-input--mini">
                                <money v-model="form.sum"
                                v-bind="money"
                                class="el-input__inner"></money>
                            </div>
                        </el-form-item>
                        <el-form-item :label="columns.parent_id.title">
                            <el-select v-model="form.parent_id" :placeholder="$t('message.choose')" filterable clearable>
                                <el-option v-for="item in parents" :key="item.id" :label="'№ ' + item.number + ' от ' + item.begin_date" :value="item.id"></el-option>
                            </el-select>
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
    import {mapGetters,mapActions} from 'vuex'
    import providers from '@selects/crm-provider';
    import statues from '@selects/crm-state';
    import create from '@mixins/modals/create-modal';

    export default {
        mixins:[create],
        components: {providers,statues},
        data(){
            return {
                name: 'create_contract_provider',
            }
        },
        computed: {
            ...mapGetters({
                money: 'money',
                contracts: 'contractProviders/inventory',
                rules: 'contractProviders/rules',
                model: 'contractProviders/model',
                columns: 'contractProviders/columns',
            }),
            parents: function () {
                return _.filter(this.contracts,['provider_id', this.form.provider_id])
            }
        },
        methods:{
            ...mapActions({
                save: 'contractProviders/store',
                updateInventoryList: 'contractProviders/inventory',
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
                                    this.$emit('crm-close', {created: true, contract_provider: res.data.contractProvider});
                                    this.$alert(res);
                                    this.changeWaiting();
                                    this.closeModal(this.name)
                                })
                                .catch(err => {
                                    this.changeWaiting();
                                    this.$alert(err)
                                })
                        }
                    });
                }
            }
        }
    }

</script>
