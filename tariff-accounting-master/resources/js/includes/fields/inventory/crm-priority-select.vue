<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.priority')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(priority,index) in priorities" :key="'priorities-' + index" :label="priority.name" :value="priority.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'priorities',
        mixins: [mix],
        mounted() {
            if (this.priorities && this.priorities.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                priorities: 'priority/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'priority/inventory'
            })
        },
    }
</script>