<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.costs') | lowerFirst }}
            </span>
            <el-button v-can="['costs.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="20">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.amount.title" prop="price">
                        <el-col :span="12">
                            <price v-model="form.amount"></price>
                        </el-col>
                        <el-col :span="11" :offset="1">
                            <currencies v-model="form.currency_id" :currency_id="form.currency_id" size="mini"></currencies>
                        </el-col>
                    </el-form-item>
                    <el-col :span="16">
                        <el-form-item :label="columns.description.title">
                            <el-input type="textarea" :rows="4" v-model="form.description" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item :label="columns.is_distribution.title" prop="price">
                            <el-checkbox v-model="form.is_distribution"></el-checkbox>
                        </el-form-item>
                    </el-col>
                </el-col>
            </el-form>
        </el-main>
    </el-col>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';
    import form from '@/utils/mixins/form';
    import cost from "@/utils/mixins/models/cost";

    export default {
        mixins:[drawer,cost,form],
        methods:{
            ...mapActions({
                save: 'costs/store',
            }),
            afterOpen() {
                this.form = this.getForm;
                if (_.size(this.currencies)) {
                    const active_currency = _.find(this.currencies, 'active');
                    if (active_currency) {
                        this.form.currency_id = active_currency.id;
                    }
                }
            }
        }
    }
</script>
