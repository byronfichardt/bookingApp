<template>
	<v-app>
		<v-container fluid class="align-start px-0 d-flex flex-row" >
			<testNav></testNav>
			<router-view></router-view>
		</v-container>
	</v-app>
</template>
<script>
import moment from "moment";
import CalenderAdmin from "../components/Admin/calenderAdmin.vue";
import testNav from "../components/navigation";
const default_layout = "default";
export default {
	components: {testNav, CalenderAdmin },
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
