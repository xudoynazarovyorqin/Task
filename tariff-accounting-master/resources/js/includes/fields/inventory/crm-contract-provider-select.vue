<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.contract')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(item,index) in items" :key="'contract_providers-' + index" :label="'№ ' + item.number +  ' ' + $t('message.from') + ' ' + item.begin_date" :value="item.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name: 'contracts',
        mixins: [mix],
        props: {
            provider_id:{
                default: null,
            }
        },
        watch: {
            provider_id: {
                handler: function(newVal,oldVal) {
                    this.selected = null;
                },
                immediate: true,
                deep: true,
            }
        },
        mounted() {
            if (this.contract_providers && this.contract_providers.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                contract_providers: 'contractProviders/inventory'
            }),
            items: function() {
                return ((!this.provider_id) ? this.contract_providers : _.filter(this.contract_providers,['provider_id', this.provider_id]))
            },
        },
        methods: {
            ...mapActions({
                updateInventory: 'contractProviders/inventory'
            })
        },
    }
</script>