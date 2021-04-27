<template>
  <el-dropdown szie="mini" @command="handleCommand">
    <el-button size="mini" icon="el-icon-setting" round>
        <i class="el-icon-arrow-down"></i>
    </el-button>
    <el-dropdown-menu slot="dropdown" size="mini">
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'show')) && actions.some(action => action == 'show')" command="show" icon="el-icon-view"> {{ $t('message.show') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'edit')) && actions.some(action => action == 'edit')" command="edit" icon="el-icon-edit el-icon--left"> {{ $t('message.edit') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'copy')) && actions.some(action => action == 'copy')" command="copy" icon="el-icon-copy-document el-icon--left"> {{ $t('message.copy') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'print'))  && actions.some(action => action == 'print')" command="print" icon="el-icon-printer el-icon--left"> {{ $t('message.print') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'manufactured')) && actions.some(action => action == 'manufactured')" command="manufactured"  icon="el-icon-trophy"> {{ $t('message.ready_products') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'defect_product')) && actions.some(action => action == 'defect_product')" command="defect_product"  icon="el-icon-sold-out"> {{ $t('message.defect_product') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'back_material')) && actions.some(action => action == 'back_material')" command="back_material" icon="el-icon-refresh-left el-icon--left"> {{ $t('message.back_material') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'comments')) && actions.some(action => action == 'comments')" command="comments" icon="el-icon-chat-line-square el-icon--left"> {{ $t('message.comment') }}</el-dropdown-item>
        <el-dropdown-item v-if="permissions.some(per => per.slug == (name+'.'+'delete')) && actions.some(action => action == 'delete')" command="delete" icon="el-icon-delete el-icon--left"> {{ $t('message.delete') }}</el-dropdown-item>
    </el-dropdown-menu>
</el-dropdown>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  props: {
    model: { type: Object, required: true },
    actions: { type: Array },
    name: { type: String, required: true }
  },
  data() {
    return {};
  },
  computed: {
    ...mapGetters({
      permissions: "auth/permissions"
    })
  },
  methods: {
    handleCommand(command) {
      if (command === "delete") {
        this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
          confirmButtonText: this.$t('message.yes'),
          cancelButtonText: this.$t('message.cancel'),
          type: 'warning'
        }).then(() => {
            this.$emit(command, this.model);
        }).catch(() => {
          this.$notify({
            title: this.$t('message.warning'),
            message: this.$t('message.request_canceled'),
            type: 'warning',
            position: 'bottom-right'
          });
        });
      } else {
        this.$emit(command, this.model);
      }
    }
  }
};
</script>