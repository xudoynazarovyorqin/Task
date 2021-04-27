<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.product')" filterable  clearable :size="size" class="d-block">
        <el-option v-for="item in products" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/inventory';

    export default {
        mixins: [mix],
        computed: {
            ...mapGetters({
                products: 'products/inventory'
            })
        },
        mounted() {
            if (this.products && this.products.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "products/inventory",
            })
        },
    }
</script>