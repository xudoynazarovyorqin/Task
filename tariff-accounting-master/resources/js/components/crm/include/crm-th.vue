<template>
    <th v-if="column.sortable && column.show" :ref="column.column" @click="sortByColumn(column.column)"> {{ column.title }} <span class="sortIcon"></span></th>
    <th v-else-if="column.show"> {{ column.title }}</th>
</template>
<script>
    export default {
        props: {
            column: {type: Object, required: true},
            sort: {type: Object,required: true}
        },
        methods: {
            sortByColumn(column) {
                if (column === this.sort.column) {
                    this.$emit('c-change',{column: column, order: (this.sort.order === 'asc') ? 'desc' : 'asc'})
                } else {
                    this.$emit('c-change',{column: column, order: 'asc'})
                }
                $('span.sortIcon').html('');
                this.$refs[column].children[0].innerHTML  = (this.sort.order === 'asc') ? `<i class="el-icon-top ml-2 bold"></i>` : `<i class="el-icon-bottom ml-2"></i>`;
            },
        },
    }
</script>
