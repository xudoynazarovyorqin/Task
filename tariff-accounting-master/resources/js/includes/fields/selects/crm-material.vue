<template>
    <el-row>
        <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.material')" filterable clearable :size="size" class="d-block">
            <el-option v-for="item in materials" :key="item.id" :label="item.name" :value="item.id"></el-option>
            <template slot="empty">
                <p class="cursor-pointer el-select-dropdown__empty" @click="showModal('create_material')"
                >{{ $t('message.new') }} {{ $t('message.material') | lowerFirst }}</p>
            </template>
        </el-select>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>
<script>
    import create from '@modals/crm-material-create-modal';
    import { mapGetters, mapActions } from 'vuex';
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'materials',
        mixins: [mix],
        components:{create},
        props:{
            filter:{
                default: null,
            }
        },
        computed: {
            ...mapGetters({
                materials: 'materials/inventory'
            })
        },
        mounted() {
            if (this.materials && this.materials.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "materials/inventory",
            }),
            afterCreated(data) {
                if (data.created && data.created === true) {
                    this.$emit('append',data.material)
                }
            },
            dispatch(e){
                this.$emit('append',_.find(this.materials, ['id',e]))
            }
        },
    }
</script>