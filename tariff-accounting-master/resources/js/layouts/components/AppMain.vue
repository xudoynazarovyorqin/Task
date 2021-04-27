<template>
  <section class="app-main">
    <transition name="fade-transform" mode="out-in">
        <router-view :key="key" />
    </transition>
  </section>
</template>

<script>
	import { mapGetters, mapActions } from 'vuex';
    export default {
        name: 'AppMain',
        mounted () {
            if (!_.size(this.currencies)) this.updateCurrencyInventory()
		},
		computed:{
			...mapGetters({
    	        currencies: 'currencies/inventory',
			}),
			key() {
				return this.$route.path
			}
		},
		methods: {
			...mapActions({
    	        updateCurrencyInventory: 'currencies/inventory',
			})
		},
    }
</script>

<style lang="scss" scoped>
.app-main {
  /* 50= navbar  50  */
  min-height: calc(100vh - 200px);
  position: relative;
  overflow: hidden;
  margin: 1rem 1.2rem;
}
</style>
