<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.Score') | lowerFirst }}
            </span>
            <el-button v-can="['scores.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3">
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
        mixins:[drawer,score,form],
        computed: {
            ...mapGetters({
                currencies: 'currencies/inventory'
            })
        },
        methods:{
            ...mapActions({
                save: 'scores/store',
            }),
            afterOpen() {
                this.form = this.getForm;
                const active_currency = _.find(this.currencies, 'active');
                if (active_currency) {
                    this.form.currency_id = active_currency.id;
                }
            }
        }
    }
</script>
