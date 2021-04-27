<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.paymentType')" filterable  clearable :size="size" class="d-block">
        <el-option v-for="item in paymentTypes" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/inventory';

    export default {
        mixins: [mix],
        computed: {
            ...mapGetters({
                paymentTypes: 'paymentTypes/inventory'
            })
        },
        mounted() {
            if (this.paymentTypes && this.paymentTypes.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "paymentTypes/inventory",
            })
        },
    }
</script>