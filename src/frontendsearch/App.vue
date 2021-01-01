<template>
    <div id="vue-frontend-products">

        <div v-if="numresults > 0">
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
                            class="p-3 d-flex flex-column margin-top box-content-product"
                            v-for="(item, index) in data"
                            :key="index"
                    >
                        <v-card>
                            <div v-if="item.sale != '' || item.sale.length > 0">
                                <div class="icon-ofert">Oferta</div>
                            </div>
                            <div v-if="item.image != false">
                                <a :href="item.guid">
                                    <figure class="image is-square is-center">
                                        <img :src="item.image" :alt="item.title"/>
                                    </figure>
                                </a>
                            </div>
                            <div v-else>
                                <a :href="item.guid">
                                    <figure class="image is-square is-center">
                                        <img
                                                src="https://via.placeholder.com/250x250"
                                                alt="no-image"
                                        />
                                    </figure>
                                </a>
                            </div>
                        </v-card>
                        <v-card>
                            <v-row style="margin-top: -115px; margin-bottom: -10px">
                                <v-col sm="4" >
                                    <div v-if="item.reseller_image != '' || item.reseller_image.length > 0">
                                        <a :href="item.guidreseller" >
                                        <figure class="image-reseller image is-64x64 align-content-center">
                                            <img
                                                    class="is-rounded"
                                                    :src="item.reseller_image"
                                                    :alt="item.reseller"
                                            />
                                        </figure>
                                        </a>
                                    </div>
                                    <div v-else>
                                        <a :href="item.guidreseller" >
                                        <figure class="image-reseller image is-64x64 align-content-center">
                                            <img
                                                    class="is-rounded"
                                                    src="https://via.placeholder.com/128x128"
                                                    alt="not-image"
                                            />
                                        </figure>
                                        </a>
                                    </div>
                                </v-col>
                                <v-col sm="8" class="p-0" >
                                    <div class="description-reseller-list">
                                        <h6 class="category-tendero">
                                            <a
                                                    v-for="(item_category, index) in item.category_reseller"
                                                    :href="item_category.url"
                                                    :key="index"
                                            >{{ item_category.title }}</a
                                            >
                                        </h6>
                                        <h4 class="category-title-tendero">{{ item.reseller }}</h4>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card>
                        <v-card class="flex d-flex flex-column padding-card">
                            <v-row>
                                <v-col sm="12" style="font-size: 12px">
                                    <ul class="list-category">
                                        <li v-for="(category, index) in item.category" :key="index">
                                            <a :href="category.url">{{ category.title }}</a>
                                        </li>
                                    </ul>
                                </v-col>
                                <v-col cols="12" sm="12" class="p-0">
                                    <h2 style="font-size: 14px; text-align: center">{{ item.title }}</h2>
                                </v-col>
                                <v-col cols="12" sm="12" class="rating-results p-0">
                                    <v-rating
                                            background-color="grey"
                                            color="warning"
                                            length="5"
                                            readonly
                                            size="15"
                                            :value="item.ratingproduct"
                                    ></v-rating>
                                </v-col>
                                <v-col cols="12" sm="12" class="p-0" >
                                    <div v-if="item.product_type === 'simple'">
                                        <div v-if="item.sale.length > 0">
                                            <p style="text-align: center" v-html="item.qurency.sale"></p>
                                            <p
                                                    style="text-decoration: line-through; text-align: center"
                                                    v-html="item.qurency.price"
                                            ></p>
                                        </div>
                                        <div v-else>
                                            <p style="text-align: center"  v-html="item.qurency.price"></p>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <p style="text-align: center"  v-html="item.product_type_price"></p>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card>
                        <v-card>
                            <div v-if="item.product_type === 'simple'">
                                <form class="cart">
                                    <input style="display:none" name="product_id" :value="item.id"/>
                                    <input style="display:none" name="quantity" value="1"/>
                                    <v-btn
                                            class="single_add_to_cart_button_success"
                                            :attr-id="item.reseller_id"
                                    >Añadir Carrito
                                    </v-btn
                                    >
                                </form>
                            </div>
                            <div v-else>
                                <a :href="item.guid"
                                   class="btn_go_product v-btn v-btn--contained theme--light v-size--default">
                                    <span class="v-btn__content">Ir al Producto</span> </a>
                            </div>
                        </v-card>

                    </v-col>
                    <!-------Column start------->
                </v-row>
            </v-container>
            <div class="text-center">
                <v-pagination
                        v-model="page"
                        :length="quanty"
                        :total-visible="7"
                        @input="actionPage"
                >
                </v-pagination>
            </div>
        </div>
        <div v-else>
            <h2 class="text-not-result">No hay resultados en la consulta</h2>
            <v-btn @click="_goToBack" class="center-align btn-go-back-info">Volver</v-btn>
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
    import FormSearch from "@/frontendproducts/FormSearch.vue";
    import "../config/urlApi";
    import axios from "axios";

    export default {
        name: "App",
        components: {
            "form-search": FormSearch,
        },
        data() {
            return {
                data: [],
                page: 1,
                post_por_page: 20,
                quanty: 1,
                loader: false,
                numresults: 0,
                rating: 4,
            };
        },
        mounted() {
            this.consultShopping();
        },
        methods: {
            consultShopping() {
                let search = this.captureSearch();
                let post_por_page = this.post_por_page;
                let paged = this.page;
                this.loader = true;
                this.consultDataGeneral(post_por_page, paged, search);
            },

            actionPage(page) {
                let search = this.captureSearch();
                let post_por_page = this.post_por_page;
                this.consultDataGeneral(post_por_page, page, search);
            },

            consultDataGeneral(post_por_page, paged, search) {
                console.log(post_por_page + " , "+ paged+",  "+ search);
                axios
                    .post(this.urlSearchGeneral, {
                        paged,
                        post_por_page,
                        search,
                    })
                    .then((res) => {
                        this.data = res.data[0];
                        this.quanty = Math.ceil(res.data[1].quanty / post_por_page);
                        this.numresults = res.data[1].quanty;
                    })
                    .catch((error) => {
                        console.log(`Error en la consulta ${error}`);
                    })
                    .finally(() => {
                        console.log("¡Consulta finalizada!");
                        this.loader = false;
                    });
            },


            captureSearch() {
                let link = window.location.href;
                let position = link.indexOf("?s=");
                link = link.slice(position).split("=");
                return link[1]
            },

            _goToBack() {
                let hostName = window.location.hostname
                window.location.assign("https://" + hostName);
            }

        },
    };
</script>