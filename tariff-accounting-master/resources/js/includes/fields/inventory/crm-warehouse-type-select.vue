<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.warehouse_type')" filterable clearable :size="size">
        <el-option v-for="(warehouse_type,index) in warehouse_types" :key="'warehouse_types' + index" :label="warehouse_type.name" :value="warehouse_type.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        mixins: [mix],
        props: {
            size: {
                default: 'small',
            },
            warehouse_type_id: {
                default: null,
            }
        },
        watch: {
            warehouse_type_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.warehouse_type_id;
                },
                immediate: true,
                deep: true,
            },
        },
        async mounted() {
            if (this.warehouse_types && this.warehouse_types.length === 0) await this.loadInventory();
        },
        computed: {
            ...mapGetters({
                warehouse_types: 'warehouseTypes/inventory'
            })
        },
        methods: {
            ...mapActions({
                loadInventory: 'warehouseTypes/inventory'
            })
        },
    }
</script>