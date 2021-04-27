<template>
    <el-row>
        <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.product')" filterable clearable :size="size" class="d-block">
            <el-option v-for="item in items" :key="item.id" :label="item.name" :value="item.id"></el-option>
            <template slot="empty">
                <p class="cursor-pointer el-select-dropdown__empty" @click="showModal('create_product')"
                >{{ $t('message.new') }} {{ $t('message.product') | lowerFirst }}</p>
            </template>
        </el-select>
        <create @crm-close="afterCreated" v-if="modal"></create>
    </el-row>
</template>
<script>
    import create from '@modals/crm-product-create-modal';
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'products',
        mixins: [mix],
        components:{create},
        props:{
            filter:{
                default: null,
            },
            semi:{
                default: false,
            },
            modal:{
                default: true,
            }
        },
        computed: {
            ...mapGetters({
                products: 'products/inventory'
            }),
            items: function() {
                let firstFilter = !this.semi ? this.products : _.filter(this.products, function(o) { return o.production_type != 'assembly'});
                return _.filter(firstFilter, this.filter);
            }
        },
        mounted() {
            if (this.products && this.products.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "products/inventory",
            }),
            afterCreated(data) {
                if (data.created && data.created === true) {
                    this.$emit('append',data.product)
                }
            },
            dispatch(e){
                this.$emit('append',_.find(this.products, ['id',e]))
            }
        },
    }
</script>