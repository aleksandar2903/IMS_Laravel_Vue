<template>
  <div class="btn-group w-100">
    <form
      class="navbar-search navbar-search-light form-inline mr-sm-3"
      id="navbar-search-main"
    >
      <div class="form-group mb-0">
        <div class="input-group input-group-alternative input-group-merge">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
          <input
            v-model="name"
            class="form-control"
            :placeholder="text.Search"
            type="search"
          />
        </div>
      </div>
      <button
        type="button"
        class="close"
        data-action="search-close"
        data-target="#navbar-search-main"
        aria-label="Close"
      >
        <span aria-hidden="true">×</span>
      </button>
    </form>
    <div
    style="width:330px;"
      v-if="name"
      class="dropdown-menu show row d-flex justify-content-center mt-3 p-3"
    >
      <div v-if="list.Products.length" class="rounded bg-white mb-1 w-100">
        <div class="w-100 p-1 rounded text-center text-white bg-blue">
          {{ text.Products }}
        </div>
        <a
          v-for="value in list.Products"
          :key="value.id"
          class="
            bg-white
            p-2
            w-100
            d-flex
            justify-content-between
            link-unstyled
            text-decoration-none
            w-100
          "
          :href="'/products/' + value.id"
        >
          <div class="d-flex align-items-center w-100">
            {{ value.name }} | {{ value.price }} | {{ value.stock }} {{ text.Unit }}
          </div>
        </a>
      </div>
      <div v-if="list.Categories.length" class="rounded bg-white w-100 mb-1">
        <div class="w-100 p-1 rounded text-center text-white bg-blue">
          {{ text.Categories }}
        </div>
        <a
          v-for="value in list.Categories"
          :key="value.id"
          class="
            bg-white
            p-2
            w-100
            d-flex
            justify-content-between
            link-unstyled
            text-decoration-none
            w-100
          "
          :href="'/products/categories/' + value.id"
        >
          <div class="d-flex align-items-center w-100">
            {{ value.name }}
          </div>
        </a>
      </div>
      <div v-if="list.Clients.length" class="rounded bg-white w-100 mb-1">
        <div class="w-100 p-1 rounded text-center text-white bg-blue">
          {{ text.Clients }}
        </div>
        <a
          v-for="value in list.Clients"
          :key="value.id"
          class="
            bg-white
            p-2
            w-100
            d-flex
            justify-content-between
            link-unstyled
            text-decoration-none
            w-100
          "
          :href="'/products/clients/' + value.id"
        >
          <div class="d-flex align-items-center w-100">
            {{ value.name }}
          </div>
        </a>
      </div>
      <div v-if="list.Suppliers.length" class="rounded bg-white w-100 mb-1">
        <div class="w-100 p-1 rounded text-center text-white bg-blue">
          {{ text.Suppliers }}
        </div>
        <a
          v-for="value in list.Suppliers"
          :key="value.id"
          class="
            bg-white
            p-2
            w-100
            d-flex
            justify-content-between
            link-unstyled
            text-decoration-none
            w-100
          "
          :href="'/products/clients/' + value.id"
        >
          <div class="d-flex align-items-center w-100">
            {{ value.name }}
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "",
  props: ["text"],
  data: function () {
    return {
      name: "",
      list: [],
    };
  },
  methods: {
    async getData(input) {
      await this.showData(input);
    },
    showData(value) {
      const options = {
        method: "GET",
        url: "/search/q",
        params: { value },
      };
      axios
        .request(options)
        .then((response) => {
          this.list = response.data;
        })
        .catch(function (error) {
          console.error(error);
        });
    },
  },
  watch: {
    name: function (value) {
      this.getData(value);
      if (value == "") {
        this.list = [];
      }
    },
  },
};
</script>
