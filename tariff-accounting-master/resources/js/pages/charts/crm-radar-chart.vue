<script>
    import { Radar } from 'vue-chartjs'

    export default {
        extends: Radar,
        props: {
            data: {
                type: Object,
                default: null
            },
            options: {
                type: Object,
                default: null
            }
        },
        watch: {
            data: {
                handler: function() {
                    this.$data._chart.destroy();
                    this.renderChart(this.data, this.options)
                },
                deep: true
            },
        },
        mounted () {
            this.renderChart(this.data, this.options)
        }
    }
</script>
