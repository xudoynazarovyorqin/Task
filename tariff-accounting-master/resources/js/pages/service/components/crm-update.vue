<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span> {{ $t('message.edit') }} {{ $t('message.service') | lowerFirst }} № {{ service.id }}</span>
      <el-button v-can="['services.update']" type="success" size="small" class="mr-1" :loading="waiting" @click="submit(false)"> {{ $t('message.save') }}</el-button>
      <el-button v-can="['services.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
      <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
      </el-button>
    </header>
    <el-main v-loading="loading">
      <el-form ref="form" :model="form" :rules="rules" label-width="220px" class="p-4 pl-0" size="small">
        <el-form-item :label="columns.name.title" prop="name">
            <el-input v-model="form.name" autocomplete="off" maxlength="250" show-word-limit></el-input>
        </el-form-item>
        <el-form-item :label="columns.price.title" prop="price" size="small">
            <amount v-model="form.price" :old="form.price" size="small"></amount>
        </el-form-item>
        <el-col :span="12">
          <measurements v-model="form.measurement_id" :measurement_id="form.measurement_id" size="small"></measurements>
        </el-col>
      </el-form>
  </el-main>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex';
import form from '@/utils/mixins/form';
import service from '@/utils/mixins/models/service';
import drawer from '@/utils/mixins/includes/drawer';

  export default {
      props:['service'],
      mixins: [form,service,drawer],
      methods:{
          ...mapActions({
              save: 'services/update',
              edit: 'services/show',
          }),
          afterOpen(){
              this.form = this.getForm;
              this.load()
          },
          load(){
              if (!this.loading && this.service) {
                this.changeLoading(true);
                this.edit(this.service.id)
                  .then(res => {
                      this.form = this.getForm;
                      this.changeLoading();
                  })
                  .catch(err => {
                      this.changeLoading();
                  })
              }
          },
      }
  }
</script>
