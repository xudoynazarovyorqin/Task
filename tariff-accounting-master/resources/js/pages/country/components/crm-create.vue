<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.country') | lowerFirst }}
            </span>
            <el-button v-can="['countries.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="20">
                    <el-form-item :label="columns.name.title" prop="name" >
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.code.title"  prop="code" >
                        <el-input type="number" v-model="form.code" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item  :label="columns.full_name.title"  prop="full_name" >
                        <el-input type="textarea" v-model="form.full_name" autocomplete="off"></el-input>
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
    import country from "@/utils/mixins/models/country";

    export default {
        mixins:[drawer,country,form],
        methods:{
            ...mapActions({
                save: 'countries/store',
            }),
            afterOpen() {
                this.form = this.getForm;
            }
        }
    }
</script>
