<template>
  <div>
      Error 401
  </div>
</template>

<script>

export default {
  name: 'Page401',
  data() {
    return {
    }
  },
  methods: {
    back() {
      if (this.$route.query.noGoBack) {
        this.$router.push({ path: '/dashboard' })
      } else {
        this.$router.go(-1)
      }
    }
  }
}
</script>