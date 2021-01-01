<template>
  <div id="vue-frontend-general-tendero">
    <div v-if="data.length > 0">
      <v-container class="grey lighten-5">
        <v-row no-gutters>
          <!-------Column start------->
          <v-col
            cols="6"
            xs="6"
            sm="6"
            md="6"
            lg="3"
            xl="3"
            class="p-3 d-flex flex-column"
            v-for="(item, index) in data"
            :key="index"
          >
            <v-card>
              <div v-if="item.urlimage != false ">
                <figure class="image is-square is-center">
                  <img class="img-reseller-logo" :src="item.urlimage" :alt="item.title" />
                </figure>
              </div>
              <div v-else>
                <figure class="image is-128x128 is-center">
                  <img class="img-reseller-logo" src="https://via.placeholder.com/250x250" alt="not-image" />
                </figure>
              </div>
            </v-card>
            <v-card class="flex d-flex flex-column padding-card">
              <h2 style="font-size: 16px; text-aling: center">{{item.title}}</h2>
              <ul class="list-category">
                <li v-for="category in data.category">{{category.title}}</li>
              </ul>
            </v-card>
            <v-card>
              <v-btn :href="item.guid" class="btn_add_cart">Ir a Tienda</v-btn>
            </v-card>
          </v-col>
          <!-------Column start------->
        </v-row>
      </v-container>
    </div>
    <div v-else>
      <h2 class="text-not-result">No hay resultados en la consulta.</h2>
      <v-btn class="center-align" @click="_goToBack">Volver</v-btn>
    </div>
    <div class="background-loader" v-show="loader === true">
      <div class="container">
        <div class="lds-ripple">
          <div></div>
          <div></div>
        </div>
        <p>Cargando...</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import "../config/urlApi";

export default {
  name: "App",

  data() {
    return {
      data: [],
      loader: false,
    };
  },

  mounted() {
    this.consultDataGeneral();
  },

  methods: {
    consultDataGeneral() {
      this.loader = true;
      axios
        .get(this.urlConsultGeneral)
        .then((res) => {
          this.data = res.data;
        })
        .catch((error) => {
          console.log(`Error en la comsutla ${error}`);
        })
        .finally(() => {
          console.log("¡Se ha finalizado la consulta!");
          this.loader = false;
        });
    },
    _goToBack(){
      history.back();
    }
  },
};
</script>

