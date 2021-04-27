<template>
    <modal :name="name" width="80%" height="auto" @before-open="beforeOpen" :draggable="true">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.provider') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
             <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="180px" size="small">
                 <el-row>
                    <el-col :span="12">
                        <el-form-item :label="columns.name.title" prop="name">
                            <el-input v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.sku.title" prop="sku">
                            <el-input v-model="form.sku" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.email.title">
                            <el-input v-model="form.email" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.phone.title">
                            <el-input v-model="form.phone" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.fax.title">
                            <el-input v-model="form.fax" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.actual_address.title">
                            <el-input v-model="form.actual_address" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.comment.title">
                            <el-input :rows="3" type="textarea" v-model="form.comment" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <types size="mini" v-model="form.type_id"></types>
                        <el-form-item :label="columns.full_name.title">
                            <el-input v-model="form.full_name" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.legal_address.title">
                            <el-input v-model="form.legal_address" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.inn.title">
                            <el-input v-model="form.inn" autocomplete="off"></el-input>
                        </el-form-item>
                        <div v-if="form.type_id === 1">
                            <el-form-item :label="columns.mfo.title">
                                <el-input v-model="form.mfo" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.okonx.title">
                                <el-input v-model="form.okonx" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.oked.title">
                                <el-input v-model="form.oked" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.rkp_nds.title">
                                <el-input v-model="form.rkp_nds" autocomplete="off"></el-input>
                            </el-form-item>
                        </div>
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
    import create from '@mixins/modals/create-modal';
    import types from '@selects/crm-agent-type';

    export default {
        mixins: [create],
        components:{types},
        data(){
            return {
                name: 'create_provider',
            }
        },
        computed: {
            ...mapGetters({
                rules: 'providers/rules',
                model: 'providers/model',
                columns: 'providers/columns',
                types: 'providers/types'
            })
        },
        methods:{
            ...mapActions({
                save: 'providers/store',
                updateListInventory: 'providers/inventory',
            }),
            beforeOpen(){
                this.form = JSON.parse( JSON.stringify( this.model ));
            },
            submit(){
                if (!this.waiting) {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            this.changeWaiting(true);
                            this.save(this.form)
                                .then(async (res) => {
                                    await this.updateListInventory();
                                    this.$emit('crm-close', {created: true, provider: res.data.provider});
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
