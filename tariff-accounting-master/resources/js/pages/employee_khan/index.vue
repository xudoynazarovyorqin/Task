


<template>
  <div class="app-container">
    <div class="mytabel table-sm mr-0 ml-0 p-0">
      <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-7 p-0 align-self-center font-weight-bold d-flex align-items-center">
          <h5 class="d-inline mr-2 font-weight-bold">Расписание</h5>
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
              :value-format="date_format"
              placeholder="от дата"
            ></el-date-picker>
            <el-date-picker
              class="ml-3"
              size="mini"
              v-model="filterForm.end_date"
              type="date"
              format="yyyy-MM-dd"
              :value-format="date_format"
              placeholder="до дата"
            ></el-date-picker>
          </div>
        </div>
        <div
          class="col-5 align-items-center align-self-center text-right pr-0 d-flex justify-content-end"
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

      <table
        class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white"
        v-loading="loadingData"
      >
        <thead>
          <tr>
            <th v-if="columns.id.show">{{ columns.id.title }}</th>
            <th v-if="columns.importer.show">{{ columns.importer.title }}</th>
            <th v-if="columns.cargo.show">{{ columns.cargo.title }}</th>
            <th v-if="columns.contCar.show">{{ columns.contCar.title }}</th>
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
            <!-- <th v-if="columns.importer.show">
              <el-select
                v-model="filterForm.importer"
                filterable
                clearable
                :placeholder="columns.importer.title"
                size="mini"
              >
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>
            </th>-->
            <th v-if="columns.importer.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.importer"
                :placeholder="columns.importer.title"
              ></el-input>
            </th>
            <th v-if="columns.cargo.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.cargo"
                :placeholder="columns.cargo.title"
              ></el-input>
            </th>
            <th v-if="columns.contCar.show">
              <el-input
                clearable
                size="mini"
                v-model="filterForm.contCar"
                :placeholder="columns.contCar.title"
              ></el-input>
            </th>

            <th v-if="columns.settings.show"></th>
          </tr>
        </thead>

        <transition-group name="flip-list" tag="tbody">
          <tr v-for="user in list" :key="user.id" class="cursor-pointer">
            <td v-if="columns.id.show">{{ user.id }}</td>
            <td v-if="columns.importer.show">{{ user.importer }}</td>
            <td v-if="columns.cargo.show">{{ user.cargo }}</td>
            <td v-if="columns.contCar.show">{{ user.contCar }}</td>
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
                  <el-dropdown-item
                    command="delete"
                    icon="el-icon-delete el-icon--left"
                    @click="open"
                  >
                    <el-button class="uadlit" @click="open">Удалить</el-button>
                  </el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </td>
          </tr>
        </transition-group>
      </table>
    </div>

    <el-drawer size="60%" :visible.sync="drawer" :with-header="false">
      <AddGroup />
    </el-drawer>
  </div>
</template>
<script>
import AddGroup from "./components/add-group";
export default {
  name: "employee_khan.index",
  data() {
    return {
      drawer: false,

      defaultProps: {
        children: "children",
        label: "label"
      },
      data_s: "",
      loadingData: false,
      drawer: false,
      filterForm: {
        search: "",
        id: "",
        importer: "",
        cargo: "",
        contCar: ""
      },
      list: [
        {
          id: "1",
          importer: "Eshmat  ",
          cargo: "Admin",
          contCar: "20.01.2020"
        },
        {
          id: "1",
          importer: "Eshmat  ",
          cargo: "Admin",
          contCar: "20.01.2020"
        },
        {
          id: "1",
          importer: "Eshmat  ",
          cargo: "Admin",
          contCar: "20.01.2020"
        }
      ],
      columns: {
        id: {
          show: true,
          title: "№",
          sortable: true,
          column: "id"
        },
        importer: {
          show: true,
          title: "Название",
          sortable: true,
          column: "importer"
        },
        cargo: {
          show: true,
          title: "Создатель ",
          sortable: true,
          column: "cargo"
        },
        contCar: {
          show: true,
          title: "Дата создания",
          sortable: true,
          column: "contCar"
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
  },
  components: {
    AddGroup
  },
  methods: {
    open() {
      this.$confirm("Это навсегда удалит файл. Продолжить?", "Предупреждение", {
        confirmButtonText: "Да",
        cancelButtonText: "Отмена",
        type: "warning"
      })
        .then(() => {
          this.$message({
            type: "success",
            message: "Удалить завершено"
          });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "Удалить отменено"
          });
        });
    }
  }
};
</script>

