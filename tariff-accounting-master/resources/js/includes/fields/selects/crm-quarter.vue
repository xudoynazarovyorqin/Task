<template>
    <el-row>
        <el-form-item :label="label || $t('message.quarter')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.quarter')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(quarter, index) in quarters" :key="'quarters-' + index" :label="quarter.name" :value="quarter.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name:'quarters',
        mixins: [mix],
        props:{
            quarter_id: {
                default: null
            },
            prop:{
                default: 'quarter_id'
            },
        },
        watch: {
            quarter_id: {
                handler: function() {
                    this.dispatch(this.quarter_id)
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.quarters && this.quarters.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                quarters: 'quarters/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'quarters/inventory'
            }),
        },
    }
</script>
