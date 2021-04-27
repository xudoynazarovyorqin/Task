<template>
    <div :class="'el-input el-input--'+size">
        <money @input="dispatch" v-model="val" v-bind="money_product" class="el-input__inner"></money>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    export default {
        props:{
            old:{
                default: 0
            },
            size:{
                default: 'mini'
            }
        },
        data() {
            return {
                val: 0,
            }
        },
        computed: {
            ...mapGetters({
                money_product: "money_product",
            })
        },
        watch: {
            old: {
                handler:function(newVal) {
                    this.val = newVal;
                },
                immediate: true,
                deep: true,
            },
        },
        methods: {
            dispatch(e){
                this.$emit('input', e)
            }
        },
    }
</script>