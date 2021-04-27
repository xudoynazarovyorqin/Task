<template>
    <el-select :value="selected" @input="dispatch" @change="cChange()" :placeholder="plc || $t('message.currency')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(currency,index) in currencies" :key="'currencies-' + index" :label="currency.symbol + ' (' + currency.code + ')'" :value="currency.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'currencies',
        mixins: [mix],
        props:{
            currency_id:{
                default: null,
            }
        },
        watch: {
            currency_id:{
                handler:function() {
                    this.selected = this.currency_id
                },
                immediate: true
            }
        },
        mounted() {
            if (this.currencies && this.currencies.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                currencies: 'currencies/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'currencies/inventory'
            }),
            cChange(){
                this.$emit('c-change');
            }
        },
    }
</script>