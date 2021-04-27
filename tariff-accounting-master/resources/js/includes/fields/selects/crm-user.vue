<template>
    <el-row>
        <el-form-item :label="label || $t('message.user')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.user')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(user,index) in users" :key="'users' + index" :label="user.name" :value="user.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'users',
        mixins: [mix],
        props: {
            user_id:{
                default: null,
            },
            prop:{
                default: 'user_id'
            }
        },
        watch: {
            user_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.user_id;
                }
            },
            immediate:true,
            deep:true
        },
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