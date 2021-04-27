<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.contract')" filterable clearable :size="size" class="d-block" remote reserve-keyword :remote-method="onSearch" :loading="loading">
        <el-option v-for="(item,index) in items" :key="'contract_clients-' + index" :label="'№ ' + item.number +  ' ' + $t('message.from') + ' ' + item.begin_date" :value="item.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name: 'contracts',
        mixins: [mix],
        props: {
            client_id:{
                default: null,
            }
        },
        watch: {
            client_id: {
                handler: function(newVal,oldVal) {
                    this.selected = null;
                },
                immediate: true,
                deep: true,
            }
        },
        mounted() {
            if (this.contract_clients && this.contract_clients.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                contract_clients: 'contractClients/inventory'
            }),
            items: function() {
                return ((!this.client_id) ? this.contract_clients : _.filter(this.contract_clients,['client_id', this.client_id]))
            },
        },
        methods: {
            ...mapActions({
                updateInventory: 'contractClients/inventory'
            })
        },
    }
</script>
