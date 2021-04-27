<template>
      <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.state') | lowerFirst }}
            </span>
            <el-button v-can="['states.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="100px" size="small">
                <el-col :span="16">
                    <el-form-item :label="columns.state.title" prop="state" size="mini">
                        <el-input v-model="form.state" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.status.title" size="mini">
                        <el-input v-model="form.status" autocomplete="off"></el-input>
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
    import state from "@/utils/mixins/models/state";

    export default {
        props:['state'],
        mixins:[drawer,state,form],
        methods:{
            ...mapActions({
                save: 'states/update',
                getModel: 'states/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.state) {
                    this.changeLoading(true);
                    this.getModel(this.state.id)
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
