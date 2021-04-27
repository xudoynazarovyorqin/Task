<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.status')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(state,index) in states" :key="'states' + index" :label="state.name" :value="state.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'states',
        mixins: [mix],
        mounted() {
            if (this.states && this.states.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                states: 'states/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'states/inventory'
            })
        },
    }
</script>