<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.currency') | lowerFirst }}
            </span>
            <el-button v-can="['currencies.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="20">
                    <el-form-item :label="columns.symbol.title" prop="symbol">
                        <el-input v-model="form.symbol" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="">
                        <el-checkbox v-model="form.reverse" :label="columns.reverse.title"></el-checkbox>
                    </el-form-item>
                    <el-form-item v-if="!form.reverse" :label="getLabel">
                        <el-input type="number" v-model="form.rate" autocomplete="off" style="width: 250px !important">
                            <i slot="append">{{ getAppendText }}</i>
                        </el-input>
                    </el-form-item>
                    <el-form-item v-if="form.reverse" :label="getLabel">
                        <el-input type="number" v-model="form.reversed_rate" autocomplete="off" style="width: 250px !important">
                            <i slot="append">{{ getAppendText }}</i>
                        </el-input>
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
    import currency from "@/utils/mixins/models/currency";

    export default {
        mixins:[drawer,currency,form],
        methods:{
            ...mapActions({
                save: 'currencies/store',
            }),
            afterOpen() {
                this.form = this.getForm;
            }
        }
    }
</script>
