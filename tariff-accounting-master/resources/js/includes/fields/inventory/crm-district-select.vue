<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.district')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(district,index) in districts" :key="'districts-' + index" :label="district.name" :value="district.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'districts',
        mixins: [mix],
        mounted() {
            if (this.districts && this.districts.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                districts: 'districts/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'districts/inventory'
            })
        },
    }
</script>
