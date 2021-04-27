<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.new') }} {{ $t('message.user') | lowerFirst }}</span>
            <el-button v-can="['users.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main>
            <el-form ref="form" :model="form" :rules="rules" label-width="150px" size="small">
                <el-col :span="8">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" clearable></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.phone.title" prop="phone">
                        <el-input v-model="form.phone" clearable  autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.password.title" prop="password" >
                        <el-input v-model="form.password" clearable show-password></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.is_employee.title" prop="is_employee" >
                        <el-checkbox v-model="form.is_employee" clearable></el-checkbox>
                    </el-form-item>
                    <el-form-item :label="$t('message.Group of employees')" v-if="show_employee_groups" class="d-block">
                        <el-select v-model="form.employee_groups" multiple filterable clearable>
                            <el-option v-for="item in employee_groups" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item :label="columns.first_name.title">
                        <el-input v-model="form.first_name" clearable ></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.surname.title">
                        <el-input v-model="form.surname" clearable ></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.patronymic.title">
                        <el-input v-model="form.patronymic" clearable ></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.pin_code.title">
                        <el-input v-model.number="form.pin_code" clearable></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item :label="columns.email.title">
                        <el-input v-model="form.email" clearable ></el-input>
                    </el-form-item>
                    <roles v-model="form.role_id" size="small"></roles>
                    <el-form-item  :label="columns.status.title"  prop="status">
                        <el-select v-model="form.status" filterable clearable class="">
                            <el-option v-for="(value,index) in statues" :key="index" :label="value" :value="index"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-form>
        </el-main>
    </div>
</template>
<script>
    import form from '@/utils/mixins/form';
    import user from '@/utils/mixins/models/user';
    import drawer from '@/utils/mixins/includes/drawer';
    import { mapGetters, mapActions } from "vuex";

    export default {
        mixins:[drawer,user,form],
        methods: {
            ...mapActions({
                save: 'users/store',
            }),
            afterOpen(){
                this.form = this.getForm;
            }
        },
    }
</script>
