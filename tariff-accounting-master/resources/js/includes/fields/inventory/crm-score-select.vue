<template>
    <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.score')" filterable clearable :size="size" class="d-block">
        <el-option v-for="(score,index) in scores" :key="'scores' + index" :label="score.name" :value="score.id"></el-option>
    </el-select>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/inventory';

    export default {
        name:'scores',
        mixins: [mix],
        mounted() {
            if (this.scores && this.scores.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                scores: 'scores/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'scores/inventory'
            })
        },
    }
</script>