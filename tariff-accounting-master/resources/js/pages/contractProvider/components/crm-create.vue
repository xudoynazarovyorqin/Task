<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.new') }} {{ $t('message.contract') | lowerFirst }}</span>
            <el-button v-can="['contractProviders.create']" type="success" size="small" class="mr-1" :loading="waiting"
                @click="submit(false)"> {{ $t('message.save') }}</el-button>
            <el-button v-can="['contractProviders.create']" type="primary" size="small" class="mr-1" :loading="waiting"
                @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
            </el-button>
        </header>
        <el-main>        
            <el-card shadow="never">
                <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
                    <el-col :span="10">
                        <el-form-item :label="columns.number.title" prop="number">
                            <el-input v-model="form.number" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.begin_date.title" prop="begin_date">
                            <el-date-picker type="date" v-model="form.begin_date" :format="date_format" :value-format="date_format"></el-date-picker>
                        </el-form-item>
                        <states v-model="form.status_id" :state_id="form.status_id" size="mini"></states>
                        <providers v-model="form.provider_id" :provider_id="form.provider_id" size="mini"></providers>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item :label="columns.sum.title">
                            <amount v-model="form.sum" :old="form.sum" size="mini"></amount>
                        </el-form-item>
                        <contracts v-model="form.parent_id" :label="columns.parent_id.title" :contract_id="form.contract_id" :provider_id="form.provider_id" size="mini"></contracts>
                        <el-form-item :label="columns.comment.title">
                            <el-input type="textarea" :rows="3" v-model="form.comment" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-form>
            </el-card>
        </el-main>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import form from '@/utils/mixins/form';
    import contract from '@/utils/mixins/models/contract-provider';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins:[drawer,form,contract],
        methods:{
            ...mapActions({
                save: 'contractProviders/store',
            }),
            afterOpen(){
                this.form = this.getForm;
            }
        }
    }
</script>
