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
        <li class="actionli">
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
              <el-tab-pane label="История сообщений">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#ID</th>
                      <th scope="col">Дата</th>
                      <th scope="col">Клиент</th>
                      <th scope="col">Телефон</th>
                      <th scope="col">Текст</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </el-tab-pane>
            </el-tabs>
          </el-col>
          <el-col :span="12" class="mt-4">
            <el-form ref="form" :model="form" class="mt-3 style__label">
              <el-form-item label="Отправить сообщение">
                <el-input type="textarea" v-model="form.desc"></el-input>
              </el-form-item>
              <!-- end  el-form-item-->

              <el-button type="success" round>Отправить</el-button>
            </el-form>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "saleReadyProductList5",
  data() {
    return {
      form: {}
    };
  }
};
</script>

