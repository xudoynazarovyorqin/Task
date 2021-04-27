<template>
    <div :class="'el-input el-input--'+size">
        <money @input="dispatch" v-model="val" v-bind="money_material" class="el-input__inner"></money>
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
                money_material: "money_material",
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
                console.log(e)
                this.$emit('input', e);
            }
        },
    }
</script>