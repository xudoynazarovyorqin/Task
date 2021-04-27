<template>
    <el-card class="mt-2" shadow="never">
        <div slot="header">
            <span class="h5">{{ $t('message.additional')}}</span>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="2">{{ $t('message.browser')}}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $t('message.ip_address') }}</td>
                    <td>{{ created_audit.ip_address }}</td>
                </tr>
                <tr>
                    <td>{{ $t('message.user_agent') }}</td>
                    <td>{{ created_audit.user_agent }}</td>
                </tr>
                <tr>
                    <td>{{ $t('message.user') }}</td>
                    <td>{{ (created_audit.user) ? created_audit.user.name : '' }}</td>
                </tr>
                <tr>
                    <td>{{ $t('message.time') }}</td>
                    <td>{{ created_audit.created_at }}</td>
                </tr>
                <tr>
                    <td>{{ $t('message.event') }}</td>
                    <td>
                        <template v-if="created_audit.event == 'created'">
                            {{ $t('message.created') }}
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>
    </el-card>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex'
    export default {
        props: ['created_audit']
    }
</script>
