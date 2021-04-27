<template>
    <div class="invalid-feedback">
        {{ getMessages() }}
    </div>
</template>

<script>
    export default {
        props: {
            messages: { type: Array, required: true}
        },
        methods: {
            getMessages(){
                return this.messages.join(', ')
            }
        },
    }
</script>