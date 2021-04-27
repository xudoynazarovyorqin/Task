<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.agent')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(agent,index) in agents" :key="'agents-' + index" :label="agent.name" :value="agent.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'agents',
        mixins: [mix],
        mounted() {
            if (!_.size(this.providers)) this.updateProviderInventory()
            if (!_.size(this.clients)) this.updateClientInventory()
        },
        computed: {
            ...mapGetters({
                providers: 'providers/inventory',
                clients: 'clients/inventory'
            }),
            agents: function() {
                return [...this.providers,...this.clients];
            }
        },
        methods: {
            ...mapActions({
                updateProviderInventory: 'providers/inventory',
                updateClientInventory: 'clients/inventory'
            })
        },
    }
</script>