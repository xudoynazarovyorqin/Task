<template>
    <el-row>
        <el-form-item :label="label || $t('message.type')" size="small" :prop="prop">
            <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.type')" filterable clearable :size="size" class="d-block">
                <el-option v-for="(type,index) in types" :key="'types-' + index" :label="type.name" :value="type.id"></el-option>
            </el-select>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name:'types',
        mixins: [mix],
        props:{
            type_id: {
                default: null
            },
            prop:{
                default: 'type_id'
            },
        },
        watch: {
            type_id: {
                handler: function() {
                    this.selected = this.type_id;
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.types && this.types.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                types: 'clients/types'
            }),
            balance: function() {
                return (this.selected ? _.find(this.types, ['id', this.selected]).balance : 0)
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'clients/getTypes'
            })
        },
    }
</script>