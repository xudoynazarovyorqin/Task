<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.level') | lowerFirst }}
            </span>
            <el-button v-can="['levels.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3"  v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px">
                <el-col :span="22">
                    <el-form-item :label="columns.name.title" prop="name" size="mini">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.color.title" size="mini">
                        <el-color-picker  v-model="form.color" autocomplete="off"></el-color-picker>
                    </el-form-item>
                    <levels v-model="form.left" :level_id="form.left" :label="columns.left.title" size="mini" prop=""></levels>
                    <levels v-model="form.right" :level_id="form.right" :label="columns.right.title" size="mini"  prop=""></levels>
                </el-col>
            </el-form>
        </el-main>
    </el-col>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';
    import form from '@/utils/mixins/form';
    import level from "@/utils/mixins/models/level";

    export default {
        props:['level'],
        mixins:[drawer,level,form],
        methods:{
            ...mapActions({
                save: 'levels/update',
                getModel: 'levels/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.level) {
                    this.changeLoading(true);
                    this.getModel(this.level.id)
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
