<template>
  <v-col>
    <v-row no-gutters>
      <v-col class="mt-2" cols="7" xs="12" sm="4" md="4" lg="6" xl="6">
        <v-btn class="btn-go-back-info" @click="actionBack" >Volver</v-btn>
      </v-col>
      <v-col class="d-flex mt-2" cols="5" xs="6" sm="4" md="4" lg="3" xl="3">
        <div v-show="showselect == true" class="select is-small">
          <select v-model="selectCategory" @change="searchCategory($event)">
            <option value="">Seleccione Categoria</option>
            <option
              v-for="(select, index) in select_data"
              :key="index"
              :value="select.slug"
            >
              {{ select.title }}
            </option>
          </select>
        </div>
      </v-col>
      <v-col class="mt-2" cols="12" xs="12" sm="4" md="4" lg="3" xl="3">
        <input
          v-model="searchProduct"
          class="search-input"
          type="text"
          placeholder="Buscar..."
        />
        <v-btn @click="actionSearch" class="btn_search"
          ><span class="icon_search"></span
        ></v-btn>
      </v-col>
    </v-row>
  </v-col>
</template>

<script>
import axios from "axios";
import puente from "../config/puente";
import { app } from "../../assets/js/script_params";
import "../config/urlApi";

export default {
  name: "FormSearch",

  data() {
    return {
      select_data: [],
      selectCategory: "",
      searchProduct: "",
      showselect: false,
    };
  },

  mounted() {
    this.consultSelectCategory();
  },

  methods: {

    actionBack(){
        document.location.href="/";
    },

    searchCategory(event) {
      puente.$emit("select-category", event.target.value);
    },

    actionSearch() {
      puente.$emit("search-product", this.searchProduct);
    },

    consultSelectCategory() {
      let id_shop = app.id_shop;
      axios
        .post(this.urlSelectCategory, { id_shop })
        .then((res) => {
          this.select_data = res.data.reduce(
            (prev, next) => Object.assign(prev, { [next.title]: next }),
            {}
          );
          this.showselect = true;
        })
        .catch((error) => {
          console.log(`Error en la conuslta ${error}`);
          this.showselect = false;
        })
        .finally(() => {
          console.log("Finalizada la consulta");
        });
    },
  },
};
</script>
