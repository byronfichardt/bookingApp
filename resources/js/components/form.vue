<template>
	<div class="ma-2 pa-2">
		<div style="margin: auto">
            <v-data-table
                v-model="selected"
                :headers="productHeaders"
                :items="products"
                :single-expand="singleExpand"
                :expanded.sync="expanded"
                item-key="name"
                hide-default-header
                show-select
                hide-default-footer
                expandIcon="mdi-information-variant"
                mobile-breakpoint="300"
                show-expand
                :dense=true
                class="elevation-1"
            >

                <template v-slot:item.quantity="{ item }">
                    <v-text-field
                        v-model="item.quantity"
                        v-if="item.display_quantity"
                        label="Qty"
                        @change="updatePrice(item)"
                    ></v-text-field>
                </template>
                <template v-slot:item.price="{ item }">
                    <label>Dkk {{item.price}}</label>
                </template>
                <template v-slot:foot>
                    <tr >
                        <td style="text-align: left" colspan="2" class="pl-2">Cost:</td>
                        <td colspan="2">
                            <label>Dkk {{sumtotal('price')}}</label>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" colspan="2" class="pl-2">Total time:</td>
                        <td colspan="2">
                            <label>Hours {{sumIntoHours}}</label>
                        </td>
                    </tr>
                </template>
                <template v-slot:expanded-item="{ headers, item }">
                    <td :colspan="headers.length">
                        {{ item.description ? item.description : "Nothing here yet:)" }}
                    </td>
                </template>
            </v-data-table>
		</div>

		<v-btn

            tile
			color="info"
			class="mr-4 mt-2 mb-2 float-right next-button"
			@click="saveProducts"
		>
			Next
		</v-btn>
	</div>
</template>
<script>
import { bus } from "../app";
export default {
	data: () => ({
        selected: [],
        productHeaders: [
            {
                text: 'Dessert',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            { text: 'Qty', value: 'quantity'},
            { text: 'Price', value: 'price', align: 'center'},
            { text: '', value: 'data-table-expand' },
        ],
        expanded: [],
        singleExpand: false,
		valid: true,
		products: [],
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
		saveProducts() {
			if (this.selected.length > 0) {
				bus.$emit("save_products", this.selected);
				bus.$emit("move_next");
			}
		},
        sumtotal(type) {
            let sum = 0;
            this.selected.forEach((element) => {
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
	},
	mounted: function () {
		this.getProducts();
	},
};
</script>
<style>
.v-input--selection-controls__input input[role="checkbox"] {

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
    padding: 0px;
}
.v-select__slot .v-select__selections .v-select__selection {
	padding-left: 15px !important;
}

@media only screen and (max-width: 600px) {
	body {
		background-color: lightblue;
	}
}
.v-data-table__expand-icon {
    background-color: #c3e8f2 !important;
    border-radius: 50% !important;
}
.v-data-table>.v-data-table__wrapper tbody tr.v-data-table__expanded__content {
    box-shadow: inset 0 2px 4px -5px rgb(50 50 50 / 75%), inset 0 -4px 4px -5px rgb(50 50 50 / 75%) !important;
}
</style>
<style scoped>
div::v-deep table > tbody > tr > td {
	border-bottom: unset !important;
}
div::v-deep table > tbody > tr > td:nth-child(3) {
    width:15%
}
div::v-deep table > tbody > tr > td:nth-child(4) {
    width:20%
}
div::v-deep table > tbody > tr > td:nth-child(1) {
    text-align: center!important;
}
</style>
