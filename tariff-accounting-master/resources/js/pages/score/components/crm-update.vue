<template>
      <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.Score') | lowerFirst }}
            </span>
            <el-button v-can="['scores.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="180px" size="small">
                <el-col :span="16">
                    <el-form-item :label="columns.name.title" prop="state" size="mini">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.branch_name.title" size="mini">
                        <el-input v-model="form.branch_name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.mfo.title" size="mini">
                        <el-input v-model="form.mfo" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.number.title" size="mini">
                        <el-input v-model="form.number" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.currency_id.title" size="mini">
                        <currencies v-model="form.currency_id" :currency_id="form.currency_id"></currencies>
                    </el-form-item>
                    <el-form-item :label="columns.active.title" size="small">
                        <el-switch v-model="form.active"></el-switch>
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
    import score from "@/utils/mixins/models/score";

    export default {
        props:['score'],
        mixins:[drawer,score,form],
        methods:{
            ...mapActions({
                save: 'scores/update',
                getModel: 'scores/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.score) {
                    this.changeLoading(true);
                    this.getModel(this.score.id)
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
