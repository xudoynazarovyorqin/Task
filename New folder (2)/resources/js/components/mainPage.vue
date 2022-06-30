<template>
  <div
    style="
      margin: auto;
      width: 50%;
      border: 3px solid green;
      padding: 10px;
      margin-top: 50vh;
      display: flex;
    "
  >
    <textarea
      type="text"
      placeholder="Search..."
      v-model="url"
      style="width: 80%"
    />
    <button
      @click="axil"
      type="button"
      class="btn btn-primary"
      style="width: 20%; padding: 1%"
    >
      GET INFO
    </button>
  </div>

  <div style="margin: auto; width: 50%; padding: 10px;">
    <table v-if="data != null">
      <tr>
        <th>url</th>
        <th>status</th>
        <th>info</th>
      </tr>
      <tr v-for="(site, index) in data" :key="index">
        <td>{{ site.url }}</td>
        <td>{{ site.status }}</td>
        <td>{{ site.info }}</td>
      </tr>
    </table>
  </div>
</template>
<script>
import axios from "axios";
export default {
  data() {
    return {
      url: "",
      data: null,
    };
  },

  methods: {
    axil(params) {
      axios({
        url: "http://127.0.0.1:8000/api/domains",
        method: "GET",
        params: { url: this.url.split("\n") },
      }).then((res) => {
        this.data = res.data;
      });
    },
  },
};
</script>

