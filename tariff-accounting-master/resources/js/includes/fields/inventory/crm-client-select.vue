<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.client')" filterable clearable :size="size" class="d-block" remote reserve-keyword :remote-method="onSearch" :loading="loading">
        <el-option v-for="(client,index) in clients" :key="'clients-' + index" :label="client.name" :value="client.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'clients',
        mixins: [mix],
        mounted() {
            if (this.clients && this.clients.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                clients: 'clients/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'clients/inventory'
            })
        },
    }
</script>
