<template>
	<div>
		<v-sheet tile height="55" class="d-flex">
			<v-btn icon class="ma-2" @click="$refs.calendar.prev()">
				<v-icon>mdi-chevron-left</v-icon>
			</v-btn>
			<v-banner
				elevation="1"
				single-line
				style="
					display: block;
					width: 100%;
					text-align: center;
					box-shadow: unset !important;
					padding-top: 10px;
				"
				>{{ selected_date ? formated_date : today }}</v-banner
			>
			<v-spacer></v-spacer>
			<v-btn icon class="ma-2" @click="$refs.calendar.next()">
				<v-icon>mdi-chevron-right</v-icon>
			</v-btn>
		</v-sheet>
		<v-sheet height="500">
			<v-calendar
				ref="calendar"
				v-model="theDate"
				:weekdays="weekday"
				:type="type"
				:events="eventsCal"
				@change="getEvents"
				color="primary"
				@click:day="clickTime"
				@click:date="clickTime"
			>
			</v-calendar>
		</v-sheet>
		<eventForm :startDate="selected_date" :events="events" :blockedDates="blockedDates"></eventForm>
	</div>
</template>
<script>
import moment from "moment";
import { bus } from "../app";
import eventForm from "../components/eventForm.vue";
import Swal from "sweetalert2";
export default {
	components: { eventForm },
	data: () => ({
		today: "",
		theDate: "",
		start: moment().add(2, "days").format("Y-MM-DD"),
		selected_date: "",
		formated_date: "",
        blockedDates: [],
		type: "month",
		mode: "stack",
		weekday: [0, 1, 2, 3, 4, 5, 6],
		eventsCal: [],
		events: [],
	}),
	methods: {
		showEvent() {
			bus.$emit("open_event_form");
		},
		viewDay(event) {
			bus.$emit("date_selected", event);
			bus.$emit("move_next");
		},
		clickTime(event) {
		    this.getAvailableTimes(event);
		},
        getAvailableTimes(event) {
            axios.get("api/bookings/availableTimes?date=" + event.date).then((response) => {
                if(response.data.length === 0 ) {
                    Swal.fire("Day fully booked");
                }else {
                    let tomorrowsDate = moment(this.today).add(1, "days");
                    if (moment(event.date).isBetween(moment(this.today), tomorrowsDate)) {
                        Swal.fire("Day fully booked");
                    } else if (moment(event.date).isAfter(tomorrowsDate)) {
                        this.showEvent();
                        this.selected_date = new Date(`${event.date} ${event.time}`);
                    }
                }
            });
        },
		getEvents() {
			axios.get("api/bookings").then((response) => {
				this.events = [];
				response.data["data"].forEach((event) => {
					this.events.push({
						start: event.start.slice(0, -3),
						end: event.end.slice(0, -3),
						name: "Booked",
					});
				});
				bus.$emit("all_events", this.events);
			});
		},
	},
	mounted: function () {
		this.today = moment().format("Y-MM-DD HH:mm:ss");
	},
	watch: {
		selected_date: function (newVal, oldVal) {
			this.formated_date = moment(newVal).format("Y-MM-DD HH:mm:ss");
		},
	},
};
</script>
<style scoped>
div::v-deep .v-banner__wrapper {
	border-bottom: unset !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day:hover {
	background-color: dimgray;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day.v-past {
	background-color: #f7f7f7 !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day.v-present {
	background-color: #f7f7f7 !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day.v-future:hover {
	cursor: pointer !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__head
	.v-calendar-weekly__head-weekday.v-future.v-outside {
	background-color: #fff !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__head
	.v-calendar-weekly__head-weekday.v-present.v-outside {
	background-color: #fff !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day
	.v-calendar-weekly__day-label {
	cursor: unset !important;
}
div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day
	.v-calendar-weekly__day-label
	> button {
	cursor: unset !important;
}

div::v-deep
	.v-calendar-weekly
	.v-calendar-weekly__week
	.v-calendar-weekly__day
	.v-calendar-weekly__day-label
	> button:hover:before {
	opacity: 0 !important;
}
</style>
