<template>
	<div style="width: 100%; margin-top: 30px" >
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
		<v-sheet height="800">
			<v-calendar
				ref="calendar"
				v-model="theDate"
				:weekdays="weekday"
				:type="type"
				:events="events"
				@change="getEvents"
				color="primary"
				@click:event="showEvent"
			>
			</v-calendar>
		</v-sheet>

		<event-info></event-info>
	</div>
</template>
<script>
import moment from "moment";
import { bus } from "../../app";
import eventForm from "../chooseTimeForm.vue";
import EventInfo from "./eventInfo.vue";
export default {
	components: { eventForm, EventInfo },
	data: () => ({
		today: "",
		theDate: "",
		start: moment().add(1, "days").format("Y-MM-DD"),
		selected_date: "",
		formated_date: "",
		type: "month",
		mode: "stack",
		weekday: [0, 1, 2, 3, 4, 5, 6],
        availableTimes: [],
		eventsCal: [],
		events: [],
	}),
	methods: {
		showEvent({ event }) {
			bus.$emit("open_event_info", event);
		},
		viewDay(event) {
			bus.$emit("date_selected", event);
			bus.$emit("move_next");
		},
		clickTime(event) {
			if (moment(event.date).isAfter(this.today)) {
				this.showEvent();
				this.selected_date = new Date(`${event.date} ${event.time}`);
			}
		},
        getBlockedDates(){
            axios.get("api/blocked").then((response) => {
                this.blockedDates = response.data.data.forEach((event) => {
                    if(event.times) {
                        event.times.split(',').forEach((item) => {
                            let eventDate = moment(event.date).hour(item);
                            this.events.push({
                                id: 0,
                                start: eventDate.format("YYYY-MM-DD HH:mm:SS"),
                                products: [],
                                user: 'karin',
                                end: eventDate.add(3, 'h').format("YYYY-MM-DD HH:mm:SS"),
                                name: 'Karin Time',
                                note: '',
                                path: '',
                                color: 'green',
                            });
                        })
                    }else {
                        let eventDate = moment(event.date);
                        this.availableTimes.forEach((item) => {
                            let thisEventDate = eventDate.hour(item);
                            this.events.push({
                                id: 0,
                                start: thisEventDate.format("YYYY-MM-DD HH:mm:SS"),
                                products: [],
                                user: 'karin',
                                end: thisEventDate.add(3, 'h').format("YYYY-MM-DD HH:mm:SS"),
                                name: 'Karin Time',
                                note: '',
                                path: '',
                                color: 'green',
                            });
                        })
                    }
                });
            });
        },
        getAvailableTimes(){
            axios.get("api/bookings/availableTimes?date=2040-01-01").then((response) => {
                this.availableTimes = response.data
            })
        },
		getEvents() {
			axios.get("api/bookings").then((response) => {
				this.events = [];
				response.data["data"].forEach((event) => {
					this.events.push({
                        id: event.id,
						start: event.start.slice(0, -3),
						products: event.products,
						user: event.user,
						end: event.end.slice(0, -3),
						name: event.user ? event.user.name : event.name,
                        note: event.note,
                        path: event.path,
					});
				});
				this.getAvailableTimes()
				this.getBlockedDates()
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
</style>
