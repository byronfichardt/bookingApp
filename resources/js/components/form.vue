<template>
	<div class="ma-2 pa-2">
		<div style="margin: auto">
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
									label="Qty"
									@change="updatePrice(item)"
								></v-text-field>
							</td>
							<td>
                                <label>Dkk {{item.price}}</label>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td style="text-align: left" colspan="2">Cost:</td>
							<td colspan="2">
                                <label>Dkk {{sumtotal('price')}}</label>
<!--								<v-text-field-->
<!--									v-model="sumPrice"-->
<!--									disabled-->
<!--								></v-text-field>-->
							</td>
						</tr>
						<tr>
							<td style="text-align: left" colspan="2">Total time:</td>
							<td colspan="2">
                                <label>Hours {{sumIntoHours}}</label>
<!--								<v-text-field-->
<!--									v-model="sumIntoHours"-->
<!--									disabled-->
<!--								></v-text-field>-->
							</td>
						</tr>
					</tfoot>
				</template>
			</v-simple-table>
		</div>

		<v-btn

            tile
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
		updatePrice(item) {
			item.origanalPrice
				? item.origanalPrice
				: (item.origanalPrice = item.price);
			item.price = 1 * item.origanalPrice;
		},
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
        sumtotal(type) {
            let sum = 0;
            this.products.forEach((element) => {
                if (element.selected) {
                    let value = parseInt(element[type]);
                    let total = 0
                    let itemQty = 1
                    if (element.hasOwnProperty("quantity")) {
                        if(element.quantity) {
                            itemQty = element.quantity
                        }
                    }
                    total = value * parseInt(itemQty);
                    sum = parseFloat(sum) + parseInt(total);
                }
            });
            return sum;
        },

	},
	computed: {
        sumIntoHours() {
            let minutes = this.sumtotal('minutes') % 60;
            return (
                parseInt((this.sumtotal('minutes') / 60).toFixed(2)) +
                " and " +
                minutes +
                " minutes"
            );
        },
        checkQuantity(value, element) {
            if (element.hasOwnProperty("quantity")) {
                let itemQty = 1
                if(element.quantity) {
                    itemQty = element.quantity
                }
                return value * parseInt(itemQty);
            }
        },
		sumMinutes() {
			let sum = 0.0;
			this.products.forEach((element) => {
				if (element.selected) {
					let minutes = parseInt(element.minutes);
                    total = this.checkQuantity(minutes, element);
					sum = parseInt(sum) + parseInt(total);
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
.v-label,
.v-input {
	font-size: 0.875rem !important;
}
.v-data-table > .v-data-table__wrapper > table > tbody > tr > td,
.v-data-table > .v-data-table__wrapper > table > tfoot > tr > td,
.v-data-table > .v-data-table__wrapper > table > thead > tr > td {
	font-size: 0.875rem !important;
}
.v-select__slot .v-select__selections .v-select__selection {
	padding-left: 15px !important;
}

@media only screen and (max-width: 600px) {
	body {
		background-color: lightblue;
	}
}
</style>
<style scoped>
div::v-deep table > tbody > tr > td {
	border-bottom: unset !important;
}
</style>
