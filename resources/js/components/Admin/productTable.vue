<template>
	<div style="width: 100%">
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
		<v-simple-table>
			<template v-slot:default>
				<thead>
					<tr>
						<th class="text-left">Name</th>
						<th class="text-left">Price</th>
						<th class="text-left">Time</th>
						<th class="text-left">Should display Qty</th>
                        <th class="text-left">Sort Order</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in products" :key="item.name">
						<td>{{ item.name }}</td>
						<td>{{ item.price }}</td>
						<td>{{ item.minutes }}</td>
						<td>
							{{ item.display_quantity == 1 ? "Yes" : "No" }}
						</td>
                        <td>{{ item.sort_order }}</td>
						<td>
							<v-btn
								color="red"
								text
								@click="deleteItem(item.id)"
							>
								Delete
							</v-btn>
							<v-btn color="blue" text @click="editItem(item)">
								Edit
							</v-btn>
						</td>
					</tr>
				</tbody>
			</template>
		</v-simple-table>
		<add-item-form></add-item-form>
		<edit-item-form></edit-item-form>
	</div>
</template>
<script>
import { bus } from "../../app";
import addItemForm from "./addItemForm.vue";
import editItemForm from "./editItemForm.vue";
export default {
	components: { addItemForm, editItemForm },
	data() {
		return {
			products: null,
		};
	},
	methods: {
		openAddItemForm() {
			bus.$emit("open_add_item_form");
		},
		getProducts() {
			axios.get("api/products").then((response) => {
				this.products = response.data;
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
