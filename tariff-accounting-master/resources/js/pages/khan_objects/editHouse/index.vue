<template>
  <div>
    <div class="addhouse">
      <el-row :gutter="20">
        <el-col :span="24">
          <div class="page-title">Демо дом</div>
          <el-breadcrumb class="mb-3" separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{  }">Name</el-breadcrumb-item>
            <el-breadcrumb-item>Name</el-breadcrumb-item>
            <el-breadcrumb-item>Name</el-breadcrumb-item>
          </el-breadcrumb>
        </el-col>
        <el-col :span="6">
          <div class="edithouse_top_blocks">
            <div class="top_blocks d-flex justify-content-start align-items-start mb-3 mt-2">
              <i class="el-icon-s-grid"></i>
              <div class="a">
                <span class="content-card__title">Шахматка</span>
                <span class="content-card__status">Заполнение 100%</span>
              </div>
            </div>
            <el-row>
              <el-button class="mb-2" type="primary" plain round size="mini">Обновить из файла</el-button>
              <router-link :to="{name: 'chessPlayer'}">
                <el-button type="success" plain round size="mini">Редактировать</el-button>
              </router-link>
            </el-row>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="edithouse_top_blocks">
            <div class="top_blocks d-flex justify-content-start align-items-start mb-3 mt-2">
              <i class="el-icon-s-grid"></i>
              <div class="a">
                <span class="content-card__title">Планировки помещений</span>
                <span class="content-card__status">Заполнение 100%</span>
              </div>
            </div>
            <el-row>
              <router-link :to="{name: 'floorPlans'}">
                <el-button class="mb-2" type="success" plain round size="mini">Редактировать</el-button>
              </router-link>
            </el-row>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="edithouse_top_blocks">
            <div class="top_blocks d-flex justify-content-start align-items-start mb-3 mt-2">
              <i class="el-icon-s-grid"></i>
              <div class="a">
                <span class="content-card__title">Планировки этажей</span>
                <span class="content-card__status">Заполнение 100%</span>
              </div>
            </div>
            <el-row>
              <router-link :to="{name: 'floors'}">
                <el-button class="mb-2" type="success" plain round size="mini">Редактировать</el-button>
              </router-link>
            </el-row>
          </div>
        </el-col>
        <el-col :span="6">
          <div class="edithouse_top_blocks">
            <div class="top_blocks d-flex justify-content-start align-items-start mb-3 mt-2">
              <i class="el-icon-s-grid"></i>
              <div class="a">
                <span class="content-card__title">Фасады</span>
                <span class="content-card__status">Заполнение 100%</span>
              </div>
            </div>
            <el-row>
              <router-link :to="{name: 'facades'}">
                <el-button class="mb-2" type="primary" plain round size="mini">Наполнить</el-button>
              </router-link>
            </el-row>
          </div>
        </el-col>
        <el-col :span="24">
          <el-button class="mt-3 mb-3" type="success" size="small" round>
            <i class="el-icon-office-building"></i> Посмотреть объект
          </el-button>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <el-col :span="24">
          <div class="info_abjects el-card pa20">
            <div class="info_abjects_title w-100 d-flex justify-content-between">
              <span>Информация о доме</span>
              <el-row>
                <el-button type="success" size="mini" round @click="addHome = true">
                  <i class="el-icon-edit-outline"></i> Редактировать
                </el-button>
              </el-row>
            </div>
            <el-row :gutter="20">
              <el-col :span="10">
                <div class="img_abjects_LEFT">
                  <img
                    id="house-image"
                    class="mb-2 house-info__image"
                    src="https://pb9413.profitbase.ru/uploads/house_470_320/uploads/house/9413/d7e0a016e3407b4459d1a7152da663c0.jpeg"
                    alt
                  />
                  <div
                    class="house-info__image_text"
                  >Превью в виджете CRM и Смарт-каталоге. Рекомендуемые пропорции изображения 580*395 пикселей</div>
                  <el-button class="mt-3 mb-3" type="success" size="small" round>
                    <i class="el-icon-upload"></i> Посмотреть объект
                  </el-button>
                </div>
              </el-col>
              <el-col :span="14">
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Название:</span>
                  <span class="color2">ЖК Демо</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">ЖК:</span>
                  <span class="color2">ЖК Демо</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Тип помещений по умолчанию:</span>
                  <span class="color2">Квартира</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Тип строения::</span>
                  <span class="color2">Жилое</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Адрес:</span>
                  <span class="color2">Екатеринбург, Свердловская область, Малышева, 71</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Отделка помещений:</span>
                  <span class="color2">не указан</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Материал дома:</span>
                  <span class="color2">не указан</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Количество подъездов:</span>
                  <span class="color2">4</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Надземных этажей:</span>
                  <span class="color2">25</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Подземных этажей:</span>
                  <span class="color2">0</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Стадия строительства:</span>
                  <span class="color2">не указана</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Сроки строительства:</span>
                  <span class="color2">II квартал 2016 г. - IV квартал 2020 г.</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Старт продаж:</span>
                  <span class="color2">не указано</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Окончание продаж:</span>
                  <span class="color2">не указано</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Офис продаж::</span>
                  <span class="color2">не указано</span>
                </div>
              </el-col>
            </el-row>
          </div>
        </el-col>
        <el-col :span="24">
          <div class="portlet__head el-card mt-4 pa20">
            <div class="info_abjects_title w-100 d-flex justify-content-between">
              <span>Информация о доме</span>
            </div>
            <div class="switchs mt-3">
              <el-switch v-model="value1" class="style_switch" active-text="Старт продаж"></el-switch>
              <el-switch v-model="value2" class="style_switch" active-text="Дом сдан"></el-switch>
              <el-switch v-model="value3" class="style_switch" active-text="Последние квартиры"></el-switch>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>

    <el-drawer :visible.sync="addHome" :with-header="false" size="80%">
      <AddHome></AddHome>
    </el-drawer>
  </div>
</template>
<script>
import AddHome from "./../../khan_objects/editHouseObjects/components/addHome";
export default {
  name: "editHouse",
  components: { AddHome },
  data() {
    return {
      addHome: false,
      value1: false,
      value2: false,
      value3: false
    };
  }
};
</script>
