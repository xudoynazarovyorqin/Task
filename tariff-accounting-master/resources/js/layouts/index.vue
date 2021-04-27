<template>
    <div>
        <navbar></navbar>
        <app-main></app-main>
    </div>
</template>

<script>
    import { Navbar, AppMain } from './components'
    export default {
        components: {
            AppMain,
            Navbar,
        }
    }
</script>