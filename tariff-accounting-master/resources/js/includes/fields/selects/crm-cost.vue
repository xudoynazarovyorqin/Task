<template>
    <el-row>
        <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.cost')" filterable clearable :size="size" class="d-block">
            <el-option v-for="item in costs" :key="item.id" :label="item.name" :value="item.id"></el-option>
            <template slot="empty">
                <p class="cursor-pointer el-select-dropdown__empty" @click="showModal('create_cost')"
                >{{ $t('message.new') }} {{ $t('message.cost') | lowerFirst }}</p>
            </template>
        </el-select>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>
<script>
    import create from '@modals/crm-cost-create-modal';
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'costs',
        mixins: [mix],
        components:{create},
        props:{
            filter:{
                default: null,
            }
        },
        computed: {
            ...mapGetters({
                costs: 'costs/inventory'
            })
        },
        mounted() {
            if (this.costs && this.costs.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "costs/inventory",
            }),
            afterCreated(data) {
                if (data.created && data.created === true) {
                    this.$emit('append',data.cost)
                }
            },
            dispatch(e){
                this.$emit('append',_.find(this.costs, ['id',e]))
            }
        },
    }
</script>