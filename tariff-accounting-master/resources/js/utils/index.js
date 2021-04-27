import store from '@/store/index'

/**
 * Created by Azizbek on 16/11/19.
 */

export function notify(res) {
    try {
        if (res.success) {
            this.$notify({
                title: 'Успешно ',
                type: "success",
                message: res.message
            });
        } else {
            if (res.error && res.error.code == 422) {
                let ms = '';
                for (const key in res.validation_errors) {
                    if (res.validation_errors.hasOwnProperty(key)) {
                        const element = res.validation_errors[key];
                        ms += (element.join("<br>") + "<br>")
                    }
                }
                this.$message({
                    dangerouslyUseHTMLString: true,
                    title: 'Предупреждение',
                    message: ms,
                    type: 'warning',
                    duration: 15000,
                    showClose: true,
                });
            } else {
                this.$notify({
                    title: 'Ошибка ',
                    type: "error",
                    message: (res.error) ? (res.error.message + ' Код: ' + res.error.code) : res.message
                });
            }
        }
    } catch (error) {
        alert(error)
    }
}

export function parseValidationErrorToStr(messages) {
    return messages.join(', ')
}

export function objectToQuery(obj) {
    var str = [];
    for (var p in obj)
        if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
    return str.join("&");
}

export function permission(permission) {
    return store.getters.role.permissions.some(perm => perm.slug == permission)
}