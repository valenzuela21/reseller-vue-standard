<template>
    <div>
        <form class="cart">
            <div class="container">
                <div v-if="loader == true" class="loader-variable">
                    <div  class="container-loader">
                        <div class="lds-ripple">
                            <div></div>
                            <div></div>
                        </div>
                        <p>Cargando...</p>
                    </div>
                </div>
                <div>
                    <div v-if="postype == 'variable' && postype != ''"
                         class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <select @change="actionVariable($event, $event.target.selectedIndex)" style="margin: 0px"
                                v-model="product_variable">
                            <option value="">Seleccione el producto</option>
                            <option v-for="(data,  index) in variation_data" 
                                    v-if="data.variation_is_active == true &&  data.regular_price != '' "
                                    :value="data.variation_id" :key="index" >
                            <span v-for="item in data.productvariations" >
                                {{ item }}
                            </span>
                            </option>
                        </select>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <input style="display: none" type="text" name="product_id" v-model="id_shop"/>
                                <input class="btn-counter" type="button" v-on:click="counter -= 1" value="-">
                                <input type="number" class="text-counter" disabled="disabled" name="quantity"
                                       v-model="counter"/>
                                <input class="btn-counter" type="button" v-on:click="counter += 1" value="+">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <button style="margin-top: 4px;" :attr-id="id_reseller"
                                        :attr-id-variation="id_variation"
                                        class="single_add_to_cart_button_variable">
                                        Adjuntar Al Carrito
                                </button>
                            </div>

                            <div v-if="product_variable != ''">
                                <div v-html="pressing_varible"></div>
                            </div>

                        </div>
                    </div>
                    <div v-else>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <input style="display: none" type="text" name="product_id" v-model="id_shop"/>
                                <input class="btn-counter" type="button" v-on:click="counter -= 1" value="-">
                                <input type="number" class="text-counter" disabled="disabled" name="quantity"
                                       v-model="counter"/>
                                <input class="btn-counter" type="button" v-on:click="counter += 1" value="+">
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <button style="margin-top: 4px;" :attr-id="id_reseller"
                                        class="single_add_to_cart_button_success">
                                    Adjuntar Al Carrito
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
    import {app} from '../../assets/js/script_params';
    import 'config/urlApi';
    import axios from 'axios';

    export default {
        name: "App",
        data() {
            return {
                variation_data: [],
                counter: 1,
                product_variable: '',
                postype: '',
                id_shop: app.id_shop,
                id_reseller: app.id_reseller,
                id_variation: '',
                pressing_varible: '',
                loader: false,
          
            }
        },
        created() {
            this.loader = true
            this.consultShop();
        },
        watch: {
            // cada vez que la pregunta cambie, esta función será ejecutada
            counter: function (val) {
                if (val <= 1) {
                    this.counter = 1;
                }
            }
        },
        methods: {
          
            actionVariable(event, selectedIndex) {
                document.getElementsByClassName('wrap-single-product-images')[0].setAttribute('id', 'image_product_woocommerce');

                this.id_variation = event.target.value;
                let index = selectedIndex - 1;

                this.pressing_varible = this.variation_data[index].price_html;

                axios.post(this.urlVariableImage, {id: this.id_variation} )
                    .then((resp)=>{
                        document.getElementById('image_product_woocommerce').innerHTML = resp.data;
                    })
                    .catch((error)=>{
                        console.log(`Error en la consulta ${error}`)
                    })
                    .finally(()=>console.log('Se ha finalizado la consulta'))

            },

            consultShop() {

                let id_shop = this.id_shop
                axios.post(this.urlVariableProduct, {id_shop})
                    .then((res) => {
                 
                        if(res.data[0].product_type == undefined){
                            this.variation_data = [];
                        }else{
                            this.variation_data = res.data;
                            this.postype = res.data[0].product_type;
                        }

                    })
                    .catch((error) => {
                        console.log(`Error en la consulta: ${error}`);
                    })
                    .finally(() => {
                        console.log('Consulta finalizada');
                        this.loader = false;
                    });
            }
        }

    }
</script>