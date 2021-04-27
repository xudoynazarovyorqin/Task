<template>
    <el-row>
        <el-form-item :label="label || $t('message.warehouseType')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.warehouseType')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(warehouseType,index) in warehouseTypes" :key="'warehouseTypes' + index" :label="warehouseType.name" :value="warehouseType.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'warehouseTypes',
        mixins: [mix],
        props: {
            warehouse_type_id:{
                default: null,
            },
            prop:{
                default: 'warehouse_type_id'
            }
        },
        watch: {
            warehouse_type_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.warehouse_type_id;
                },
                immediate:true,
            }
        },
        mounted() {
            if (this.warehouseTypes && this.warehouseTypes.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                warehouseTypes: 'warehouseTypes/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'warehouseTypes/inventory'
            })
        },
    }
</script>