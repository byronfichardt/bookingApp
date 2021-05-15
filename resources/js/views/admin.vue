<template>
	<v-app>
		<v-container fluid class="align-start px-0 d-flex flex-row" >
			<testnav></testnav>
			<router-view></router-view>
		</v-container>
	</v-app>
</template>
<script>
import moment from "moment";
import { bus } from "../app";
import CalenderAdmin from "../components/calenderAdmin.vue";
import NavDrawer from "../components/navDrawer.vue";
import Testnav from "../components/testnav";
const default_layout = "default";
export default {
	components: {Testnav, CalenderAdmin, NavDrawer },
	data() {
		return {
			selected_date_time: "",
			events: [],
		};
	},
	watch: {
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
		this.selected_date_time = moment();
	},
};
</script>
