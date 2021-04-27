<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.state')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(val, key) in items" :key="'state-' + key" :label="val" :value="key"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'transaction-states',
        mixins: [mix],
        computed: {
            ...mapGetters({
                items: 'transactions/transaction_states'
            })
        }
    }
</script>
