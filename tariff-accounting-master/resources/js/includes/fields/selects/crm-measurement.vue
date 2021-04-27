<template>
    <el-row>
        <el-form-item :label="label || $t('message.measurement')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.measurement')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(measurement,index) in measurements" :key="'measurements-' + index" :label="measurement.name" :value="measurement.id"></el-option>
                </el-select>
            </el-col>
            <el-col v-if="is_with_add" :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_measurement')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-measurement-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'measurements',
        mixins: [mix],
        components:{create},
        props:{
            measurement_id: {
                default: null
            },
            prop:{
                default: 'measurement_id'
            },
            is_with_add: {
                default: false
            },
        },
        watch: {
            measurement_id: {
                handler: function() {
                    this.dispatch(this.measurement_id);
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.measurements && this.measurements.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                measurements: 'measurements/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'measurements/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.measurement.id)
                }
            }
        },
    }
</script>
