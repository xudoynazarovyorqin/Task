<template>
    <el-row>
        <el-form-item :label="label || $t('message.priority')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.priority')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(priority,index) in priorities" :key="'priorities-' + index" :label="priority.name" :value="priority.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_priority')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-priority-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'priorities',
        mixins: [mix],
        components:{create},
        props:{
            priority_id: {
                default: null
            },
            prop:{
                default: 'priority_id'
            },
        },
        watch: {
            priority_id: {
                handler: function() {
                    this.selected = this.priority_id;
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.priorities && this.priorities.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                priorities: 'priority/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'priority/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.priority.id)
                }
            }
        },
    }
</script>