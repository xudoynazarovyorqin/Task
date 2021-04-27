<template>
    <modal :name="name" width="50%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.cost') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="150px" size="small">
                <el-col :span="24">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.amount.title">
                        <el-col :span="12">
                            <price v-model="form.amount"></price>
                        </el-col>
                        <el-col :span="11" :offset="1">
                            <currencies v-model="form.currency_id" :currency_id="form.currency_id" size="mini"></currencies>
                        </el-col>
                    </el-form-item>
                    <el-form-item :label="columns.description.title">
                        <el-input type="textarea" :rows="4" v-model="form.description" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
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
    import create from '@mixins/modals/create-modal';
    import cost from "@/utils/mixins/models/cost";

    export default {
        mixins: [create,cost],
        data(){
            return {
                name: 'create_cost'
            }
        },
        methods:{
            ...mapActions({
                save: 'costs/store',
            }),
            beforeOpen(){
                this.form = this.getForm;
                if (_.size(this.currencies)) {
                    const active_currency = _.find(this.currencies, 'active');
                    if (active_currency) {
                        this.form.currency_id = active_currency.id;
                    }
                }
            },
            submit(){
                if (!this.waiting) {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            this.changeWaiting(true);
                            this.save(this.form)
                                .then(async (res) => {
                                    await this.updateInventory();
                                    this.$emit('crm-close', {created: true, cost: res.data.cost});
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
