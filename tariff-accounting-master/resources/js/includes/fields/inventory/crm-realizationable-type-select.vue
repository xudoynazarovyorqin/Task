<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.Type document')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(val, key) in items" :key="'Type document-' + key" :label="val" :value="key"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'realization-types',
        mixins: [mix],
        computed: {
            ...mapGetters({
                items: 'realizations/realization_types'
            })
        }
    }
</script>