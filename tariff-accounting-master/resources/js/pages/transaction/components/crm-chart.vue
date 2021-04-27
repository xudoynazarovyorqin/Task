<template>
  <el-row :gutter="20">
      <div>
        <div>
          <div>
            <crm-pie-chart :height="265" :data="dataChart"></crm-pie-chart>
          </div>
        </div>
      </div>
  </el-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import CrmPieChart from "@/pages/charts/crm-pie-chart";
export default {
  components: { CrmPieChart },
  data() {
    return {
      dataChart: {},
    };
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    ...mapActions({
      getAmountsAndCounts: "transactions/getAmountsAndCounts",
    }),

    async fetchData() {
      await this.getAmountsAndCounts()
        .then(res => {
          if (res.data.amounts) {
            let labels = [],
              data = [];

            labels.push('Payme');
            data.push(res.data.amounts.amount_payme);

            labels.push('Click');
            data.push(res.data.amounts.amount_click);

            labels.push('Paynet');
            data.push(res.data.amounts.amount_paynet);

            labels.push('Наличный');
            data.push(res.data.amounts.amount_cash);

            this.dataChart = {
              labels: labels,
              datasets: [
                {
                  label: this.$t("message.transactions"),
                  backgroundColor: [
                        '#00CCCC',
                        '#00A7F8',
                        '#00B101',
                        '#DD1B16'
                    ],

                  data: data
                }
              ]
            };
          }
        })
        .catch(err => {
          console.log(err);
        });
    },

    receiveBtnType(data) {
      if (data.last_total > data.before_total) {
        this.bg_color_class = "percent_bg_success";
        if (data.before_total == 0) return 100;
        return Math.ceil(
          100 *
            (data.last_total /
              (data.before_total > 0 ? data.before_total : 1)) -
            100
        );
      }
      if (data.last_total == data.before_total) {
        this.bg_color_class = "percent_bg_success";
        return 0;
      }
      if (data.last_total < data.before_total) {
        this.bg_color_class = "percent_bg_danger";
        if (data.last_total == 0) return -100;
        return -(
          Math.ceil(
            100 *
              (data.last_total /
                (data.before_total > 0 ? data.before_total : 1))
          ) - 100
        );
      }
    },
    changeRadio(val) {
      if (val == "monthly") {
        this.text_one = "На этой неделе";
        this.text_coler_one = "По сравнению с прошлой неделей";
        this.text_two = "На этой месяц";
        this.text_coler_two = "По сравнению с прошлой месяц";
      } else if (val == "yearly") {
        this.text_one = "На этой месяц";
        this.text_coler_one = "По сравнению с прошлой месяц";
        this.text_two = "В этом году";
        this.text_coler_two = "По сравнению с прошлым годом";
      }
    }
  }
};
</script>

<style scoped>
.percent_bg_success {
  color: #67c23a;
}
.percent_bg_danger {
  color: #ff9149;
}
</style>
