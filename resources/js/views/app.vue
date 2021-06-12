<template>
	<v-app>
		<v-container style="max-width: 800px">
			<div class="pa-2">
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
				this.minutes_total += parseInt(element.minutes);
			});
		},
		selected_date_time: function selectedDateTime(newVal) {
			let bookingstart = moment(newVal);
			let bookingend = moment(newVal).add(this.minutes_total, "m");
			this.events.forEach((element) => {
				let eventStart = moment(element.start);
				let eventEnd = moment(element.end);
				if (
					this.intercept(
						bookingstart,
						bookingend,
						eventStart,
						eventEnd
					)
				) {
					bus.$emit("intercept_alert", [
						bookingstart,
						bookingend,
						eventStart,
						eventEnd,
					]);
				}
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
</style>

