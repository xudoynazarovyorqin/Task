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
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList2.index', params: {id: $route.params.id} }">
            <!-- <i class="el-icon-view"></i> -->
            История платежи
          </router-link>
        </li>
        <li class="actionli">
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
          <el-col :span="24" class="mt-4">
            <el-tabs type="border-card">
              <el-tab-pane label=" История">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Дата добавлена</th>
                      <th scope="col">Сумма</th>
                      <th scope="col">Тип оплаты</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>2020-07-08</td>
                      <td>185 000 сум</td>
                      <td>
                        <div class="divr">ОПЛАТА НАЛИЧНЫЙ</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </el-tab-pane>
            </el-tabs>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "saleReadyProductList3",
  data() {
    return {};
  }
};
</script>

