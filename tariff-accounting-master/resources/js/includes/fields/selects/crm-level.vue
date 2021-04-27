<template>
    <el-row>
        <el-form-item :label="label || $t('message.level')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.level')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(level,index) in levels" :key="'levels' + index" :label="level.name" :value="level.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_level')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import create from '@/includes/modals/crm-level-create-modal';
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'levels',
        mixins: [mix],
        components:{create},
        props: {
            level_id:{
                default: null,
            },
            prop:{
                default: 'level_id'
            }
        },
        watch: {
            level_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.level_id;
                }
            }
        },
        mounted() {
            if (this.levels && this.levels.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                levels: 'levels/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'levels/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.level.id)
                }
            }
        },
    }
</script>