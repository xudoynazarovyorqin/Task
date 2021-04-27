<template>
  <div>
    <el-breadcrumb separator="/" class="mb-3">
      <el-breadcrumb-item :to="{ name: 'applications.index' }">{{ $t("message.applications") }}</el-breadcrumb-item>
      <el-breadcrumb-item>{{ $t("message.show_application") }}</el-breadcrumb-item>
    </el-breadcrumb>

    <div class="user_info">
      <ul class="user_info_top_menu">
        <li class="actionli">
          <router-link slot="title" :to="{ name: 'saleReadyProductList.index' }">
            <!-- <i class="el-icon-view"></i> -->
            Информация о заказе
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList2.index' }">
            <!-- <i class="el-icon-view"></i> -->
            История платежи
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList3.index' }">
            <!-- <i class="el-icon-view"></i> -->
            История транзакций
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList4.index' }">
            <!-- <i class="el-icon-view"></i> -->
            Журнал действий
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList5.index' }">
            <!-- <i class="el-icon-view"></i> -->
            СМС Оповещения
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList6.index' }">
            <!-- <i class="el-icon-view"></i> -->
            Печать документов
          </router-link>
        </li>
      </ul>
      <div class="p-4">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> Продажа
                </span>
              </div>
              <div class="text item">
                <div class="blockas_eliment">
                  <span class="font_weight">Создан</span>
                  <span>2020-07-03 19:14:05</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">Приоритет</span>
                  <span>Очен важний</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">Статус</span>
                  <span>Активный</span>
                </div>
              </div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> Продажа
                </span>
              </div>
              <div class="text item">
                <div class="blockas_eliment">
                  <span class="font_weight">Имя</span>
                  <span>NURALIYEV SHERZOD ABDIMANNANOVICH</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">Телефон</span>
                  <span>+99890 000 00 00</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">Адрес</span>
                  <span>TOSHKENT SHAHAR CHILONZOR TUMANI USMUR</span>
                </div>
              </div>
            </el-card>
          </el-col>

          <el-col :span="24" class="mt-4">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> Тариф
                </span>
              </div>
              <div class="text item">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">Наименование продукции</th>
                      <th scope="col">Ед. изм.</th>
                      <th scope="col">Количество</th>
                      <th scope="col">Цена(шт)</th>
                      <th scope="col">Общая сумма</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>ТЕЛЕФОН REDMI Note 8 Pro 128G (Gray)</td>
                      <td>шт</td>
                      <td>1</td>
                      <td>2 669 000 сум</td>
                      <td>2 669 000 сум</td>
                    </tr>
                    <tr>
                      <th colspan="4">Итого</th>
                      <th>12 016 949,15 сум</th>
                    </tr>
                  </tbody>
                </table>
              </div>
            </el-card>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "saleReadyProductList",
  data() {
    return {};
  }
};
</script>
