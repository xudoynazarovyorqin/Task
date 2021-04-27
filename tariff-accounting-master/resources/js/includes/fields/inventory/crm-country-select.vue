<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.country')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(country,index) in countries" :key="'countries-' + index" :label="country.name" :value="country.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'countries',
        mixins: [mix],
        mounted() {
            if (this.countries && this.countries.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                countries: 'countries/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'countries/inventory'
            })
        },
    }
</script>