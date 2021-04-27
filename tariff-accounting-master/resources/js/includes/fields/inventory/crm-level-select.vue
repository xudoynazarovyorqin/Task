<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.level')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(level,index) in levels" :key="'levels' + index" :label="level.name" :value="level.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'levels',
        mixins: [mix],
        mounted() {
            if (this.levels && this.levels.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                levels: 'levels/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'levels/inventory'
            })
        },
    }
</script>