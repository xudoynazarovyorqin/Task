<template>
  <div>
    <div class="create_New_Objects">
      <el-row :gutter="20">
        <el-col :span="6">
          <div
            class="add_building style_building d-flex flex-column justify-content-center align-items-center"
          >
            <i class="el-icon-office-building d-flex justify-content-center align-items-center"></i>
            <el-button type="success" round @click="addHous = true">Добавить объект</el-button>
          </div>
        </el-col>

        <el-col :span="6">
          <div
            class="residential_Complex style_building d-flex flex-column justify-content-center align-items-center"
          >
            <div class="info_Complex d-flex flex-column justify-content-center align-items-center">
              <div class="title_Complex mb-2">Жилой комплекс</div>
              <div class="name_Complex mb-2">ЖК Демо</div>
              <div class="data_Complex mb-2">(создан 05.03.2020)</div>
              <div class="show_Complex mb-2">
                <router-link :to="{name: 'editHouseObjects'}">
                  <el-button type="danger" round>Редактировать</el-button>
                </router-link>
              </div>
            </div>
            <div class="footer_Complex d-flex align-items-center">
              <a href="#">
                <span>Домов</span>
                <span>1</span>
              </a>
              <span></span>
              <a href="#">
                <a href="#">
                  <span>Помещений</span>
                  <span>340</span>
                </a>
              </a>
            </div>
          </div>
        </el-col>

        <el-col :span="6">
          <div
            class="residential_Complex style_building d-flex flex-column justify-content-center align-items-center"
          >
            <div class="info_Complex d-flex flex-column justify-content-center align-items-center">
              <div class="title_Complex mb-2">Жилой комплекс</div>
              <div class="name_Complex mb-2">ЖК Демо</div>
              <div class="data_Complex mb-2">(создан 05.03.2020)</div>
              <div class="show_Complex mb-2">
                <el-button type="danger" round>Редактировать</el-button>
              </div>
            </div>
            <div class="footer_Complex d-flex align-items-center">
              <a href="#">
                <span>Домов</span>
                <span>1</span>
              </a>
              <span></span>
              <a href="#">
                <a href="#">
                  <span>Помещений</span>
                  <span>340</span>
                </a>
              </a>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>

    <el-drawer :visible.sync="addHous" :with-header="false" size="40%">
      <AddHouseObject></AddHouseObject>
    </el-drawer>
  </div>
</template>
<script>
import AddHouseObject from "./components/addHouseObject";
export default {
  components: { AddHouseObject },
  data() {
    return {
      addHous: false
    };
  }
};
</script>
