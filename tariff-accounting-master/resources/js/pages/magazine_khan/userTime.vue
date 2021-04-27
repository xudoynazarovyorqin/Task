<template>
  <div class="app-container">
    <div class="mytabel table-sm mr-0 ml-0 p-0">
      <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-10 p-0 align-self-center font-weight-bold d-flex align-items-center">
          <h5 class="d-inline mr-2 font-weight-bold">Отчет по рабочему времени работнику Azimov A A</h5>
          <crm-refresh @c-click="refresh()"></crm-refresh>
          <div class="text-center d-flex sorddata ml-3">
            <el-input
              class="ml-3"
              size="mini"
              placeholder="Найти"
              prefix-icon="el-icon-search"
              v-model="search"
              clearable
            ></el-input>
            <el-date-picker
              class="ml-3"
              size="mini"
              v-model="start_date"
              type="date"
              format="yyyy-MM-dd"
              :value-format="date_format"
              placeholder="от дата"
            ></el-date-picker>
            <el-date-picker
              class="ml-3"
              size="mini"
              v-model="end_date"
              type="date"
              format="yyyy-MM-dd"
              :value-format="date_format"
              placeholder="до дата"
            ></el-date-picker>
          </div>
        </div>
        <div
          class="col-2 align-items-center align-self-center text-right pr-0 d-flex justify-content-end"
        >
          <el-button
            class="mr-2"
            size="mini"
            @click="drawer = true"
            icon="el-icon-circle-plus-outline"
          >Создать</el-button>
          <!-- <export-excel  class="btn excel_btn  mr-2" :data="list"
                        worksheet="Пользователи" name="Пользователи.xls">
                        <el-button size="mini" icon="el-icon-document-delete"> Excel </el-button>
          </export-excel>-->
          <el-dropdown class="mr-2">
            <el-button size="mini" icon="el-icon-setting" @click="drawer = true">
              <i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item v-for="(column,index) in columns" :key="index">
                <el-checkbox
                  :checked="column.show"
                  @change="column.show = !column.show"
                >{{ column.title }}</el-checkbox>
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </div>
      </div>
    </div>

    <table class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white">
      <thead>
        <tr>
          <th>Месяц</th>
          <th>Группы</th>
          <th>Дни недели</th>
          <th>Рабочая время</th>
          <th>check in</th>
          <th>check out</th>
          <th>Опаздания</th>
          <th>Сверхурочная</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td scope="row">21.01.2020</td>
          <td>AL 0157</td>
          <td>Понедельник</td>
          <td>От 09:00 до 18:00</td>
          <td class="red_off">11 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">2 часа</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">22.01.2020</td>
          <td>AL 0157</td>
          <td>Вторник</td>
          <td>От 09:00 до 18:00</td>
          <td class="yel_cer">10 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">2 часа</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">23.01.2020</td>
          <td>AL 0157</td>
          <td>Среда</td>
          <td>От 09:00 до 18:00</td>
          <td class="min_off">09 : 15</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">15 минут</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">24.01.2020</td>
          <td>AL 0157</td>
          <td>Четверг</td>
          <td>От 09:00 до 18:00</td>
          <td class="gre_on">09 : 00</td>
          <td class="gre_on">19 : 00</td>
          <td class="gre_on">0</td>
          <td class="gre_on">1 часа</td>
        </tr>
        <tr>
          <td scope="row">25.01.2020</td>
          <td>AL 0157</td>
          <td class="yel_cer">Пятьница</td>
          <td>От 09:00 до 13:00</td>
          <td class="red_off">x</td>
          <td class="red_off">x</td>
          <td class="gre_on">0</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">26.01.2020</td>
          <td>AL 0157</td>
          <td class="yel_cer">Субота</td>
          <td>От 09:00 до 13:00</td>
          <td class="gre_on">09 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="gre_on">0</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">27.01.2020</td>
          <td>AL 0157</td>
          <td class="red_off">Воскресения</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
        </tr>
        <tr>
          <td scope="row">21.01.2020</td>
          <td>AL 0157</td>
          <td>Понедельник</td>
          <td>От 09:00 до 18:00</td>
          <td class="red_off">11 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">2 часа</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">22.01.2020</td>
          <td>AL 0157</td>
          <td>Вторник</td>
          <td>От 09:00 до 18:00</td>
          <td class="yel_cer">10 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">2 часа</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">23.01.2020</td>
          <td>AL 0157</td>
          <td>Среда</td>
          <td>От 09:00 до 18:00</td>
          <td class="min_off">09 : 15</td>
          <td class="gre_on">18 : 00</td>
          <td class="red_off">15 минут</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">24.01.2020</td>
          <td>AL 0157</td>
          <td>Четверг</td>
          <td>От 09:00 до 18:00</td>
          <td class="gre_on">09 : 00</td>
          <td class="gre_on">19 : 00</td>
          <td class="gre_on">0</td>
          <td class="gre_on">1 часа</td>
        </tr>
        <tr>
          <td scope="row">25.01.2020</td>
          <td>AL 0157</td>
          <td class="yel_cer">Пятьница</td>
          <td>От 09:00 до 13:00</td>
          <td class="red_off">x</td>
          <td class="red_off">x</td>
          <td class="gre_on">0</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">26.01.2020</td>
          <td>AL 0157</td>
          <td class="yel_cer">Субота</td>
          <td>От 09:00 до 13:00</td>
          <td class="gre_on">09 : 00</td>
          <td class="gre_on">18 : 00</td>
          <td class="gre_on">0</td>
          <td class="gre_on">0</td>
        </tr>
        <tr>
          <td scope="row">27.01.2020</td>
          <td>AL 0157</td>
          <td class="red_off">Воскресения</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
          <td class="red_off">Выходной</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">Итого: 13 дней</th>
          <th>97 часов</th>
          <th colspan="2">91 часов 45 минут</th>
          <th>6 часа 15 минут</th>
          <th>2 часа</th>
        </tr>
      </tfoot>
    </table>
  </div>
</template>
<script>
export default {
  name: "userTime"
};
</script>
<style lang="scss" scoped>
.table th,
.table td {
  padding: 0.5rem;
  font-size: 13px;
  text-align: center;
}
.table-bordered td,
.table-bordered th {
  border: 1px solid #dee2e6;
}
table > tfoot > tr > th {
  color: rgba(0, 0, 0, 0.54);
}
</style>
