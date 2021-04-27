<template>
  <el-row :gutter="20" class="mb-4">
    <el-col :span="16">
      <div class="bar16 car_box overflow-hidden" v-can="'applications.chart'">
        <div class="bg-white p-2 shadow-sm">
          <div class="d-flex bd-highlight border-bottom pt-2">
            <div class="flex-grow-1 bd-highlight">
              {{ $t('message.amount') }}:
              <span class="ml-3">{{ totalSum | formatMoney }}</span>
            </div>
            <div class="bd-highlight">
              <el-radio-group v-model="radio" size="mini">
                <el-radio-button value="weekly" label="weekly">{{ $t('message.weekly')}}</el-radio-button>
                <el-radio-button value="monthly" label="monthly">{{ $t('message.monthly')}}</el-radio-button>
                <el-radio-button value="yearly" label="yearly">{{ $t('message.yearly')}}</el-radio-button>
              </el-radio-group>
            </div>
          </div>
          <div>
            <crm-line-chart :height="180" :data="dataChart"></crm-line-chart>
          </div>
        </div>
      </div>
    </el-col>

    <el-col :span="8">
      <div class="car_box row__progress ClassHight">
        <el-row :gutter="20" class="style_elRow" style="padding: 10px;">
          <el-col :span="24">
            <div class="d-flex">
              <div class="blocks_title mr-3">{{ $t('message.application') }}</div>
            </div>
          </el-col>
        </el-row>
        <div class="mytable_row chart_H height350 dd_flex">
          <el-row :gutter="10" class="right__bar p-2 mb-4">
            <el-col :span="24">
              <div class="right_bart_sec_info">{{text_one | lowerCase | capitalize}}</div>
            </el-col>
            <el-col :span="12">
              <div>{{data_one.last_count ? data_one.last_count : 0 | formatNumber}}</div>
              <span>{{ $t('message.applications') }}</span>
            </el-col>
            <el-col :span="12">
              <div>{{data_one.last_total ? data_one.last_total : 0 | formatNumber}}</div>
              <span>сум</span>
            </el-col>
            <el-col :span="24">
              <div>{{data_one.last_total ? data_one.last_total : 0 - data_one.before_total ? data_one.before_total : 0 | formatNumber}} сум ({{receiveBtnType(data_one)}}%)</div>
              <span :class="bg_color_class">{{text_coler_one}}</span>
            </el-col>
          </el-row>

          <el-row :gutter="10" class="right__bar p-2">
            <el-col :span="24">
              <div class="right_bart_sec_info">{{text_two}}</div>
            </el-col>
            <el-col :span="12">
              <div>{{data_two.last_count ? data_two.last_count : 0 | formatNumber}}</div>
              <span>{{ $t('message.applications') }}</span>
            </el-col>
            <el-col :span="12">
              <div>{{data_two.last_total ? data_two.last_total : 0 | formatNumber}}</div>
              <span>сум</span>
            </el-col>
            <el-col :span="24">
              <div>{{data_two.last_total ? data_two.last_total : 0 - data_two.before_total ? data_two.before_total : 0 | formatNumber}} сум ({{receiveBtnType(data_two)}}%)</div>
              <span :class="bg_color_class">{{text_coler_two}}</span>
            </el-col>
          </el-row>
        </div>
      </div>
    </el-col>
  </el-row>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import CrmLineChart from "@/pages/charts/crm-line-chart";
export default {
  components: { CrmLineChart },
  data() {
    return {
      totalSum: 0,
      radio: "weekly",
      dataChart: {},
      data_one: {},
      data_two: {},
      bg_color_class: "percent_bg_success",
      text_one: "",
      text_two: "",
      text_coler_one: "",
      text_coler_two: ""
    };
  },
  watch: {
    radio: {
      handler: function(val) {
        this.fetchData();
        this.changeRadio(val);
      }
    }
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    ...mapActions({
      editChart: "applications/chart"
    }),

    async fetchData() {
      await this.editChart(this.radio)
        .then(res => {
          this.data_one = {};
          this.data_one = res.data.data_one;
          this.data_two = res.data.data_two;
          if (this.radio == "weekly" && this.data_one) {
            this.text_one =
              "Сегодня, " +
              this.data_two.label_week +
              " " +
              this.data_one.label_day +
              " " +
              this.data_one.label_month;
            this.text_coler_one = "По сравнению со вторником";
            this.text_two = "На этой неделе";
            this.text_coler_two = "По сравнению с прошлой неделей";
          }

          if (res.data.chart_data) {
            this.totalSum = 0;
            let labels = [],
              data = [];
            for (let key in res.data.chart_data) {
              res.data.chart_data.sort((a, b) => b.date - a.date);
              if (res.data.chart_data.hasOwnProperty(key)) {
                let chart_data = res.data.chart_data[key];
                labels.push(chart_data.label);
                data.push(chart_data.sum);
                this.totalSum += chart_data.sum;
              }
            }
            this.dataChart = {
              labels: labels,
              datasets: [
                {
                  label: this.$t("message.applications"),
                  backgroundColor: "#5cb8f8",
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
