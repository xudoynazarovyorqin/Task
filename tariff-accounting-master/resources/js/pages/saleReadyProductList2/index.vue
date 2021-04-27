<template>
  <div>
    <el-breadcrumb separator="/" class="mb-3">
      <el-breadcrumb-item :to="{ name: 'applications.index' }">{{ $t("message.applications") }}</el-breadcrumb-item>
      <el-breadcrumb-item>{{ $t("message.show_application") }}</el-breadcrumb-item>
    </el-breadcrumb>

    <div class="user_info">
      <ul class="user_info_top_menu">
        <li>
          <router-link slot="title" :to="{ name: 'applications.show', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            Информация о заказе
          </router-link>
        </li>
        <li class="actionli">
          <router-link slot="title" :to="{ name: 'saleReadyProductList2.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            История платежи
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList3.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            История транзакций
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList4.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            Журнал действий
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList5.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            СМС Оповещения
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList6.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            Печать документов
          </router-link>
        </li>
      </ul>
      <div class="p-4">
        <el-row :gutter="20">
          <el-col :span="24" class="mt-2">
            <el-collapse accordion>
              <el-collapse-item name="1">
                <template slot="title">
                  <i class="el-icon-s-order"></i>
                  <span class="font_weight">Тариф Max</span>
                  <span>12 месячные</span>
                </template>
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr class="text-center bg_color_table">
                        <th scope="col">Тариф</th>
                        <th scope="col">Начальная дата</th>
                        <th scope="col">Первоначальный платеж</th>
                        <th scope="col">Ежемесячная сумма</th>
                        <th scope="col">Общая оплата: 00 000 000 сум</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="text-center bg_color_table">
                        <td>12 Max</td>
                        <td>2020-07-10</td>
                        <td>00 000 000 сум</td>
                        <td>00 000 000 сум</td>
                        <td>00 000 000 сум</td>
                      </tr>
                    </tbody>
                  </table>

                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">Какой месяц</th>
                        <th scope="col">Ежемесячная сумма</th>
                        <th scope="col">Общая сумма</th>
                        <th scope="col">Oплаченная сумма</th>
                        <th scope="col">Неоплаченная сумма</th>
                        <th scope="col">Статус</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>2020-07-10</td>
                        <td>0 сум</td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">0 сум</span>
                        </td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">оплачена</span>
                        </td>
                      </tr>
                      <tr>
                        <td>2020-07-10</td>
                        <td>0 сум</td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">0 сум</span>
                        </td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">оплачена</span>
                        </td>
                      </tr>
                      <tr>
                        <td>2020-07-10</td>
                        <td>0 сум</td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">0 сум</span>
                        </td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">оплачена</span>
                        </td>
                      </tr>
                      <tr>
                        <td>2020-07-10</td>
                        <td>0 сум</td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">0 сум</span>
                        </td>
                        <td>0 сум</td>
                        <td>
                          <span class="act_color">оплачена</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </el-collapse-item>
            </el-collapse>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "saleReadyProductList2",
  data() {
    return {
      activeNames: ["1"]
    };
  },
  methods: {
    handleChange(val) {
      console.log(val);
    }
  }
};
</script>

