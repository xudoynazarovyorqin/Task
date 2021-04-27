<template>
  <div class="app-container">
    <div class="mytabel table-sm mr-0 ml-0 p-0">
      <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
          <h5 class="d-inline mr-2 font-weight-bold">История транзакций</h5>
          <crm-refresh @c-click="refresh()"></crm-refresh>
          <div class="text-center d-flex sorddata ml-3">
            <el-input
              class="ml-3"
              size="mini"
              placeholder="Найти"
              prefix-icon="el-icon-search"
              v-model="filterForm.search"
              clearable
            ></el-input>
            <el-date-picker
              class="ml-3"
              size="mini"
              v-model="filterForm.start_date"
              type="date"
              format="yyyy-MM-dd"
              placeholder="от дата"
            ></el-date-picker>
            <el-date-picker
              class="ml-3"
              size="mini"
              v-model="filterForm.end_date"
              type="date"
              format="yyyy-MM-dd"
              placeholder="до дата"
            ></el-date-picker>
          </div>
        </div>
        <div
          class="col-5 align-items-center align-self-center text-right pr-0 d-flex justify-content-end"
        >
          <!-- <el-button
            class="mr-2"
            size="mini"
            @click="tableID = true"
            icon="el-icon-circle-plus-outline"
          >Создать</el-button>
          <el-dropdown class="mr-2">
            <el-button size="mini" icon="el-icon-setting">
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
          </el-dropdown>-->
        </div>
      </div>
      <table
        class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white"
        v-loading="loadingData"
      >
        <thead>
          <tr>
            <th v-if="columns.id.show">{{ columns.id.title }}</th>
            <th v-if="columns.user.show">{{ columns.user.title }}</th>
            <th v-if="columns.modelType.show">{{ columns.modelType.title }}</th>
            <th v-if="columns.payme.show">{{ columns.payme.title }}</th>
            <th v-if="columns.summa.show">{{ columns.summa.title }}</th>
            <th v-if="columns.settings.show">{{ columns.settings.title }}</th>
          </tr>

          <tr>
            <th v-if="columns.id.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.id"
                class="id_input"
                :placeholder="columns.id.title"
              ></el-input>
            </th>
            <th v-if="columns.user.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.user"
                :placeholder="columns.user.title"
              ></el-input>
            </th>
            <th v-if="columns.modelType.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.modelType"
                :placeholder="columns.modelType.title"
              ></el-input>
            </th>
            <th v-if="columns.payme.show">
              <el-select
                v-model="filterForm.payme"
                filterable
                clearable
                :placeholder="columns.payme.title"
                size="mini"
              >
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>
            </th>
            <th v-if="columns.summa.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.summa"
                :placeholder="columns.summa.title"
              ></el-input>
            </th>

            <th v-if="columns.settings.show"></th>
          </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
          <tr v-for="user in list" :key="user.id" class="cursor-pointer">
            <td v-if="columns.id.show">{{ user.id }}</td>
            <td v-if="columns.user.show">{{ user.user }}</td>
            <td v-if="columns.modelType.show">{{ user.modelType }}</td>
            <td v-if="columns.payme.show">{{ user.payme }}</td>
            <td v-if="columns.summa.show">{{ user.summa }}</td>
            <td v-if="columns.settings.show" class="settings-td">
              <el-dropdown szie="mini">
                <el-button size="mini" icon="el-icon-setting" round>
                  <i class="el-icon-arrow-down"></i>
                </el-button>
                <el-dropdown-menu slot="dropdown" size="mini">
                  <el-dropdown-item command="edit" icon="el-icon-edit el-icon--left">Изменить</el-dropdown-item>
                  <el-dropdown-item command="print" icon="el-icon-printer el-icon--left">Печать</el-dropdown-item>
                  <!-- <el-dropdown-item v-if="permissions.includes(name+'.'+'show')" command="show"  icon="el-icon-view el-icon--left"> Показать</el-dropdown-item> -->
                  <el-dropdown-item
                    command="back_material"
                    icon="el-icon-refresh-left el-icon--left"
                  >Возврат сырья</el-dropdown-item>
                  <el-dropdown-item
                    command="comments"
                    icon="el-icon-chat-line-square el-icon--left"
                  >Комментарий</el-dropdown-item>
                  <el-dropdown-item command="delete" icon="el-icon-delete el-icon--left">
                    <el-button class="uadlit">Удалить</el-button>
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </td>
          </tr>
        </transition-group>
      </table>
    </div>
  </div>
</template>
<script>
export default {
  name: "transactionHistor",
  data() {
    return {
      data_s: "",
      loadingData: false,
      drawer: false,
      form: {},
      filterForm: {
        search: "",
        id: "",
        user: "",
        modelType: "",
        payme: "",
        summa: ""
      },
      list: [
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        },
        {
          id: "1",
          user: "Azimov A ",
          modelType: "22.05.2020 13:04",
          payme: "PAYME",
          summa: "noto'g'ri to'lov"
        }
      ],
      columns: {
        id: {
          show: true,
          title: "№",
          sortable: true,
          column: "id"
        },
        user: {
          show: true,
          title: "Пользователь",
          sortable: true,
          column: "user"
        },
        modelType: {
          show: true,
          title: "Дата",
          sortable: true,
          column: "modelType"
        },

        payme: {
          show: true,
          title: "Тип модели",
          sortable: true,
          column: "payme"
        },
        summa: {
          show: true,
          title: "Комментарий",
          sortable: true,
          column: "summa"
        },
        settings: {
          show: true,
          title: "Настройки",
          sortable: false,
          column: "settings"
        }
      },
      tableID: false,
      tableUser: false
    };
  }
};
</script>
