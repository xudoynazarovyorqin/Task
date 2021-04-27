<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.category')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(category,index) in categories" :key="'categories-' + index" :label="category.name" :value="category.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'categories',
        mixins: [mix],
        mounted() {
            if (this.categories && this.categories.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                categories: 'category/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'category/inventory'
            })
        },
    }
</script>