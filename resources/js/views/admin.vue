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
	created: function () {
		this.selected_date_time = moment();
	},
};
</script>
