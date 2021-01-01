<template>
    <div id="vue-frontend-category-tendero">
        <div v-if="dataCategory.length > 0">
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
                            class="p-3 d-flex flex-column box-content-product"
                            v-for="(item, index) in dataCategory"
                            :key="index"
                    >
                        <v-card>
                            <div v-if="item.url_image != false">
                                <figure class="image is-square is-center">
                                    <img  class="img-reseller-logo" :src="item.url_image" :alt="item.title"/>
                                </figure>
                            </div>
                            <div v-else>
                                <figure class="image is-square is-center">
                                    <img    class="img-reseller-logo"
                                            src="https://via.placeholder.com/250x250"
                                            alt="not-image"
                                    />
                                </figure>
                            </div>
                        </v-card>
                        <v-card class="flex d-flex flex-column padding-card">
                            <h2 style="font-size: 16px; text-align:center">{{ item.title }}</h2>
                            <ul class="list-category">
                                <li v-for="category in item.category">
                                    <a :href="category.url">{{ category.title }}</a>
                                </li>
                            </ul>
                        </v-card>
                        <v-card>
                            <v-btn :href="item.guid" class="btn_add_cart">
                                Ir a Tienda
                            </v-btn>
                        </v-card>
                    </v-col>
                    <!-------Column start------->
                </v-row>
            </v-container>
            <div class="text-center">
                <v-pagination
                        v-model="page"
                        :length="count_result"
                        :total-visible="7"
                        @input="consultPage"
                ></v-pagination>
            </div>
        </div>
        <div v-else>
            <h2 class="text-not-result">No hay resultados en la consulta</h2>
            <v-btn @click="_goToBack" class="center-align">Volver</v-btn>
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
    import {app} from "../../assets/js/script_params";
    import "../config/urlApi";
    import axios from "axios";

    export default {
        name: "App",

        data() {
            return {
                dataCategory: [],
                loader: false,
                page: 1,
                per_page: 20,
                count_result: 0,
            };
        },

        mounted() {
            this.consultGeneralCategory(this.page, this.per_page);
        },

        methods: {
            consultPage(page) {
                this.page = page;
                this.consultGeneralCategory(this.page, this.per_page);
            },

            consultGeneralCategory(paged, per_paged) {
                let term = app.term;
                let taxonomy = app.taxonomy;
                this.loader = true;

                axios
                    .post(this.urlConsultCategory, {
                        term,
                        taxonomy,
                        paged,
                        per_paged,
                    })
                    .then((res) => {
                        this.dataCategory = res.data[0];
                        this.count_result = Math.ceil(
                            res.data[1].number_result / this.per_page
                        );
                    })
                    .catch((error) => {
                        console.log(`Error en la consulta: ${error}`);
                    })
                    .finally(() => {
                        this.loader = false;
                        console.log("¡Se ha finalizado la consulta!");
                    });
            },

            _goToBack() {
                history.back();
            }
        },
    };
</script>
