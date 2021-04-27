<template>
    <el-row>
        <el-form-item :label="label || $t('message.status')" size="small" :prop="prop">
            <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.status')" filterable clearable :size="size">
                <el-option v-for="(status,index) in statuses" :key="'statuses-' + index" :label="status.name" :value="status.id"></el-option>
            </el-select>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        mixins: [mix],
        props: {
            status_id: {
                default: null,
            },
            prop:{
                default: 'status_id'
            }
        },
        watch: {
            status_id: {
                handler: function(newVal,oldVal) {
                    if (newVal) {
                        this.selected = this.status_id;
                    }
                },
                immediate: true,
                deep: true,
            },
        },
        mounted() {
            if (this.statuses && this.statuses.length === 0) this.loadStatuses();
        },
        computed: {
            ...mapGetters({
                statuses: 'contractClients/statuses'
            })
        },
        methods: {
            ...mapActions({
                loadStatuses: 'contractClients/statuses',
            })
        },
    }
</script>