<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.paymentType') | lowerFirst }}
            </span>
            <el-button v-can="['paymentTypes.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="20">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.is_active.title">
                        <el-select v-model="form.is_active" filterable clearable >
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
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
    import paymentType from "@/utils/mixins/models/paymentType";

    export default {
        props:['type'],
        mixins:[drawer,paymentType,form],
        methods:{
            ...mapActions({
                save: 'paymentTypes/update',
                getModel: 'paymentTypes/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.type) {
                    this.changeLoading(true);
                    this.getModel(this.type.id)
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
