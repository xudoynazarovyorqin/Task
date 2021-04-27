<template>
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
      <div class="col-12 p-0 font-weight-bold">
        <h5 class="d-inline mr-2 font-weight-bold">Остаток сырья</h5>
      </div>
    </div>

    <div class="col-12 p-0 font-weight-bold">
      <div class="dropdown d-inline show float-right mr-4">
        <button
          type="button"
          id="viewsExports"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="true"
          class="btn btn-default border_add_btn dropdown-toggle mr-2"
        >
          <span class="fa fa-download"></span>
          <span class>Экспорт</span> &nbsp;
        </button>
        <div
          aria-labelledby="viewsExports"
          id="exportsDropdown"
          class="dropdown-menu"
          x-placement="bottom-start"
        >
          <button class="dropdown-item" type="button" @click="exportExcel()">Excel</button>
        </div>
      </div>
    </div>

    <div class="col-md-8 offset-md-2">
      <input
        style="border-radius: 50px"
        class="form-control mb-4"
        type="text"
        id="searchInput"
        @keyup="doSearch()"
        placeholder="Найти"
        title="Найти"
      />

      <table id="reportMaterials" class="table table-hover table-bordered text-center">
        <thead class="thead-light">
          <tr>
            <th @click="sortTable(0)">Наименование сырья</th>
            <th @click="sortTableNumber(1)">Остаток</th>
            <th @click="sortTableNumber(2)">Общее количество</th>
            <th @click="sortTableNumber(3)">Средняя цена</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reportMaterial in reportMaterials" :key="reportMaterial.id">
            <td>{{ reportMaterial.name }}</td>
            <td>{{ reportMaterial.remainder }}</td>
            <td>{{ reportMaterial.qty_weight }}</td>
            <td>{{ reportMaterial.price_sum / reportMaterial.material_count | formatMoney }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { objectToQuery } from "./../../utils";
export default {
  name: "ReportMaterialsList",

  data() {
    return {
      reportMaterials: []
    };
  },

  created() {
    this.fetchData();
  },

  methods: {
    fetchData() {
      this.$store
        .dispatch("reports/reportMaterials")
        .then(res => {
          const { data } = res;
          this.reportMaterials = data.reportMaterials;
        })
        .catch(err => {
          this.$alert(err)
        });
    },

    doSearch() {
      var searchText = document
        .getElementById("searchInput")
        .value.toLowerCase();
      var targetTable = document.getElementById("reportMaterials");
      var targetTableColCount;

      for (var rowIndex = 0; rowIndex < targetTable.rows.length; rowIndex++) {
        var rowData = "";

        if (rowIndex == 0) {
          targetTableColCount = targetTable.rows.item(rowIndex).cells.length;
          continue;
        }

        for (var colIndex = 0; colIndex < targetTableColCount; colIndex++) {
          rowData += targetTable.rows
            .item(rowIndex)
            .cells.item(colIndex)
            .textContent.toLowerCase();
        }

        if (rowData.indexOf(searchText) == -1)
          targetTable.rows.item(rowIndex).style.display = "none";
        else targetTable.rows.item(rowIndex).style.display = "table-row";
      }
    },

    sortTable(n) {
      var table,
        rows,
        switching,
        i,
        x,
        y,
        shouldSwitch,
        dir,
        switchcount = 0;
      table = document.getElementById("reportMaterials");
      switching = true;
      dir = "asc";
      while (switching) {
        switching = false;
        rows = table.getElementsByTagName("TR");
        for (i = 1; i < rows.length - 1; i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];

          if (dir == "asc") {
            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          } else if (dir == "desc") {
            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchcount++;
        } else {
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    },

    sortTableNumber(n) {
      var table,
        rows,
        switching,
        i,
        x,
        y,
        shouldSwitch,
        dir,
        switchcount = 0;
      table = document.getElementById("reportMaterials");
      switching = true;
      dir = "asc";
      while (switching) {
        switching = false;
        rows = table.getElementsByTagName("TR");
        for (i = 1; i < rows.length - 1; i++) {
          shouldSwitch = false;
          x = rows[i].getElementsByTagName("TD")[n];
          y = rows[i + 1].getElementsByTagName("TD")[n];

          if (dir == "asc") {
            if (Number(x.innerHTML) > Number(y.innerHTML)) {
              shouldSwitch = true;
              break;
            }
          } else if (dir == "desc") {
            if (Number(x.innerHTML) < Number(y.innerHTML)) {
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
          switchcount++;
        } else {
          if (switchcount == 0 && dir == "asc") {
            dir = "desc";
            switching = true;
          }
        }
      }
    },

    exportExcel() {
      this.$store
        .dispatch("reports/excelMaterials")
        .then(res => {
          //const url = window.URL.createObjectURL(new Blob([res.data], {type:'application/pdf'}));
          const url = window.URL.createObjectURL(
            new Blob([res.data], { type: "application/vnd.ms-excel" })
          );

          const link = document.createElement("a");
          link.href = url;
          //link.setAttribute('download', 'file.pdf');
          link.setAttribute("download", "report_material.xls");
          document.body.appendChild(link);
          link.click();
        })
        .catch(err => {
          this.$alert(err)
        });
    }
  }
};
</script>