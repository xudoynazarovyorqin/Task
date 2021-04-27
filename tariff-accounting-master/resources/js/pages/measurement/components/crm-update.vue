<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.measurement') | lowerFirst }}
            </span>
            <el-button v-can="['measurements.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="18">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="18">
                    <el-form-item :label="columns.full_name.title">
                        <el-input v-model="form.full_name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="18">
                    <el-form-item :label="columns.code.title">
                        <el-input v-model="form.code" autocomplete="off"></el-input>
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
    import measurement from "@/utils/mixins/models/measurement";

    export default {
        props:['measurement'],
        mixins:[drawer,measurement,form],
        methods:{
            ...mapActions({
                save: 'measurements/update',
                getModel: 'measurements/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.measurement) {
                    this.changeLoading(true);
                    this.getModel(this.measurement.id)
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
