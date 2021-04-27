<template>
    <el-row>
        <el-form-item :label="label || $t('message.Score')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.Score')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(score,index) in items" :key="'scores' + index" :label="score.name" :value="score.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'scores',
        mixins: [mix],
        props: {
            score_id:{
                default: null,
            },
            currency_id:{
                default: null,
            },
            prop:{
                default: 'score_id'
            }
        },
        watch: {
            score_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.score_id;
                }
            },
            immediate:true,
            deep:true
        },
        mounted() {
            if (this.scores && this.scores.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                scores: 'scores/inventory'
            }),
            items: function() {
                return _.filter(this.scores, ['currency.id',this.currency_id])
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'scores/inventory'
            })
        },
    }
</script>