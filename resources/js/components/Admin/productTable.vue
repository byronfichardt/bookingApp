<template>
	<div style="width: 100%; margin-top: 30px">
        <v-card
            class="d-flex flex-row-reverse"
            color="none"
            flat
            tile
            style="padding-right: 15px"
        >
            <v-btn
                color="accent"
                elevation="2"
                outlined
                small
                @click="openAddItemForm"
                >Add item</v-btn
            >
        </v-card>
        <v-data-table
            :headers="headers"
            :items="products"
        >
            <template v-slot:item.actions="{ item }">
                <v-btn
                    color="red"
                    @click="deleteItem(item.id)"
                >
                    Delete
                </v-btn>
                <v-btn color="blue" @click="editItem(item)">
                    Edit
                </v-btn>
                <v-btn color="blue" @click="editPrices(item)">
                    Prices
                </v-btn>
            </template>

        </v-data-table>


		<add-item-form></add-item-form>
		<edit-item-form></edit-item-form>
        <product-prices></product-prices>
	</div>
</template>
<script>
import { bus } from "../../app";
import addItemForm from "./addItemForm.vue";
import editItemForm from "./editItemForm.vue";
import ProductPrices from "./productPrices";
export default {
	components: {ProductPrices, addItemForm, editItemForm },
	data() {
		return {
			products: [],
            headers: [
                {
                    text: 'Name',
                    align: 'left',
                    value: 'name'
                },
                { text: 'Price', value: 'price' },
                { text: 'Description', value: 'description' },
                { text: 'Time', value: 'minutes' },
                { text: 'Display Qty', value: 'display_quantity' },
                { text: 'Sort Order', value: 'sort_order' },
                { text: 'Actions', value: 'actions' },
            ],
		};
	},
	methods: {
		openAddItemForm() {
			bus.$emit("open_add_item_form");
		},
		getProducts() {
			axios.get("api/products").then((response) => {
				this.products = response.data.data;
			});
		},
		deleteItem(id) {
			axios.delete("api/products/" + id).then((response) => {
				if (response.data == "success") {
					this.getProducts();
				}
			});
		},
		editItem(item) {
			bus.$emit("open_edit_item_form", item);
		},
        editPrices(item) {
            bus.$emit("open_edit_prices_form", item);
        },
	},
	mounted: function () {
		this.getProducts();
	},
	created: function () {
		bus.$on("item_created", (event) => {
			this.getProducts();
		});
	},
};
</script>
