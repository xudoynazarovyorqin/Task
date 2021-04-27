<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span> {{ $t('message.new') }} {{ $t('message.district') | lowerFirst }}</span>
      <el-button v-can="['districts.create']" type="success" size="small" class="mr-1" :loading="waiting" @click="submit(false)"> {{ $t('message.save') }}</el-button>
      <el-button v-can="['districts.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
      <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
      </el-button>
    </header>

    <el-form ref="form" :model="form" :rules="rules" label-width="220px" class="p-4 pl-0" size="small">
      <el-form-item :label="columns.name.title" prop="name">
          <el-input v-model="form.name" autocomplete="off" maxlength="250" show-word-limit></el-input>
      </el-form-item>
      <el-form-item :label="columns.description.title" size="small">
          <el-input :rows="3" type="textarea" v-model="form.description" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>
  </div>
</template>
<script>

import { mapGetters, mapActions } from 'vuex';
import form from '@/utils/mixins/form';
import district from '@/utils/mixins/models/district';
import drawer from '@/utils/mixins/includes/drawer';

export default {
  mixins: [form,district,drawer],
  data() {
    return {

    };
  },
  methods: {
    ...mapActions({
      save: "districts/store",
          }),
          afterOpen(){
              this.form = this.getForm;
          }
  }
};
</script>
