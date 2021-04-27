<template>
    <el-dropdown>
        <el-button size="mini" icon="el-icon-setting">
            <i class="el-icon-arrow-down el-icon--right"></i>
        </el-button>
        <el-dropdown-menu slot="dropdown">
            <el-dropdown-item v-for="(column,index) in filtered_columns" :key="index">
                <el-checkbox :checked="column.show" @change="check(column.column, $event)">{{ column.title }}</el-checkbox>
            </el-dropdown-item>
        </el-dropdown-menu>
    </el-dropdown>
</template>
<style>
.el-dropdown-menu__item, .el-checkbox__label, .el-checkbox{
    font-size: 12px !important;
}
</style>
<script>
    export default {
        props: {
            columns: {type:Object}
        },
        computed: {
            filtered_columns: function() {
                let arr = [];
                for (const key in this.columns) {
                    if (this.columns.hasOwnProperty(key)) {
                        const element = this.columns[key];
                        if (element.hasOwnProperty('changeable')) {
                            if (element.changeable) {
                                arr.push(element)
                            }
                        }else{
                            arr.push(element)
                        }
                    }
                }
                return arr;
            }
        },
        methods:{
            check: function (column, event) {
                this.$emit('c-change',{ key: column, value: event});
            }
        }
    }
</script>
<style scoped>
.el-dropdown-menu__item>label{
    margin-bottom: 0.1rem !important;
}
.el-dropdown-menu{
    max-height: 720px !important;
    overflow-y: scroll;
}
</style>
