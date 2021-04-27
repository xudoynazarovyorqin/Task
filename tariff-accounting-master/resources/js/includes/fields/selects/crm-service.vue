<template>
    <el-row>
        <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.service')" filterable clearable :size="size" class="d-block">
            <el-option v-for="item in services" :key="item.id" :label="item.name" :value="item.id"></el-option>            
        </el-select>        
    </el-row>
</template>
<script>
    import mix from '@mixins/fields/form-item';
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: 'services',
        mixins: [mix],
        computed: {
            ...mapGetters({
                services: 'services/inventory'
            })
        },
        mounted() {
            if (this.services && this.services.length === 0) this.updateInventory();
        },
        methods: {
            ...mapActions({
                updateInventory: "services/inventory",
            }),            
            dispatch(e){
                this.$emit('append',_.find(this.services, ['id',e]))
            }
        },
    }
</script>