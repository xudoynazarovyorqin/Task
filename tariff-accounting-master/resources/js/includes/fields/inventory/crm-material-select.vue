<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.material')" filterable  clearable :size="size" class="d-block">
        <el-option v-for="item in materials" :key="item.id" :label="item.name" :value="item.id"></el-option>
    </el-select>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/inventory';

    export default {
        mixins: [mix],
        computed: {
            ...mapGetters({
                materials: 'materials/inventory'
            })
        },
        mounted() {
            if (this.materials && this.materials.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "materials/inventory",
            })
        },
    }
</script>