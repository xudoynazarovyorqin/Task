<template>
    <el-row>
        <el-form-item :label="label || $t('message.status')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.status')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(state,index) in states" :key="'states' + index" :label="state.name" :value="state.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_state')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import create from '@/includes/modals/crm-state-create-modal';
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'states',
        mixins: [mix],
        components:{create},
        props: {
            state_id:{
                default: null,
            },
            prop:{
                default: 'state_id'
            }
        },
        watch: {
            state_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.state_id;
                }
            }
        },
        mounted() {
            if (this.states && this.states.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                states: 'states/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'states/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.state.id)
                }
            }
        },
    }
</script>