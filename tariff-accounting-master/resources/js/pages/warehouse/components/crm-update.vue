<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.edit') }} {{ $t('message.warehouse') | lowerFirst }}
            </span>
            <el-button v-can="['warehouses.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3" v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="20">
                    <warehouse-types v-model="form.warehouse_type_id" :warehouse_type_id="form.warehouse_type_id"></warehouse-types>
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
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
    import warehouse from "@/utils/mixins/models/warehouse";

    export default {
        props:['warehouse'],
        mixins:[drawer,warehouse,form],
        methods:{
            ...mapActions({
                save: 'warehouses/update',
                getModel: 'warehouses/show'
            }),
            afterOpen() {
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.warehouse) {
                    this.changeLoading(true);
                    this.getModel(this.warehouse.id)
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
