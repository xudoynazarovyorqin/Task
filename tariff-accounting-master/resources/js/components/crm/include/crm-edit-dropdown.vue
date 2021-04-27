<template>
    <el-dropdown @command="handleCommand">
        <el-button size="mini">
        Изменить <i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item command="delete" :disabled="!(items.length > 0)" icon="el-icon-delete el-icon--left"> Удалить</el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
</template>
<script>
    export default {
        props: {
            items: {type : Array, required: true}
        },
        data() {
            return {
            }
        },
        methods: {
            handleCommand(command) {
                if (command === 'delete') {
                    this.$confirm('Вы действительно хотите это сделать?', 'Предупреждение', {
                        confirmButtonText: 'Да',
                        cancelButtonText: 'Отмен',
                        type: 'warning'
                    }).then(() => {
                        this.$emit(command,this.items)
                    }).catch(() => {
                        this.$message({
                            type: 'warning',
                            message: 'Операция отменена'
                        });
                    });
                }
            }
        },
    }
</script>