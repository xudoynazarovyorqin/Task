<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span> {{ $t('message.new') }} {{ $t('message.service') | lowerFirst }}</span>
      <el-button v-can="['services.create']" type="success" size="small" class="mr-1" :loading="waiting" @click="submit(false)"> {{ $t('message.save') }}</el-button>
      <el-button v-can="['services.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
      <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
      </el-button>
    </header>

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
  </div>
</template>
<script>

import { mapGetters, mapActions } from 'vuex';
import form from '@/utils/mixins/form';
import service from '@/utils/mixins/models/service';
import drawer from '@/utils/mixins/includes/drawer';

export default {
  mixins: [form,service,drawer],
  data() {
    return {
      
    };
  },
  methods: {
    ...mapActions({
      save: "services/store",
          }),
          afterOpen(){
              this.form = this.getForm;              
          }
  }
};
</script>
