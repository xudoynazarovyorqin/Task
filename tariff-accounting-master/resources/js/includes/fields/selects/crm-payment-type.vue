<template>
    <el-row>
        <el-form-item :label="label || $t('message.paymentType')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.paymentType')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(paymentType,index) in paymentTypes" :key="'paymentTypes-' + index" :label="paymentType.name" :value="paymentType.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name:'paymentTypes',
        mixins: [mix],
        props:{
            payment_type_id: {
                default: null
            },
            prop:{
                default: 'payment_type_id'
            },
        },
        watch: {
            payment_type_id: {
                handler: function() {
                    this.dispatch(this.payment_type_id);
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.paymentTypes && this.paymentTypes.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                paymentTypes: 'paymentTypes/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'paymentTypes/inventory'
            })
        },
    }
</script>