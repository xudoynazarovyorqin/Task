<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.measurement')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(measurement,index) in measurements" :key="'measurements-' + index" :label="measurement.name" :value="measurement.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'measurements',
        mixins: [mix],
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
            })
        },
    }
</script>