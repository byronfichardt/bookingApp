<template>
	<v-dialog v-model="opened_form" width="500">
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

                <v-btn v-for="time in available_times" :key="time" elevation="2" @click="setDateTime(time)" :class="timeClass(time)">{{ hourToString(time) }}</v-btn>

			</v-card-text>
			<v-card-actions class="justify-end">
				<v-btn @click="opened_form = false" color="error"
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
import Swal from "sweetalert2";
export default {
	props: ["startDate", 'events', "blockedDates"],
	components: {
		datetime: Datetime,
	},
	data: function () {
		return {
			opened_form: false,
			valid: true,
			date_start: "",
			date_end: "",
            available_times : [],
            times: [],
            date_start_date: ""
		};
	},
	methods: {
	    timeClass(time) {
	        return "time-button-" + time;
        },
	    hourToString(time) {
            return ('0' + time).slice(-2) + ":00"
        },
		setDateTime(time) {
			let dateTime = moment(this.date_start)
				.set("hour", time)
				.set("minute", 0)
				.set("second", 0);

			bus.$emit("selected_date_time", dateTime);
			setTimeout(() => {
                this.opened_form = false;
                bus.$emit("move_next");
			}, 500);
		},
	},
	watch: {
		startDate: function (newVal) {
			this.date_start = moment(newVal).format("YYYY-MM-DD HH:mm:ss");
            this.date_start_date = moment(newVal).format("YYYY-MM-DD");
		},
	},
	mounted: function () {
		bus.$on("open_event_form", (data) => {
			this.opened_form = true;
			this.available_times = data
		});
	}
};
</script>
