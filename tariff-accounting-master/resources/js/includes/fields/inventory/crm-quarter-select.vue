<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.quarter')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(quarter,index) in quarters" :key="'quarters-' + index" :label="quarter.name" :value="quarter.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'quarters',
        mixins: [mix],
        mounted() {
            if (this.quarters && this.quarters.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                quarters: 'quarters/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'quarters/inventory'
            })
        },
    }
</script>
