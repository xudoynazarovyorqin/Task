<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span>{{ $t('message.new') }} {{ $t('message.client') | lowerFirst }}</span>
      <el-button
        v-can="['clients.create']"
        type="success"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit(false)"
      >{{ $t('message.save') }}</el-button>
      <el-button
        v-can="['clients.create']"
        type="primary"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit(true)"
      >{{ $t('message.save_and_exit') }}</el-button>
      <el-button
        type="warning"
        @click="close()"
        icon="el-icon-close"
        size="small"
      >{{ $t('message.close') }}</el-button>
    </header>
    <el-main>
      <el-form
        ref="form"
        status-icon
        :model="form"
        :rules="rules"
        :label-position="'right'"
        size="mini"
        class="style__label"
      >
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item :label="columns.name.title" prop="name">
              <el-input v-model="form.name" autocomplete="off"></el-input>
            </el-form-item>
            <el-form-item :label="columns.phone.title">
              <el-input v-model="form.phone" autocomplete="off"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item :label="columns.email.title">
              <el-input v-model="form.email" autocomplete="off"></el-input>
            </el-form-item>
            <!-- <el-form-item :label="columns.actual_address.title">
              <el-input v-model="form.actual_address" autocomplete="off"></el-input>
            </el-form-item> -->
            <el-form-item :label="columns.comment.title">
              <el-input :rows="3" type="textarea" v-model="form.comment" autocomplete="off"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <!-- <types v-model="form.type_id" size="mini"></types> -->
            <template v-if="form.type_id === 1">
              <el-form-item :label="columns.inn.title">
                <el-input v-model="form.inn" autocomplete="off"></el-input>
              </el-form-item>
              <el-form-item :label="columns.mfo.title">
                <el-input v-model="form.mfo" autocomplete="off"></el-input>
              </el-form-item>
              <el-form-item :label="columns.okonx.title">
                <el-input v-model="form.okonx" autocomplete="off"></el-input>
              </el-form-item>
              <el-form-item :label="columns.oked.title">
                <el-input v-model="form.oked" autocomplete="off"></el-input>
              </el-form-item>
              <el-form-item :label="columns.rkp_nds.title">
                <el-input v-model="form.rkp_nds" autocomplete="off"></el-input>
              </el-form-item>
            </template>
          </el-col>
        </el-row>

        <el-row :gutter="20">
          <el-col :span="24">
            <el-card class="box-card clent_calabs">
              <el-collapse v-model="activeNames">
                <el-collapse-item :title="$t('message.object')" name="1">
                  <el-form-item class="mb-1" :label="columns.object_name.title">
                    <el-input v-model="form.object_name" size="small"></el-input>
                  </el-form-item>

                  <districts v-model="form.district_id" :district_id="form.district_id" :label="$t('message.district')"></districts>

                  <quarters v-model="form.quarter_id" :quarter_id="form.quarter_id" :label="$t('message.quarter')"></quarters>

                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_street.title">
                        <el-input v-model="form.object_street" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_home.title">
                        <el-input v-model="form.object_home" size="small"></el-input>
                      </el-form-item>
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_corps.title">
                        <el-input v-model="form.object_corps" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_flat.title">
                        <el-input v-model="form.object_flat" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                  </el-row>
                </el-collapse-item>
              </el-collapse>
            </el-card>
          </el-col>
        </el-row>
        <!-- end row -->

        <!-- <el-row :gutter="20">
          <el-col :span="8" :offset="2">
            <el-card class="mt-2" v-for="(item,index) in client_contact_persons" :key="index">
              <div slot="header" class="clearfix">
                <span class="h4">{{ $t('message.Contact person') }}</span>
                <el-button
                  style="float: right;"
                  circle
                  type="danger"
                  size="mini"
                  icon="el-icon-delete"
                  @click="removeContactPerson(index)"
                ></el-button>
              </div>
              <el-form label-position="right" label-width="100px">
                <el-form-item :label="$t('message.name')">
                  <el-input v-model="item.full_name" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.Position')">
                  <el-input v-model="item.position" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.phone')">
                  <el-input v-model="item.phone" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.email')">
                  <el-input v-model="item.email" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.comment')">
                  <el-input v-model="item.comment" size="mini"></el-input>
                </el-form-item>
              </el-form>
            </el-card>
            <el-button type="primary" round size="mini" @click="addContactPerson()" class="mt-2">
              <i class="el-icon-plus"></i>
              {{ $t('message.Contact person') }}
            </el-button>
          </el-col>
          <el-col :span="8" :offset="4">
            <el-card class="mt-2" v-for="(item,index) in client_checking_accounts" :key="index">
              <div slot="header" class="clearfix">
                <span class="h4">{{ $t('message.Payment account') }}</span>
                <el-button
                  style="float: right;"
                  circle
                  type="danger"
                  size="mini"
                  icon="el-icon-delete"
                  @click="removeCheckingAccount(index)"
                ></el-button>
              </div>
              <el-form label-position="right" label-width="120px">
                <el-form-item :label="$t('message.Bank')">
                  <el-input v-model="item.bank" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.address')">
                  <el-input v-model="item.address" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.Correspondent account')">
                  <el-input v-model="item.correspondent_account" size="mini"></el-input>
                </el-form-item>
                <el-form-item :label="$t('message.Payment account')">
                  <el-input v-model="item.checking_account" size="mini"></el-input>
                </el-form-item>
              </el-form>
            </el-card>
            <el-button type="primary" round size="mini" @click="addCheckingAccount()" class="mt-2">
              <i class="el-icon-plus"></i>
              {{ $t('message.Payment account') }}
            </el-button>
          </el-col>
        </el-row> -->
        <!-- end row -->
      </el-form>
    </el-main>
  </div>
</template>
<script>
import drawer from "@/utils/mixins/includes/drawer";
import form from "@/utils/mixins/form";
import client from "@/utils/mixins/models/client";
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [form, drawer, client],
  data() {
    return {
      activeNames: ["1"],
    };
  },
  methods: {
    ...mapActions({
      save: "clients/store"
    }),
    afterOpen() {
      this.form = this.getForm;
      this.client_contact_persons = [];
      this.client_checking_accounts = [];
    }
  }
};
</script>
