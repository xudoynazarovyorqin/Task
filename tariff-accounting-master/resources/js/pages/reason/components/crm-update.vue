<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.reason') | lowerFirst }}
            </span>
            <el-button v-can="['reasons.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="22">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.color.title" size="mini">
                        <el-color-picker  v-model="form.color" autocomplete="off"></el-color-picker>
                    </el-form-item>
                    <el-form-item  :label="columns.description.title" size="small" prop="description">
                        <el-input type="textarea" size="mini" v-model="form.description" :placeholder="columns.description.title" clearable></el-input>
                    </el-form-item>
                </el-col>
            </el-form>
        </el-main>
    </el-col>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';
    import form from '@/utils/mixins/form';
    import reason from "@/utils/mixins/models/reason";

    export default {
        props:['reason'],
        mixins:[drawer,reason,form],
        methods:{
            ...mapActions({
                save: 'reasons/update',
                getModel: 'reasons/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.reason) {
                    this.changeLoading(true);
                    this.getModel(this.reason.id)
                    .then(res =>{
                        this.form = this.getForm;
                        this.changeLoading()
                    })
                    .catch(err => {
                        this.changeLoading()
                    })
                }
            }
        }
    }
</script>
