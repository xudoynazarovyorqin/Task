<template>
    <modal :name="name" width="40%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.country') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
             <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="180px" size="small">
                <el-form-item :label="columns.name.title" prop="name" >
                    <el-input v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item :label="columns.code.title"  prop="code" >
                    <el-input type="number" v-model="form.code" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item  :label="columns.full_name.title"  prop="full_name" >
                    <el-input type="textarea" :rows="4" v-model="form.full_name" autocomplete="off"></el-input>
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
    import create from '@mixins/modals/create-modal';

    export default {
        mixins: [create],
        data(){
            return {
                name: 'create_country'
            }
        },
        computed: {
            ...mapGetters({
                rules: 'countries/rules',
                getForm: 'countries/form',
                columns: 'countries/columns'
            })
        },
        methods:{
            ...mapActions({
                save: 'countries/store',
                updateListInventory: 'countries/inventory'
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
                                    this.$emit('crm-close', {created: true, country: res.data.country});
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
