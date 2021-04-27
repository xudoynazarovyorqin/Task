<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.payment_system')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(val, key) in items" :key="'payment_system-' + key" :label="val" :value="key"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'payment-systems',
        mixins: [mix],
        computed: {
            ...mapGetters({
                items: 'transactions/payment_systems'
            })
        }
    }
</script>
