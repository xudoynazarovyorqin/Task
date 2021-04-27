<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.role')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(role,index) in roles" :key="'roles' + index" :label="role.name" :value="role.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'roles',
        mixins: [mix],
        mounted() {
            if (this.roles && this.roles.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                roles: 'roles/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'roles/inventory'
            })
        },
    }
</script>