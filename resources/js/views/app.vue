<template>
	<v-app>
		<v-container style="max-width: 800px" class="bookingpadding0mobile">
			<div class="pa-2 bookingpadding0mobile">
				<tabs></tabs>
			</div>
		</v-container>
	</v-app>
</template>
<script>
import moment from "moment";
import { bus } from "../app";
import tabs from "../components/tabs.vue";
const default_layout = "default";
export default {
	components: { tabs },
	data() {
		return {
			selected_products: [],
			selected_date_time: "",
			minutes_total: 0,
			events: [],
		};
	},
	methods: {
		intercept(start1, end1, start2, end2) {
			return (
				Math.max(
					0,
					Math.min(end2, end1) - Math.max(start1, start2) + 1
				) > 0
			);
		},
	},
	watch: {
		selected_products: function selectedProduct(newVal) {
			this.minutes_total = 0;
			newVal.forEach((element) => {
				this.minutes_total += parseInt(element.minutes) * parseInt(element.hasOwnProperty('quantity') ? element.quantity : 1);
			});
		},
	},
	created: function () {
		bus.$on("save_products", (data) => {
			console.log(data);
			this.selected_products = data;
		});
		bus.$on("selected_date_time", (event) => {
			this.selected_date_time = moment(event).format(
				"YYYY-MM-DD HH:mm:SS"
			);
		});
		bus.$on("all_events", (event) => {
			this.events = event;
		});
	},
};
</script>
<style>
.v-application--wrap {
    min-height: unset!important;
}
@media only screen and (max-width: 600px) {
    .bookingpadding0mobile {
        padding: 0px!important;
    }
    .v-application .pa-2 {
        padding: 0px!important;
    }
}

</style>

