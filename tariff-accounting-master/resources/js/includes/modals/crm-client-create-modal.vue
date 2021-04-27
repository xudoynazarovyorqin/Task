<template>
    <modal :name="name" width="70%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.client') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" class="style__label" size="mini">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item :label="columns.name.title" prop="name">
                            <el-input v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                        <!-- <el-form-item :label="columns.actual_address.title">
                            <el-input v-model="form.actual_address" autocomplete="off"></el-input>
                        </el-form-item> -->
                        <el-form-item :label="columns.phone.title">
                            <el-input v-model="form.phone" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item :label="columns.email.title">
                            <el-input v-model="form.email" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.comment.title">
                            <el-input :rows="3" type="textarea" v-model="form.comment" autocomplete="off"></el-input>
                        </el-form-item>
                        <!-- <types size="mini" v-model="form.type_id"></types> -->
                        <div v-if="form.type_id === 1">
                            <el-form-item :label="columns.inn.title">
                                <el-input v-model="form.inn" autocomplete="off"></el-input>
                            </el-form-item>
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
                <el-row :gutter="20">
                    <el-col :span="24">
                        <el-card class="box-card clent_calabs">
                        <el-collapse v-model="activeNames">
                            <el-collapse-item :title="$t('message.object')" name="1">
                            <el-form-item class="mb-1" :label="columns.object_name.title">
                                <el-input v-model="form.object_name" size="small"></el-input>
                            </el-form-item>

                            <districts v-model="form.district_id" :district_id="form.district_id" :label="$t('message.district')"></districts>

                            <quarters v-model="form.quarter_id" :quarter_id="form.quarter_id" :label="$t('message.quarter')"></quarters>

                            <el-row :gutter="20">
                                <el-col :span="12">
                                <el-form-item class="mb-1" :label="columns.object_street.title">
                                    <el-input v-model="form.object_street" size="small"></el-input>
                                </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                <el-form-item class="mb-1" :label="columns.object_home.title">
                                    <el-input v-model="form.object_home" size="small"></el-input>
                                </el-form-item>
                                </el-col>

                                <el-col :span="12">
                                <el-form-item class="mb-1" :label="columns.object_corps.title">
                                    <el-input v-model="form.object_corps" size="small"></el-input>
                                </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                <el-form-item class="mb-1" :label="columns.object_flat.title">
                                    <el-input v-model="form.object_flat" size="small"></el-input>
                                </el-form-item>
                                </el-col>
                            </el-row>
                            </el-collapse-item>
                        </el-collapse>
                        </el-card>
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
    import districts from '@selects/crm-district';
    import quarters from '@selects/crm-quarter';

    export default {
        mixins: [create],
        components:{types, districts, quarters},
        data(){
            return {
                activeNames: ["1"],
                name: 'create_client',
            }
        },
        computed: {
            ...mapGetters({
                clients: 'clients/inventory',
                rules: 'clients/rules',
                model: 'clients/model',
                columns: 'clients/columns',
            })
        },
        methods:{
            ...mapActions({
                save: 'clients/store',
                updateInventoryList: 'clients/inventory',
            }),
            beforeOpen(){
                this.form = JSON.parse( JSON.stringify( this.model ));
            },
            submit(){
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.save(this.form)
                            .then(async (res) => {
                                await this.updateInventoryList();
                                this.$alert(res);
                                this.$refs['form'].resetFields();
                                this.$emit('crm-close', {created: true, client: res.data.client});
                                this.closeModal()
                            })
                            .catch(err => {
                                this.$alert(err)
                            })
                    }
                });
            },
            closeModal(){
                this.$modal.hide('create_client');
            }
        }
    }

</script>
