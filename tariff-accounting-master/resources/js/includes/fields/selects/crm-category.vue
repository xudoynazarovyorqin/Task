<template>
    <el-row>
        <el-form-item :label="label || $t('message.category')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.category')" multiple filterable clearable :size="size" class="d-block">
                    <el-option v-for="(category,index) in categories" :key="'categories-' + index" :label="category.name" :value="category.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_category')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-category-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'categories',
        mixins: [mix],
        components:{create},
        props:{
            category_id: {
                default: null
            },
            prop:{
                default: 'category_id'
            },
            old_categories: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        watch: {
            category_id: {
                handler: function() {
                    //this.selected = this.category_id;
                },
                immediate: true,
                deep: true,
            },
            old_categories: {
                handler: function() {
                    this.selected = this.old_categories;
                },
                immediate: true,
                deep: true,
            },            
        },
        mounted() {
            if (this.categories && this.categories.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                categories: 'category/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'category/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.selected.push(data.category.id);
                }
            }
        },
    }
</script>