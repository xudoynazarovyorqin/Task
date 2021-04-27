<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.warehouse')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(warehouse,index) in items" :key="'warehouses-' + index" :label="warehouse.name" :value="warehouse.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/inventory';

    export default {
        name:'warehouses',
        mixins: [mix],
        props:{
            type_id: {
                default: null,
            },
            warehouse_id: {
                default: null,
            }
        },
        watch: {
            warehouse_id: {
                handler: function(newVal,oldVal) {
                    this.dispatch(this.warehouse_id)
                },
                immediate: true,
                deep: true,
            },
        },
        mounted() {
            if (this.warehouses && this.warehouses.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                warehouses: 'warehouses/inventory'
            }),
            items: function() {
                return (!this.type_id) ? this.warehouses : _.filter(this.warehouses, ['warehouse_type_id',this.type_id])
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'warehouses/inventory'
            })
        },
    }
</script>