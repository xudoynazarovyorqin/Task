
<template>
  <div class="app-container">
    <div class="mytabel table-sm mr-0 ml-0 p-0">
      <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-9 p-0 align-self-center font-weight-bold d-flex align-items-center">
          <h5 class="d-inline mr-2 font-weight-bold">Группа сотрудников</h5>
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
          class="col-3 align-items-center align-self-center text-right pr-0 d-flex justify-content-end"
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

    <div class="container2">
      <aside>
        <div class="left_bar_a">
          <div class="add_gruppa">
            <div class="div" @click="centerDialogVisible = true">
              <i class="el-icon-circle-plus-outline"></i> Добавить группа
            </div>
          </div>
          <div class="tree__checkbox block">
            <el-tree
              :data="data"
              show-checkbox
              node-key="id"
              default-expand-all
              :expand-on-click-node="false"
            >
              <span class="custom-tree-node but__text__tree" slot-scope="{ node, data }">
                <span class="tect__tree">{{ node.label }}</span>
                <span class="but__tree">
                  <el-button size="mini" type="success" icon="el-icon-plus" circle></el-button>
                  <el-button size="mini" type="primary" icon="el-icon-edit" circle></el-button>
                  <el-button
                    size="mini"
                    type="danger"
                    icon="el-icon-delete"
                    circle
                    @click="() => remove(node, data)"
                  ></el-button>
                  <!-- <el-button type="text" icom=""  size="mini" @click="() => append(data)">Append</el-button> -->
                  <!-- <el-button type="text" icon="el-icon-delete" size="mini" @click="() => remove(node, data)"></el-button> -->
                </span>
              </span>
            </el-tree>
          </div>
        </div>
      </aside>
      <div class="resize-handle--x" data-target="aside">
        <i class="el-icon-arrow-left"></i>
        <i class="el-icon-arrow-right"></i>
      </div>
      <main>
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
              <th v-if="columns.allTn.show">{{ columns.allTn.title }}</th>

              <th v-if="columns.status.show">{{ columns.status.title }}</th>
              <th v-if="columns.numberStorage.show">{{ columns.numberStorage.title }}</th>
              <th v-if="columns.totalAmount.show">{{ columns.totalAmount.title }}</th>
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
              <th v-if="columns.importer.show">
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
              <th v-if="columns.allTn.show">
                <el-input
                  clearable
                  size="mini"
                  v-model="filterForm.allTn"
                  :placeholder="columns.allTn.title"
                ></el-input>
              </th>
              <th v-if="columns.acceptance.show">
                <el-date-picker
                  :placeholder="columns.acceptance.title"
                  v-model="filterForm.acceptance"
                  size="mini"
                ></el-date-picker>
              </th>

              <th v-if="columns.status.show">
                <el-select
                  v-model="filterForm.status"
                  filterable
                  clearable
                  :placeholder="columns.status.title"
                  size="mini"
                >
                  <el-option label="Zone one" value="shanghai"></el-option>
                  <el-option label="Zone two" value="beijing"></el-option>
                </el-select>
              </th>
              <th v-if="columns.numberStorage.show">
                <el-input
                  clearable
                  size="mini"
                  v-model="filterForm.numberStorage"
                  :placeholder="columns.numberStorage.title"
                ></el-input>
              </th>
              <th v-if="columns.totalAmount.show">
                <el-input
                  clearable
                  size="mini"
                  v-model="filterForm.totalAmount"
                  :placeholder="columns.totalAmount.title"
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
              <td v-if="columns.allTn.show">{{ user.allTn }}</td>
              <td v-if="columns.acceptance.show">{{ user.acceptance }}</td>
              <td
                v-if="columns.status.show"
              >{{ (user.status === 'active') ? 'Активний' : 'Не астивний' }}</td>
              <td v-if="columns.numberStorage.show">{{ user.numberStorage }}</td>
              <td v-if="columns.totalAmount.show">{{ user.totalAmount }}</td>
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
      </main>
    </div>

    <el-dialog
      title=" Добавить группа"
      :visible.sync="centerDialogVisible"
      width="30%"
      class="modal_icon_ix madali_add_grup"
    >
      <el-form ref="form" :model="form" class="style__label">
        <el-form-item label="Название">
          <el-input v-model="form.name"></el-input>
        </el-form-item>
        <el-row class="but_footer_style">
          <el-button type="success">Создать</el-button>
          <el-button type="danger">Закрыть</el-button>
        </el-row>
      </el-form>
    </el-dialog>

    <el-drawer size="80%" :visible.sync="drawer" :with-header="false">
      <AddGroup />
    </el-drawer>
  </div>
</template>
<script>
import AddGroup from "./components/add-group";
export default {
  name: "employeeGroupkhan.index",
  data() {
    return {
      form: {},
      centerDialogVisible: false,
      drawer: false,
      data: [
        {
          label: "Level one 1",
          children: [
            {
              label: "Level two 1-1",
              children: [
                {
                  label: "Level three 1-1-1"
                }
              ]
            }
          ]
        },
        {
          label: "Level one 2",
          children: [
            {
              label: "Level two 2-1",
              children: [
                {
                  label: "Level three 2-1-1"
                }
              ]
            },
            {
              label: "Level two 2-2",
              children: [
                {
                  label: "Level three 2-2-1"
                }
              ]
            }
          ]
        },
        {
          label: "Level one 3",
          children: [
            {
              label: "Level two 3-1",
              children: [
                {
                  label: "Level three 3-1-1"
                }
              ]
            },
            {
              label: "Level two 3-2",
              children: [
                {
                  label: "Level three 3-2-1"
                }
              ]
            }
          ]
        }
      ],
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
        contCar: "",
        allTn: "",
        acceptance: "",
        status: "",
        numberStorage: "",
        totalAmount: ""
      },
      list: [
        {
          id: "1",
          importer: "Eshmat  ",
          cargo: "+998 99 789 56 23",
          contCar: " Admin role ",
          allTn: "Зав.Склад и",
          acceptance: "Руковадител",
          status: " 20.02.19 ",
          numberStorage: "05.01.2015 ",
          totalAmount: "205 часов"
        },
        {
          id: "2",
          importer: "Eshmat  ",
          cargo: "+998 99 789 56 23",
          contCar: " Admin role ",
          allTn: "Зав.Склад и",
          acceptance: "Руковадител",
          status: " 20.02.19 ",
          numberStorage: "05.01.2015 ",
          totalAmount: "205 часов"
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
          title: "Имя",
          sortable: true,
          column: "importer"
        },
        cargo: {
          show: true,
          title: "Telefon",
          sortable: true,
          column: "cargo"
        },
        contCar: {
          show: true,
          title: "Роль",
          sortable: true,
          column: "contCar"
        },
        allTn: {
          show: true,
          title: "Должность",
          sortable: true,
          column: "allTn"
        },
        acceptance: {
          show: true,
          title: "Приемка ",
          sortable: true,
          column: "acceptance"
        },

        status: {
          show: true,
          title: "гр.Сотрудников",
          sortable: true,
          column: "status"
        },
        numberStorage: {
          show: true,
          title: "Дата Принятия",
          sortable: true,
          column: "numberStorage"
        },
        totalAmount: {
          show: true,
          title: "Месячный норматив",
          sortable: true,
          column: "totalAmount"
        },
        settings: {
          show: true,
          title: "Настройки",
          sortable: false,
          column: "settings"
        }
      },
      tableID: false,
      tableUser: false,
      data: [
        {
          id: 1,
          label: "Level one 2",
          children: [
            {
              id: 5,
              label: "Level two 2-1"
            },
            {
              id: 6,
              label: "Level two 2-2"
            }
          ]
        },
        {
          id: 2,
          label: "Level one 2",
          children: [
            {
              id: 5,
              label: "Level two 2-1"
            },
            {
              id: 6,
              label: "Level two 2-2"
            }
          ]
        },
        {
          id: 3,
          label: "Level one 3",
          children: [
            {
              id: 7,
              label: "Level two 3-1"
            },
            {
              id: 8,
              label: "Level two 3-2"
            }
          ]
        }
      ]
    };
  },
  components: {
    AddGroup
  },
  mounted() {
    const selectTarget = (fromElement, selector) => {
      if (!(fromElement instanceof HTMLElement)) {
        return null;
      }

      return fromElement.querySelector(selector);
    };

    const resizeData = {
      tracking: false,
      startWidth: null,
      startCursorScreenX: null,
      handleWidth: 10,
      resizeTarget: null,
      parentElement: null,
      maxWidth: null
    };

    $(document.body).on("mousedown", ".resize-handle--x", null, event => {
      if (event.button !== 0) {
        return;
      }

      event.preventDefault();
      event.stopPropagation();

      const handleElement = event.currentTarget;

      if (!handleElement.parentElement) {
        console.error(new Error("Parent element not found."));
        return;
      }

      // Use the target selector on the handle to get the resize target.
      const targetSelector = handleElement.getAttribute("data-target");
      const targetElement = selectTarget(
        handleElement.parentElement,
        targetSelector
      );

      if (!targetElement) {
        console.error(new Error("Resize target element not found."));
        return;
      }

      resizeData.startWidth = $(targetElement).outerWidth();
      resizeData.startCursorScreenX = event.screenX;
      resizeData.resizeTarget = targetElement;
      resizeData.parentElement = handleElement.parentElement;
      resizeData.maxWidth =
        $(handleElement.parentElement).innerWidth() - resizeData.handleWidth;
      resizeData.tracking = true;

      console.log("tracking started");
    });

    $(window).on(
      "mousemove",
      null,
      null,
      _.debounce(event => {
        if (resizeData.tracking) {
          const cursorScreenXDelta =
            event.screenX - resizeData.startCursorScreenX;
          const newWidth = Math.min(
            resizeData.startWidth + cursorScreenXDelta,
            resizeData.maxWidth
          );

          $(resizeData.resizeTarget).outerWidth(newWidth);
        }
      }, 1)
    );

    $(window).on("mouseup", null, null, event => {
      if (resizeData.tracking) {
        resizeData.tracking = false;

        console.log("tracking stopped");
      }
    });
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
    },
    remove(node, data) {
      const parent = node.parent;
      const children = parent.data.children || parent.data;
      const index = children.findIndex(d => d.id === data.id);
      children.splice(index, 1);
    }
  }
};
</script>

<style lang="scss" scoped>
.container2 {
  width: 100%;
  overflow: hidden;
  //   background-color: white;
}
.container2 {
  display: -ms-flexbox;
  display: -webkit-flex;
  display: flex;
  -webkit-flex-direction: row;
  -ms-flex-direction: row;
  flex-direction: row;
  -webkit-flex-wrap: nowrap;
  -ms-flex-wrap: nowrap;
  flex-wrap: nowrap;
  -webkit-justify-content: flex-start;
  -ms-flex-pack: start;
  justify-content: flex-start;
  -webkit-align-content: stretch;
  -ms-flex-line-pack: stretch;
  align-content: stretch;
  -webkit-align-items: stretch;
  -ms-flex-align: stretch;
  align-items: stretch;
}
.container2 > aside {
  -webkit-order: 0;
  -ms-flex-order: 0;
  order: 0;
  -webkit-flex: 0 0 auto;
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  -webkit-align-self: auto;
  -ms-flex-item-align: auto;
  align-self: auto;
  //   background-color: #fff;
  min-width: 50px;
}
.container2 > main {
  -webkit-order: 0;
  -ms-flex-order: 0;
  order: 0;
  -webkit-flex: 1 1 auto;
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  -webkit-align-self: auto;
  -ms-flex-item-align: auto;
  align-self: auto;
}
.container2 > aside,
.container2 > main {
  //   padding: 10px;
  overflow: auto;
}
.resize-handle--x {
  -webkit-flex: 0 0 auto;
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  position: relative;
  box-sizing: border-box;
  //   width: 3px;
  //   height: 100%;
  //   border-left-width: 1px;
  //   border-left-style: solid;
  //   border-left-color: black;
  //   border-right-width: 1px;
  //   border-right-style: solid;
  //   border-right-color: black;
  cursor: ew-resize;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background: #fff;
  width: 40px;
  height: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  -webkit-box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);
  margin-top: 10px;
  margin-left: -22px;
  z-index: 9;
}
.resize-handle--x:before {
  content: "";
  position: absolute;
  z-index: 1;
  top: 50%;
  right: 100%;
  height: 18px;
  //   width: 2px;
  margin-top: -9px;
  //   border-left-color: black;
  //   border-left-width: 1px;
  //   border-left-style: solid;
}
.resize-handle--x:after {
  content: "";
  position: absolute;
  z-index: 1;
  top: 50%;
  left: 100%;
  height: 18px;
  width: 2px;
  margin-top: -9px;
  //   border-right-color: black;
  //   border-right-width: 1px;
  //   border-right-style: solid;
}
.left_bar_a {
  background-color: #fff;
  padding: 10px;
  //   padding-right: 20px;
}
.tree__checkbox {
  .el-tree-node__content {
    display: flex !important;
    .but__text__tree {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
    }
    .but__tree {
      margin-left: 20px;
    }
  }
}
</style>
<style lang="scss">
.tree__checkbox {
  .el-tree-node {
    margin: 5px 0;
  }
  .el-button + .el-button {
    margin-left: 2px;
  }
  //   .el-tree-node__expand-icon {
  //     padding-top: 0px !important;
  //     margin-top: -2px !important;
  //   }
}
.add_gruppa {
  margin-bottom: 15px;
  //   height: 30px;
  //   overflow: hidden;
  border-bottom: 1px solid #f7f7f7;
  .div {
    background-color: #4caf50;
    color: #fff;
    border-radius: 3px;
    margin-bottom: 5px;
    height: 37px;
    overflow: hidden;
    cursor: pointer;
    padding: 7px 10px;
    display: inline-block;
  }
}
.container2 aside {
  background-color: #fff;
}
.but_footer_style {
  background-color: #fbfbfb;
  text-align: center;
  padding: 10px;
}
.madali_add_grup .el-dialog__body {
  padding: 0px;
}
.madali_add_grup .el-form-item {
  padding: 0 20px;
}
.tree__checkbox .el-tree-node__content > label.el-checkbox {
  margin-top: 5px;
  margin-right: 8px;
}
</style>
