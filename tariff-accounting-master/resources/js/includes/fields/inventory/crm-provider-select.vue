<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.provider')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(provider,index) in providers" :key="'providers-' + index" :label="provider.name" :value="provider.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'providers',
        mixins: [mix],
        mounted() {
            if (this.providers && this.providers.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                providers: 'providers/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'providers/inventory'
            })
        },
    }
</script>