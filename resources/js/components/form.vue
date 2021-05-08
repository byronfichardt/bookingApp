<template>
	<div class="ma-2 pa-2">
		<h5 style="text-align: center">{{ intro }}</h5>

		<br />
		<hr />
		<div style="margin: auto" class="ml-6">
			<v-simple-table>
				<template v-slot:default>
					<tbody>
						<tr v-for="(item, index) in products" :key="index">
							<td>
								<v-checkbox
									light
									:label="item.name"
									v-model="item.selected"
									style="flex: 1 1 auto"
								>
								</v-checkbox>
							</td>
							<td>
								<v-text-field
									v-model="item.quantity"
									v-if="item.display_quantity"
									label="Number of ..."
								></v-text-field>
							</td>
							<td>
								<v-text-field
									v-model="item.price"
									disabled
									label="Price"
								></v-text-field>
							</td>
							<td>
								<v-text-field
									v-model="item.minutes"
									disabled
									label="Time"
								></v-text-field>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td>Totals:</td>
							<td>
								<v-text-field
									v-model="sumPrice"
									disabled
								></v-text-field>
							</td>
							<td>
								<v-text-field
									v-model="sumIntoHours"
									disabled
								></v-text-field>
							</td>
						</tr>
					</tfoot>
				</template>
			</v-simple-table>
		</div>

		<v-btn
			color="info"
			class="mr-4 mt-2 mb-2 float-right"
			@click="saveProducts"
		>
			Next
		</v-btn>
	</div>
</template>
<script>
import { bus } from "../app";
import productTable from "./table.vue";
export default {
	components: { productTable },
	data: () => ({
		intro:
			"Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
		valid: true,
		selected_products: [],
		products: [
			{ name: "baby boomer", price: 160, minutes: 60 },
			{ name: "baby boomer 1", price: 160, minutes: 60 },
			{ name: "baby boomer 2", price: 160, minutes: 60 },
			{ name: "baby boomer 3", price: 160, minutes: 60 },
		],
		sum: {
			price: 0.0,
			minutes: 0,
		},
	}),

	methods: {
		getProducts() {
			axios.get("api/products").then((response) => {
				this.products = response.data;
			});
		},
		setSelectedProducts() {
			this.products.forEach((element) => {
				if (element.selected) {
					this.selected_products.push(element);
				}
			});
		},
		saveProducts() {
			this.setSelectedProducts();
			if (this.selected_products.length > 0) {
				bus.$emit("save_products", this.selected_products);
				bus.$emit("move_next");
			}
		},
	},
	computed: {
		sumIntoHours() {
			let minutes = this.sumMinutes % 60;
			return (
				parseInt((this.sumMinutes / 60).toFixed(2)) +
				" and " +
				minutes +
				" minutes"
			);
		},
		sumPrice() {
			let sum = 0.0;
			this.products.forEach((element) => {
				if (element.selected) {
					let price = parseFloat(element.price);
					if (element.hasOwnProperty("quantity")) {
						price = price * parseFloat(element.quantity);
					}
					sum = parseFloat(sum) + parseFloat(price);
				}
			});
			return sum;
		},
		sumMinutes() {
			let sum = 0.0;
			this.products.forEach((element) => {
				if (element.selected) {
					let minutes = parseInt(element.minutes);
					if (element.hasOwnProperty("quantity")) {
						minutes = minutes * parseInt(element.quantity);
					}
					sum = parseInt(sum) + parseInt(minutes);
				}
			});
			return sum;
		},
	},
	mounted: function () {
		this.getProducts();
	},
};
</script>
<style>
.v-input--selection-controls__input input[role="checkbox"] {
	opacity: 1 !important;
}
.v-select__slot .v-label {
	left: 15px !important;
}
.v-select__slot .v-select__selections .v-select__selection {
	padding-left: 15px !important;
}
</style>
<style scoped>
div::v-deep table > tbody > tr > td {
	border-bottom: unset !important;
}
</style>