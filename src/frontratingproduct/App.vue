<template>
    <v-card>
            <div class="text-center ma-7 mb-4">

                <v-rating
                        background-color="grey"
                        color="warning"
                        length="5"
                        readonly
                        size="15"
                        :value="Number(this.responde)"
                ></v-rating>

            </div>
    </v-card>
</template>

<script>
    import {app} from "../../assets/js/script_params";
    import "../config/urlApi";
    import axios from "axios";

    export default {
        data: () => ({
            responde: '',
        }),
        mounted(){
            this.consultStart();
        },
        methods:{
            async consultStart(){
                    let id_shop = app.id_shop;
                    let res = await  axios.post(this.urlStartProduct, {id_shop});
                    if(res.data != ""){
                        this.responde =  res.data;
                    }else {
                        this.responde = 0;
                    }

                    console.log(res.data);
            }
        }
    }
</script>