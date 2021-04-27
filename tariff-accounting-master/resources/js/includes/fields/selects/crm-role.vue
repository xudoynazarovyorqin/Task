<template>
    <el-row>
        <el-form-item :label="label || $t('message.role')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.role')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(role,index) in roles" :key="'roles-' + index" :label="role.name" :value="role.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_role')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-role-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'roles',
        mixins: [mix],
        components:{create},
        props:{
            role_id: {
                default: null
            },
            prop:{
                default: 'role_id'
            },
        },
        watch: {
            role_id: {
                handler: function() {
                    this.selected = this.role_id;
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.roles && this.roles.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                roles: 'roles/inventory'
            })
        },
        methods: {
            ...mapActions({
                updateInventory: 'roles/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.role.id)
                }
            }
        },
    }
</script>