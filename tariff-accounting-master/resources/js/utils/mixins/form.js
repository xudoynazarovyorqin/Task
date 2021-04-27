export default {
    data() {
        return {
            form: {},
            waiting: false,
        }
    },
    methods: {
        waitingStop() {
            setTimeout(() => {
                this.waiting = false
            }, 500);
        },
        listChanged() {
            this.parent().listChanged()
        }
    }
}