<template>
    <div class="col-6 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
        <div class="bg-white p-2 shadow-sm">
            <div class="d-flex bd-highlight border-bottom pt-2">
                <div class="flex-grow-1 bd-highlight ml-2">Статистика по сырьё(закупка)</div>

                <el-select v-model="material_id" placeholder="Сырьё" size="small" class="mr-3">
                    <el-option label="Всё" value="0"></el-option>
                    <el-option v-for="item in materials" :key="item.id + item.name" :label="item.name" :value="item.id"></el-option>
                </el-select>

                <div class="bd-highlight">
                    <el-radio-group v-model="radio" size="mini">
                        <el-radio-button value="weekly" label="weekly">Неделя</el-radio-button>
                        <el-radio-button value="monthly" label="monthly">Месяц</el-radio-button>
                        <el-radio-button value="yearly" label="yearly">Год</el-radio-button>
                    </el-radio-group>
                </div>
            </div>
            <div>
                <crm-line-chart :height="180" :data="dataChart"></crm-line-chart>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import CrmLineChart from "../../charts/crm-line-chart";
    export default {
        components: {CrmLineChart},
        data() {
            return {
                totalSum: 0,
                radio: 'weekly',
                material_id: '',
                text_for_label: 'Всё',
                dataChart: {}
            }
        },

        computed:{
            ...mapGetters({
                materials: 'materials/list',
            }),
        },

        watch: {
            radio: {
                handler: function () {
                    this.fetchData()
                }
            },

            material_id: {
                handler: function (new_value, old_value) {
                    this.fetchData()

                    let current_material = this.materials.filter(function(item) {
                        return item.id == new_value;
                    });

                    if( current_material.length )
                    {
                        this.text_for_label = current_material[0].name;
                    }
                    else
                    {
                        this.text_for_label = 'Всё';
                    }
                }
            }
        },
        mounted(){
            this.fetchData()

            if (this.materials && this.materials.length === 0)
                this.loadMaterials();
        },
        methods:{
            ...mapActions({
                materialStatistic : 'statistics/materialStatistic',

                loadMaterials: 'materials/index',
            }),
            fetchData(){
                this.materialStatistic({ type: this.radio, material_id: this.material_id })
                    .then(res => {
                        if (res.data.chart_data){
                            this.totalSum = 0;
                            let labels = [], data = [];
                            for (let key in res.data.chart_data){
                                res.data.chart_data.sort((a, b) => b.date - a.date);
                                if (res.data.chart_data.hasOwnProperty(key)){
                                    let chart_data = res.data.chart_data[key];
                                    labels.push(chart_data.label);
                                    data.push(chart_data.sum);
                                    this.totalSum +=chart_data.sum;
                                }
                            }
                            this.dataChart = {
                                labels: labels,
                                datasets: [
                                    {
                                        label: this.text_for_label,
                                        backgroundColor: '#FFFF5B',
                                        data: data
                                    }
                                ]
                            };
                        }
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        },
    }
</script>

<style scoped>

</style>