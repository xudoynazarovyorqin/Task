<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.contract')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(item,index) in contracts" :key="'contracts-' + index" :label="'№ ' + item.number +  ' ' + $t('message.from') + ' ' + item.begin_date" :value="item.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name: 'contracts',
        mixins: [mix],
        mounted() {
            if (!_.size(this.contract_clients)) this.updateClientContractInventory()
            if (!_.size(this.contract_providers)) this.updateProviderContractInventory()
        },
        computed: {
            ...mapGetters({
                contract_clients: 'contractClients/inventory',
                contract_providers: 'contractProviders/inventory'
            }),
            contracts: function() {
                return [...this.contract_clients,...this.contract_providers];
            },
        },
        methods: {
            ...mapActions({
                updateClientContractInventory: 'contractClients/inventory',
                updateProviderContractInventory: 'contractProviders/inventory'
            })
        },
    }
</script>