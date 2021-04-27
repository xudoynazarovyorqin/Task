<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.type')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(type,index) in types" :key="'types-' + index" :label="type.name" :value="type.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'types',
        mixins: [mix],
        mounted() {
            if (this.types && this.types.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                types: 'clients/types'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'clients/getTypes'
            })
        },
    }
</script>