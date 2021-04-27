<template>
    <modal  :name="name" width="45%" height="auto" :draggable="true" @before-open="beforeOpen()">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.level') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
             <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="170px" size="small">
                <el-form-item :label="columns.name.title" prop="name" size="mini">
                    <el-input v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item :label="columns.color.title" size="mini">
                        <el-color-picker  v-model="form.color" autocomplete="off"></el-color-picker>
                    </el-form-item>
                <el-form-item  :label="columns.left.title">
                    <levels v-model="form.left"></levels>
                </el-form-item>
                <el-form-item  :label="columns.right.title" size="small">
                    <levels v-model="form.right"></levels>
                </el-form-item>
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
    import levels from '@inventory/crm-level-select';
    import create from '@mixins/modals/create-modal';

    export default {
        mixins: [create],
        components:{levels},
        data(){
            return {
                name: 'create_level'
            }
        },
        computed: {
            ...mapGetters({
                rules: 'levels/rules',
                getForm: 'levels/form',
                columns: 'levels/columns'
            })
        },
        methods:{
            ...mapActions({
                save: 'levels/store',
                updateListInventory: 'levels/inventory'
            }),
            beforeOpen(){
                this.form = this.getForm;
            },
            submit(){
                if (!this.waiting) {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            this.changeWaiting(true);
                            this.save(this.form)
                                .then(async (res) => {
                                    await this.updateListInventory();
                                    this.$alert(res);
                                    this.changeWaiting();
                                    this.$emit('crm-close', {created: true, level: res.data.level});
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
