<template>
  <div class="container-fluid">
      <div class="row">
          <product-chart></product-chart>
          <material-chart></material-chart>
      </div>
  </div>
</template>

<script>
import productChart from "./components/product-chart";
import materialChart from "./components/material-chart";
export default {
  data() {
    return {};
  },
  components: {
      'product-chart': productChart,
      'material-chart' : materialChart,
  }
};
</script>
