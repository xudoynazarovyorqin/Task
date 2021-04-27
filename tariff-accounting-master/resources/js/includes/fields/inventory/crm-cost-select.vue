<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.cost')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(cost,index) in costs" :key="'costs-' + index" :label="cost.name" :value="cost.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'costs',
        mixins: [mix],
        mounted() {
            if (this.costs && this.costs.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                costs: 'costs/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'costs/inventory'
            })
        },
    }
</script>