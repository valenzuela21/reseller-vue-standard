<template>
    <v-container>
        <v-main v-show="dialog === true">
            <div class="shadow-modal"></div>
            <v-col class="modal-add">
                <span @click="close" class="close_modal"></span>
                <v-container class="grey lighten-5">
                    <h3 class="headline">Adjuntar Tienda</h3>
                    <v-row no-gutters>
                        <v-col sm="8">
                            <select v-model="typereseller" style="width: 100%">
                                <option selected value="">Seleccione la tienda</option>
                                <option v-for="item in shopping" :value="item.id">{{item.title}}</option>
                            </select>
                        </v-col>
                        <v-col sm="3">
                            <v-btn class="btn-add" @click="addGeneral">Adjuntar</v-btn>
                        </v-col>
                    </v-row>
                </v-container>

            </v-col>
        </v-main>
        <v-data-table
                :headers="headers"
                :items="desserts"
                :page.sync="page"
                :items-per-page="itemsPerPage"
                hide-default-footer
                class="elevation-1"
                @page-count="pageCount = $event"
        >
            <template v-slot:top>
                <v-toolbar flat color="white">
                    <v-toolbar-title>
                        Mis Tenderos
                    </v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                                    color="primary"
                                    dark
                                    class="mb-2"
                                    v-bind="attrs"
                                    v-on="on"
                            >Agregar Tendero
                            </v-btn>
                        </template>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">

                <v-icon
                        small
                        @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>
        <div class="text-center pt-2">
            <v-pagination
                    v-model="page"
                    :length="pageCount"
            ></v-pagination>
            <select style="margin: auto; display: block;" v-model="itemsPerPage" >
                <option value="5"> Cantidad de Items </option>
                <option value="10"> 10 </option>
                <option value="25"> 25 </option>
                <option value="50"> 50 </option>
                <option value="80"> 80 </option>
                <option value="100"> 100 </option>
            </select>

        </div>
        <div class="loader-admin-table" v-if="loader === true">
            <div class="loader">
                <div style="display: block">
                    <div class="lds-ripple">
                        <div></div>
                        <div></div>
                    </div>
                    <p>Loader...</p>
                </div>

            </div>
        </div>
    </v-container>
</template>
<script>
    import axios from 'axios';
    import "../config/urlApi";

    export default {
        name: "App",
        data: () => ({
            dialog: false,
            page: 1,
            pageCount: 0,
            itemsPerPage: 5,
            headers: [
                {text: 'ID', value: 'id', width: 100},
                {text: 'Tendero', value: 'title'},
                {text: 'Opciones', value: 'actions', width: 100},
            ],
            typereseller: '',
            desserts: [],
            shopping: [],
            editedIndex: -1,
            defaultItem: {
                id: 0,
                title: '',
            },
            loader: false,
        }),


        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        mounted() {
            this.consultShopping();
            this.consultPostype();
        },

        methods: {

            deleteItem(item) {
                const index = this.desserts.indexOf(item);
                const id = this.get_post_id();
                const id_post = item.id;
                const alert = confirm('¿Deseas eliminar este tendero?');

                if (alert == true) {
                    axios.post(this.urlDeleteReseller, {id, id_post})
                        .then(res => {
                            console.log("Eliminando...")
                        }).catch(error => {
                        console.log(`Error en la consulta ${error}`);
                    }).finally(() => {
                        this.desserts.splice(index, 1);
                        console.log("Se ha eliminado correctamente.");
                    })
                } else {
                    console.log("Cancelado la acción por el usuario");
                }

            },

            close() {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            addGeneral() {
                let id = this.typereseller;
                this.insertPostype(id);
                this.close();
            },


            consultPostype() {
                axios.get(this.urlConsultSelectReseller)
                    .then(res => {
                        this.shopping = res.data;
                    }).catch(error => {
                    console.log(`Error en la consulta ${error}`);
                }).finally(() => {
                    console.log('Se ha finalizado');
                })

            },


            insertPostype(id_reseller) {

                let id = this.get_post_id();

                axios.post(this.urlInsertNewReseller, {
                    id,
                    id_reseller
                })
                    .then(res => {
                        console.log("Insertando...");
                        this.consultShopping();
                    })
                    .catch(error => {
                        console.log(`Error al adjuntar nuevo dato.  ${error}`)
                    }).finally(() => {
                    console.log("Se ha insertado correctamente");
                })
            },

            consultShopping() {
                let id = this.get_post_id();
                this.loader = true;
                axios.post(this.urlConsultResellerMetabox, {id})
                    .then(res => {
                        this.desserts = res.data;
                    })
                    .catch(error => {
                        console.log(`Error al adjuntar nuevo dato.  ${error}`)
                    })
                    .finally(() => {
                        this.loader = false;
                        console.log('Consultando tiendas disponibles');
                    })
            },

            get_post_id() {
                let url = window.location.href
                let position = url.indexOf('?post=');
                let id = url.substr(position)
                    .split("&");
                id = id[0].split("=");
                id = id[1];
                return id;
            }

        },
    }
</script>

