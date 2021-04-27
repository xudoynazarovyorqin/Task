<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.edit') }} {{ $t('message.permission') }} № {{ permission.id }}</span>
            <el-button v-can="['permissions.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading">
            <el-row>
                <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                    <el-col :span="22">
                        <el-form-item :label="columns.name.title" prop="name">
                            <el-input v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-form>
            </el-row>
        </el-main>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        props:['permission'],
        mixins:[form,drawer],
        computed: {
            ...mapGetters({
                parent_permissions: 'permissions/parent_permissions',
                rules: 'permissions/rules',
                getForm: 'permissions/form',
                columns: 'permissions/columns'
            })
        },
        methods:{
            ...mapActions({
                save: 'permissions/update',
                edit: 'permissions/show',
                updateList: 'permissions/index',
            }),
            afterOpen(){
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.permission) {
                    this.changeLoading(true);
                    this.edit(this.permission.id)
                    .then(res => {
                        this.form = this.getForm;
                        this.changeLoading(false);
                    })
                    .catch(err =>{
                        this.changeLoading(false);
                        this.$alert(err);
                    })
                }
            },
            submit(close = true){
                this.$refs['form'].validate((valid) => {
                    if (valid){
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.listChanged();
                                this.waitingStop();
                                this.close();
                            })
                            .catch(err => {
                                this.waitingStop();
                                this.$alert(err);
                            });
                    }
                });
            }
        }
    }

</script>
