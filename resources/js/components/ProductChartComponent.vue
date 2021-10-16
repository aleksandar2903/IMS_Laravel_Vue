<template>
  <div class="container-fluid">
    <div class="row m-0">
      <div class="col-xl-12">
        <div class="card bg-default">
          <div class="card-header bg-transparent">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="text-light text-uppercase ls-1 mb-1">{{text.Overview}}</h6>
                <h5 class="h3 text-white mb-0">{{text.Traffic}}</h5>
              </div>
              <div class="col">
                <VueHotelDatepicker
                  @confirm="applyData"
                  minDate="01-01-2000"
                  separator="-"
                  format="DD-MM-YYYY"
                  :startDate="lw"
                  :endDate="today"
                  placeholder=""
                  mobile="mobile"
                ></VueHotelDatepicker>
              </div>
              <div class="col">
                <ul class="nav nav-pills justify-content-end">
                  <li
                    id="chart-data"
                    class="nav-item mr-2 mr-md-0"
                    data-prefix="$"
                  >
                    <a
                      href="javascript:void(0)"
                      id="sales"
                      v-on:click="sales"
                      class="nav-link py-2 px-3 active"
                      data-toggle="tab"
                    >
                      <span class="d-none d-md-block">{{text.Sales}}</span>
                      <span class="d-md-none">S</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a
                      href="javascript:void(0)"
                      id="purchases"
                      v-on:click="purchases"
                      class="nav-link py-2 px-3"
                      data-toggle="tab"
                    >
                      <span class="d-none d-md-block">{{text.Purchases}}</span>
                      <span class="d-md-none">P</span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <!-- Chart -->
            <div class="chart">
              <!-- Chart wrapper -->
              <canvas
                id="chart-sales"
                class="chart-canvas chartjs-render-monitor"
                style="display: block; width: 635px; height: 100%"
                width="635"
                height="100%"
              ></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueHotelDatepicker from "./VueHotelDatepicker";
import moment from "moment";
export default {
  components: {
    VueHotelDatepicker,
  },
  props: ["productId",'text'],
  data() {
    return {
      query: [],
      data: null,
      chart: null,
      lw: "",
      today: "",
      active: false,
    };
  },
  created() {
    var d = new Date();
    var lastWeek = new Date();
    lastWeek.setDate(lastWeek.getDate() - 7);
    this.lw = moment(lastWeek, "YYYYMMDD").format("YYYY-MM-DD").toString();
    this.today = moment(d, "YYYYMMDD").format("YYYY-MM-DD");
    this.query = [this.lw, this.today, this.productId];
    this.sendQuery();
  },
  methods: {
    applyData(result) {
      this.query = [result.start, result.end, this.productId];
      this.sendQuery();
    },
    async sendQuery() {
      await axios
        .get("/data/query", { params: this.query })
        .then((response) => (this.data = response.data));
      if (!this.active) {
        this.sales();
      } else {
        this.purchases();
      }
    },
    seedChart(data) {
      var date = [];
      var totalAmount = [];
      data.forEach((element) => {
        totalAmount.push(element["totalAmount"]), date.push(element["date"]);
      });
      $(document).ready(function () {
        var sales = $("#chart-sales");
        if (window.chart != undefined) {
          window.chart.destroy();
        }
        window.chart = new Chart(sales, {
          type: "line",
          options: {
            scales: {
              yAxes: [
                {
                  gridLines: {
                    color: Charts.colors.gray[700],
                    zeroLineColor: Charts.colors.gray[700],
                  },
                },
              ],
            },
          },
          data: {
            labels: date.reverse(),
            datasets: [
              {
                label: "Total amount",
                data: totalAmount.reverse(),
              },
            ],
          },
        });
      });
    },
    purchases() {
      this.seedChart(this.data["purchases"]);
      this.active = true;
    },
    sales() {
      this.seedChart(this.data["sales"]);
      this.active = false;
    },
  },
};
</script>
<style>
</style>
