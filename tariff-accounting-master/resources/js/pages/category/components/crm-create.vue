<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.category') | lowerFirst }}
            </span>
            <el-button v-can="['categories.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-3">
            <el-form ref="form" status-icon :model="form" :rules="rules" label-position="right" label-width="200px" size="small">
                <el-col :span="12">
                    <el-form-item :label="columns.name.title" prop="name" >
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item  :label="columns.parent_id.title">
                        <el-select v-model="form.parent_id" placeholder="" filterable clearable>
                            <el-option v-for="item in categories" :key="item.id" :label="item.name" :value="item.id"></el-option>
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
    import category from "@/utils/mixins/models/category";

    export default {
        mixins:[drawer,category,form],
        methods:{
            ...mapActions({
                save: 'category/store',
            }),
            afterOpen() {
                this.form = this.getForm;
            }
        }
    }
</script>
