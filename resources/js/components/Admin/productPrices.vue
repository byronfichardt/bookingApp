<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">Item Prices ({{ item ? item.name : ''}})</span>
            </v-card-title>
            <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="prices"
                    class="elevation-1"
                >
                    <template v-slot:top>
                        <v-toolbar
                            flat
                        >
                            <v-dialog
                                v-model="priceDialog"
                                max-width="500px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        color="primary"
                                        dark
                                        class="mb-2"
                                        v-bind="attrs"
                                        v-on="on"
                                    >
                                        New Price
                                    </v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="text-h5">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="editedItem.price"
                                                        label="Price"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="editedItem.start_date"
                                                        label="Start Date"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="4"
                                                >
                                                    <v-text-field
                                                        v-model="editedItem.end_date"
                                                        label="End Date"
                                                    ></v-text-field>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="close"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="save"
                                        >
                                            Save
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogDelete" max-width="500px">
                                <v-card>
                                    <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            small
                            @click="deleteItem(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                </v-data-table>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialog = false">
                    Close
                </v-btn>
                <v-btn color="blue darken-1" text @click="savePrices">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>
<script>
import { bus } from "../../app";
import addItemForm from "./addItemForm.vue";
import editItemForm from "./editItemForm.vue";
export default {
	data() {
		return {
		    item: null,
            dialog: false,
            priceDialog: false,
            dialogDelete: false,
			prices: [],
            headers: [
                { text: 'Price', value: 'price', align: "center" },
                { text: 'StartDate', value: 'start_date', align: "center" },
                { text: 'EndDate', value: 'end_date', align: "center" },
                { text: 'Actions', value: 'actions', sortable: false, align: "center" },
            ],
            editedIndex: -1,
            editedItem: {
                price: 0,
                start_date: '',
                end_date: '',
            },
            defaultItem: {
                price: 0,
                start_date: '',
                end_date: '',
            },
		};
	},
    watch: {
        priceDialog (val) {
            val || this.close()
        },
        dialogDelete (val) {
            val || this.closeDelete()
        },
    },
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },
	methods: {
        editItem (item) {
            this.editedIndex = this.prices.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.priceDialog = true
        },

        deleteItem (item) {
            this.editedIndex = this.prices.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm () {
            this.prices.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        close () {
            this.priceDialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save () {
            if (this.editedIndex > -1) {
                Object.assign(this.prices[this.editedIndex], this.editedItem)
            } else {
                this.prices.push(this.editedItem)
            }
            this.close()
        },

		getProductPrices(item) {
			axios.get("api/product-prices?product_id=" + item.id).then((response) => {
				this.prices = response.data.data;
			});
		},
        savePrices() {
            let data = {
                product: this.item,
                prices: this.prices
            }
            axios.post("api/product-prices", data).then((response) => {
                if (response.data == "success") {
                    this.priceDialog = false;
                    this.dialog = false
                }
            });
        },
	},
	created: function () {
		bus.$on("open_edit_prices_form", (event) => {
		    this.dialog = true;
		    this.item = event;
			this.getProductPrices(event);
		});
	},
};
</script>
