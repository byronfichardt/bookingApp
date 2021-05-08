<template>
	<v-dialog v-model="selected_open" width="500">
		<v-card>
			<v-card-title class="headline grey lighten-2">
				<div style="width: 50%">Booking</div>

				<div
					class="float-right"
					style="
						width: 50%;
						font-family: SFMono-Regular;
						font-weight: 400;
						font-size: 0.875rem;
						text-align: right;
					"
				>
					{{ date_start }}
				</div>
			</v-card-title>

			<v-card-text>
				<div class="mt-2 mb-2">
					<v-label class="disabled">Available times</v-label>
				</div>
				<v-btn elevation="2" @click="setDateTime(9)">09:00</v-btn>
				<v-btn elevation="2" @click="setDateTime(13)">13:00</v-btn>
				<v-btn elevation="2" @click="setDateTime(16)">16:00</v-btn>
				<v-alert
					class="mt-2"
					v-if="intercept_alert"
					color="red"
					dense
					outlined
					type="error"
					>{{ intercept_alert_message }}</v-alert
				>
			</v-card-text>
			<v-card-actions class="justify-end">
				<v-btn @click="selected_open = false" color="error"
					>Close</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>
<script>
import { bus } from "../app";
import { Datetime } from "vue-datetime";
import moment from "moment";
export default {
	props: ["startDate"],
	components: {
		datetime: Datetime,
	},
	data: function () {
		return {
			selected_open: false,
			valid: true,
			date_start: "",
			date_end: "",
			intercept_alert: false,
			intercept_alert_message: "",
		};
	},
	methods: {
		setDateTime(time) {
			this.intercept_alert = false;
			let dateTime = moment(this.date_start)
				.set("hour", time)
				.set("minute", 0)
				.set("second", 0);
			bus.$emit("selected_date_time", dateTime);
			setTimeout(() => {
				if (this.intercept_alert == false) {
					this.selected_open = false;
					bus.$emit("move_next");
				}
			}, 1000);
		},
	},
	watch: {
		startDate: function (newVal) {
			this.date_start = moment(newVal).format("YYYY-MM-DD HH:mm:ss");
		},
	},
	mounted: function () {
		bus.$on("open_event_form", () => {
			this.selected_open = true;
		});
	},
	created: function () {
		bus.$on("intercept_alert", (event) => {
			this.intercept_alert = true;
			this.intercept_alert_message =
				"Unfortunately the booking will not fit in this time slot please select another";
		});
	},
};
</script>
