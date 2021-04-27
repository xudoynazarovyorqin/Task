<template>
    <div class="bg-white p-2 shadow-sm">
        <div class="d-flex bd-highlight border-bottom pt-2">
            <div class="flex-grow-1 bd-highlight">{{ $t('message.buy_ready_products')}} : <span class="ml-3">  {{ totalSum | formatMoney }}  </span></div>
            <div class="bd-highlight">
                <el-radio-group v-model="radio" size="mini">
                    <el-radio-button value="weekly" label="weekly">{{ $t('message.weekly')}}</el-radio-button>
                    <el-radio-button value="monthly" label="monthly">{{ $t('message.monthly')}}</el-radio-button>
                    <el-radio-button value="yearly" label="yearly">{{ $t('message.yearly')}}</el-radio-button>
                </el-radio-group>
            </div>
        </div>
        <div>
            <crm-doughnut-chart :height="180" :data="dataChart"></crm-doughnut-chart>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    import CrmDoughnutChart from "@/pages/charts/crm-bar-chart";
    export default {
        components: {CrmDoughnutChart},
        data() {
            return {
                totalSum: 0,
                radio: 'weekly',
                dataChart: {}
            }
        },
        watch: {
            radio: {
                handler: function () {
                    this.fetchData()
                }
            }
        },
        mounted(){
            this.fetchData()
        },
        methods:{
            ...mapActions({
                editChart : 'buyReadyProducts/chart'
            }),
            fetchData(){
                this.editChart(this.radio)
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
                                        label: this.$t('message.buy_ready_products'),
                                        backgroundColor: '#5cb8f8',
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
