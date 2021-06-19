<template>
	<v-card>
		<v-tabs v-model="tab" centered icons-and-text touchless>
			<v-tab href="#tab-1" disabled> Products </v-tab>
			<v-tab href="#tab-2" disabled> Date </v-tab>
			<v-tab href="#tab-3" disabled> Your Info </v-tab>
			<v-tab href="#finished" disabled> Thank You </v-tab>
		</v-tabs>

		<v-tabs-items v-model="tab" touchless>
			<v-tab-item :value="'tab-1'">
				<productForm></productForm>
			</v-tab-item>
			<v-tab-item :value="'tab-2'">
				<calender
					calander-type="month"
					:startDate="selected_date"
				></calender>
			</v-tab-item>
			<v-tab-item :value="'tab-3'">
				<info-form></info-form>
			</v-tab-item>
			<v-tab-item :value="'finished'">
				<div class="ma-3 text-center">
					<p class="display-1 text--primary">Thank you!!</p>
					<div class="text--primary">
						Thank you for booking with us. We look forward to seeing
						you!
					</div>
				</div>
			</v-tab-item>
		</v-tabs-items>
	</v-card>
</template>
<script>
import { bus } from "../app";
import calender from "./calender.vue";
import productForm from "./form.vue";
import InfoForm from "./infoForm.vue";
export default {
	components: { calender, productForm, InfoForm },
	data() {
		return {
			tab: "tab-1",
			selected_date: "",
		};
	},
	created: function () {
		bus.$on("move_next", () => {
			let currentTab = this.tab.split("-").pop();
			let nextTab = parseInt(currentTab) + 1;
			this.tab = "tab-" + nextTab;
		});
		bus.$on("date_selected", (event) => {
			this.selected_date = event.date;
		});
		bus.$on("finished", () => {
			this.tab = "finished";
		});
	},
};
</script>
<style>
.theme--light.v-tabs > .v-tabs-bar {
	background: rgb(139, 225, 255);
	background: linear-gradient(
		180deg,
		rgba(139, 225, 255, 1) 0%,
		rgba(0, 241, 255, 1) 100%
	);
	color: black;
}
.theme--light.v-tabs > .v-tabs-bar .v-tab--disabled {
	color: rgba(0, 0, 0) !important;
	font-weight: 600 !important;
}
.v-tab--disabled {
	opacity: 0.7 !important;
}
.v-sheet.v-card {
     border-radius: unset!important;
}
.v-sheet.v-card:not(.v-sheet--outlined) {
    box-shadow: unset!important;
}
.v-slide-group:not(.v-slide-group--has-affixes)>.v-slide-group__next, .v-slide-group:not(.v-slide-group--has-affixes)>.v-slide-group__prev {
    display: none!important;
}
</style>

