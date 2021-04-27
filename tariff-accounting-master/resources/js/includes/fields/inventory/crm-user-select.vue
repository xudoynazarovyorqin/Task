<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.user')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(user,index) in users" :key="'users-' + index" :label="user.name" :value="user.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'users',
        mixins: [mix],
        mounted() {
            if (this.users && this.users.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                users: 'users/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'users/inventory'
            })
        },
    }
</script>