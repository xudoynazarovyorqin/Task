<template>
  <div>
    <div class="editHouse">
      <el-row :gutter="20" class="mb-2">
        <el-col :span="24">
          <div class="page-title">ЖК Демо</div>
          <el-breadcrumb class="mb-3" separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{  }">Name</el-breadcrumb-item>
            <el-breadcrumb-item>Name</el-breadcrumb-item>
            <el-breadcrumb-item>Name</el-breadcrumb-item>
          </el-breadcrumb>
        </el-col>
        <el-col :span="16">
          <div class="info_abjects el-card pa20">
            <div class="info_abjects_title w-100 d-flex justify-content-between">
              <span>Информация об объекте</span>
              <el-row>
                <el-button type="success" size="mini" round>
                  <i class="el-icon-edit-outline"></i> Редактировать
                </el-button>
                <el-button type="danger" size="mini" round @click="modalDElet = true">
                  <i class="el-icon-circle-close"></i> Удалить объект
                </el-button>
              </el-row>
            </div>
            <el-row :gutter="20">
              <el-col :span="12">
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Тип объекта:</span>
                  <span class="color2">ЖК Демо</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Название:</span>
                  <span class="color2">ЖК Демо</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Адрес:</span>
                  <span class="color2">Екатеринбург, Свердловская область</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Застройщик:</span>
                  <span class="color2">не указан</span>
                </div>
              </el-col>
              <el-col :span="12">
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Инфраструктура:</span>
                  <span class="color2">не указан</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">СайтСайт::</span>
                  <span class="color2">не указан</span>
                </div>
                <div class="info_abjects_LEFT">
                  <span class="color1 wi100">Отдел продаж:</span>
                  <span class="color2">не указан</span>
                </div>
              </el-col>
            </el-row>
          </div>
        </el-col>
        <el-col :span="8">
          <div class="info_abjects el-card pa20">
            <div class="info_abjects_title w-100">
              <span>
                Медиа-материалы
                <i class="el-icon-info"></i>
              </span>
            </div>
            <div class="mater">
              <div class="mb-2">Видео- и фото-материалы не загружены</div>
              <el-button type="primary" round>
                <i class="el-icon-circle-plus-outline"></i> Добавить материалы
              </el-button>
            </div>
          </div>
        </el-col>
      </el-row>

      <el-row :gutter="20">
        <el-col :span="24">
          <div class="page-title">Список домов</div>
          <el-button type="primary" round @click="addHome = true">
            <i class="el-icon-circle-plus-outline"></i> Добавить дом
          </el-button>
        </el-col>
        <el-col :span="8">
          <div class="building-card mt-3">
            <div
              class="building-card__bg"
              style="background-image: url(https://pb9413.profitbase.ru/uploads/house_470_320/uploads/house/9413/d7e0a016e3407b4459d1a7152da663c0.jpeg)"
            >
              <div class="building-card__body">
                <div class="font_18">ЖК Демо</div>
                <div class="font_18 font-weight-bold">Демо дом</div>
                <div class="font_18">340 помещений</div>
              </div>
            </div>
            <div class="building-card__actions d-flex justify-content-around align-items-center">
              <router-link :to="{name: 'editHouse'}" class="text-primary">Редактировать</router-link>
              <a class="text-primary" @click="centerDialogVisible = true">Копировать</a>
              <a class="text-danger" @click="modalDElet = true">Удалить</a>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>

    <el-drawer :visible.sync="addHome" :with-header="false" size="80%">
      <AddHome></AddHome>
    </el-drawer>

    <el-dialog
      class="CopuHomeModal modal_icon_ix"
      title="Копирование дома Демо дом"
      :visible.sync="centerDialogVisible"
      width="30%"
    >
      <div
        class="text"
      >Будут скопированы все параметры дома (тип, адрес и другие), а также фасады, планировки помещений и этажей, разметка этажей, помещения и их свойства.</div>
      <el-form ref="form" :model="formModal" size="small" class="style__label">
        <el-form-item label="Название дома">
          <el-input v-model="formModal.name"></el-input>
        </el-form-item>
        <el-form-item label="ЖК">
          <el-select v-model="formModal.regions1" class="width-select">
            <el-option label="Zone one" value="shanghai"></el-option>
            <el-option label="Zone two" value="beijing"></el-option>
          </el-select>
        </el-form-item>
        <div
          class="text"
        >Процесс копирования может занимать от нескольких секунд до нескольких минут.</div>
        <el-button type="primary" round @click="addHome = true">Копировать</el-button>
      </el-form>
    </el-dialog>

    <el-dialog
      class="CopuHomeModal modal_icon_ix"
      title=" Внимание Вы действительно хотите это сделать?"
      :visible.sync="modalDElet"
      width="30%"
      center
    >
      <DeletModal></DeletModal>
    </el-dialog>
  </div>
</template>
<script>
import AddHome from "./components/addHome";
import DeletModal from "./../../../components/crm/include/khna_modal";
export default {
  name: "editHouseObjects",
  components: { AddHome, DeletModal },
  data() {
    return {
      addHome: false,
      centerDialogVisible: false,
      modalDElet: false,
      form: {},
      formModal: {}
    };
  }
};
</script>
<style lang="scss">
.mater {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  height: 200px;
}
</style>
