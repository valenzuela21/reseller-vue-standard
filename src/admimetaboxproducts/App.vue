<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="desserts"
                :search="search"
                :page.sync="page"
                :items-per-page="itemsPerPage"
                sort-by="calories"
                class="elevation-1"
                hide-default-footer
                @page-count="pageCount = $event"
        >
            <template v-slot:top>
                <v-toolbar
                        flat
                >
                    <v-toolbar-title>Mis Productos</v-toolbar-title>

                    <v-spacer></v-spacer>
                    <v-text-field
                            class="search-input-products"
                            v-model="search"
                            append-icon="mdi-magnify"
                            label="Search"
                            single-line
                            hide-details
                    ></v-text-field>

                </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                        small
                        @click="deleteItem(item)"
                >
                    mdi-delete
                </v-icon>
                <v-icon
                        small
                        @click="goPage(item.guid)"
                >
                    mdi-content-paste
                </v-icon>
            </template>

        </v-data-table>
        <div class="text-center pt-2">
            <v-pagination
                    v-model="page"
                    :length="pageCount"
            ></v-pagination>
            <select style="margin: auto; display: block;" v-model="itemsPerPage">
                <option value="10">Cantidad de Items</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="80">80</option>
                <option value="100">100</option>
                <option value="150">150</option>
                <option value="200">200</option>
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
    </div>

</template>
<script>
    import axios from 'axios';
    import {app} from '../../assets/js/script_params';
    import '../config/urlApi';

    export default {
        name: "App",
        data: () => ({
            search: '',
            loader: false,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            headers: [
                {text: 'ID', value: 'id'},
                {text: 'Titel', value: 'title'},
                {text: 'Publicado', value: 'poststatus'},
                {text: 'Opciones', value: 'actions', sortable: false}
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                calories: 0,
                fat: 0,
                carbs: 0,
                protein: 0,
            },
            defaultItem: {
                name: '',
                calories: 0,
                fat: 0,
                carbs: 0,
                protein: 0,
            },
        }),

        mounted() {
            this.consultProducts();
        },

        methods: {

            goPage(item_url) {
                window.open(item_url, '_blank');
            },

            deleteItem(item) {
                let alert = confirm("Deseas eliminar este producto");
                if (alert) {
                    let id_post = item.id;
                    let id_reseller = app.id_shop;
                    axios.post(this.urlDeleteProductReseller, {
                        id_post,
                        id_reseller
                    }).then(res => {
                        console.log("Precesando...")
                    }).catch(error => console.log(`Error en la consulta ${error}`))
                        .finally(() => {
                            this.editedIndex = this.desserts.indexOf(item);
                            this.desserts.splice(this.editedIndex, 1)
                            console.log("Se ha finalizado la consulta");
                        });


                } else {
                    console.log("Cancelada eliminación de esté item.")
                }

            },

            close() {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            consultProducts() {
                this.loader = true;
                let id_shop = app.id_shop;

                console.log(id_shop)

                axios.post(this.urlSearchProducts, {
                    id_shop
                })
                    .then((res) => {
                        this.desserts = res.data;
                    }).catch(error => {
                    console.log(`Error Consulta: ${error}`);
                }).finally(() => {
                    console.log("Finalizado la consulta");
                    this.loader = false;
                })
            }
        },
    }
</script>



