<template>
    <div class="block" v-loading="loading">
        <h3 class="ml-4">{{ title }} № {{ model.id }}</h3>
        <el-timeline>
            <el-timeline-item placement="top" v-for="(comment,index) in comments" :key="index" :timestamp="((comment.user) ? comment.user.name : '') + ' послал ' + comment.created_at">
                <el-card>
                    <h4>{{ comment.body }}</h4>
                </el-card>
            </el-timeline-item>
        </el-timeline>
        <div class="row">
            <div class="col-6 ml-5">
                <el-form :model="form" ref="form" class="demo-ruleForm" size="small" label-position="top">
                    <el-form-item
                        :label="$t('message.comment')"
                        prop="body"
                        :rules="[{ required: true, message: this.$t('message.This field is required')}]"
                    >
                        <el-input type="textarea" :rows="4" v-model.number="form.body" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" round @click="submit()" size="small"> {{ $t('message.save') }}</el-button>
                        <el-button round size="small" @click="resetForm('form')"> {{ $t('message.cancel') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex'
    export default {
        props:['drawer','comments', 'title',  'model',  'loading'],
        data() {
            return {
                form: {
                    model_id: '',
                    body: ''
                }
            }
        },
        methods: {
            submit(){
                this.form.model_id = this.model.id;
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.$emit('crm-save',this.form);
                    } else {
                        return false;
                    }
                });
            },
            resetForm (formName) {
                this.$refs[formName].resetFields();
            }
        },
    }
</script>

<style scoped>
    body{margin-top:20px;}

    .comment-wrapper .panel-body {
        max-height:650px;
        overflow:auto;
    }

    .comment-wrapper .media-list .media {
        border-bottom:1px dashed #efefef;
        margin-bottom:25px;
    }
</style>
