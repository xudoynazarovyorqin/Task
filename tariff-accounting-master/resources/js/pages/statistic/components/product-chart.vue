<template>
    <div class="col-6 col-lg-6 col-xl-6 col-xs-12 col-sm-12">
        <div class="bg-white p-2 shadow-sm">
            <div class="d-flex bd-highlight border-bottom pt-2">
                <div class="flex-grow-1 bd-highlight ml-2">Статистика по продукции(продажа)</div>

                <el-select v-model="product_id" placeholder="Продукция" size="small" class="mr-3">
                    <el-option label="Всё" value="0"></el-option>
                    <el-option v-for="item in products" :key="item.id + item.name" :label="item.name" :value="item.id"></el-option>
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
                product_id: '',
                text_for_label: 'Всё',
                dataChart: {}
            }
        },

        computed:{
            ...mapGetters({
                products: 'products/list',
            }),
        },

        watch: {
            radio: {
                handler: function () {
                    this.fetchData()
                }
            },

            product_id: {
                handler: function (new_value, old_value) {
                    this.fetchData()

                    let current_product = this.products.filter(function(item) {
                        return item.id == new_value;
                    });

                    if( current_product.length )
                    {
                        this.text_for_label = current_product[0].name;
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

            if (this.products && this.products.length === 0)
                this.loadProducts();
        },
        methods:{
            ...mapActions({
                productStatistic : 'statistics/productStatistic',

                loadProducts: 'products/index',
            }),
            fetchData(){
                this.productStatistic({ type: this.radio, product_id: this.product_id })
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
                                        backgroundColor: '#8AD2CD',
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
