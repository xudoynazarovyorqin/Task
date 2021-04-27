export default {
    data() {
        return {
            form: {},
            waiting: false,
        }
    },
    methods: {
        closeModal(modalName) {
            this.$modal.hide(modalName);
        },
        changeWaiting(val = false) {
            if (val === false) {
                setTimeout(() => {
                    this.waiting = false;
                }, 500);
            } else {
                this.waiting = true;
            }
        }
    },
}