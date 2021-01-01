<template>
  <div id="vue-frontend-tendero">
    <div v-if="result.length > 0">
      <v-container>
        <!-----Column Shopping----->
        <v-col
          class="grey lighten-5 mt-6"
          v-for="(item, index) in result"
          :key="index"
        >
          <v-row no-gutters>
            <v-col cols="12" xs="12" sm="4" md="3" lg="2" xl="2">
              <figure class="image is-square is-center">
                <img :src="item.url_image" :alt="item.title" />
              </figure>
            </v-col>
            <v-col cols="12" xs="12" sm="8" md="9" lg="9" xl="9">
              <h1 style="font-size: 28px; text-align: center">{{ item.title }}</h1>
              <ul class="list-category">
                <li v-for="value in item.category">{{ value }}</li>
              </ul>
              <p>{{ item.content }}</p>
              <v-btn class="btn_add_cart" :href="item.guid"
                >Ingresar Tienda
              </v-btn>
            </v-col>
          </v-row>
        </v-col>
        <!-----End Column Shopping----->
      </v-container>
      <div justify="center">
        <v-pagination
          v-model="page"
          class="my-4"
          :length="lengthpage"
          :total-visible="7"
          prev-icon="mdi-menu-left"
          next-icon="mdi-menu-right"
          @input="actionPage"
        ></v-pagination>
      </div>
    </div>
    <div v-else>
      <h3 class="text-not-result">No hay resultados en la consulta</h3>
      <v-btn @click="_goToBack" class="center-align btn-go-back-info" >Volver</v-btn>
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
      result: [],
      page: 1,
      lengthpage: 0,
      posts_per_page: 6,
      loader: false,
    };
  },
  created() {
    this.consultReseller();
  },
  methods: {
    consultReseller() {
      this.loader = true;
      this.consultGeneral(this.page);
    },

    actionPage(page) {
      this.loader = true;
      this.page = page;
      this.consultGeneral(this.page);
    },
    consultGeneral(page) {
      let posts_per_page = this.posts_per_page;

      axios
        .post(this.urlConsulTendero, { page, posts_per_page })
        .then((res) => {
          this.result = res.data;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.loader = false;
          this.lengthpage = Math.ceil(
            this.result[0].number_result / posts_per_page
          );
        });
    },
    _goToBack(){
      history.back();
    }
  },
};
</script>
