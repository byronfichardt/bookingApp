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
                @click:date="showAddBlockedDateForm"
			>
			</v-calendar>
		</v-sheet>

		<event-info></event-info>
        <add-item-form></add-item-form>
	</div>
</template>
<script>
import moment from "moment";
import { bus } from "../../app";
import eventForm from "../chooseTimeForm.vue";
import EventInfo from "./eventInfo.vue";
import AddItemForm from "./addBlockedItemForm";
export default {
	components: {AddItemForm, eventForm, EventInfo },
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
        availableTimesBlockOut: [],
		eventsCal: [],
		events: [],
	}),
	methods: {
        showAddBlockedDateForm(event) {
            this.getAvailableTimes(event.date)
        },
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
        getAvailableTimes(date = '2040-01-01'){
            axios.get("api/bookings/availableTimes?date=" + date).then((response) => {
                this.availableTimes = response.data
                if(date !== '2040-01-01'){
                    bus.$emit("available_times_fetched", {times:response.data, date: date});
                }else{
                    this.alltimes = response.data
                }
            })
        },
		getEvents() {
			axios.get("api/bookings").then((response) => {
				this.events = [];
				response.data["data"].forEach((event) => {
				    let color = 'blue';
				    let name = event.user ? event.user.name : event.name
				    if(event.note === 'BLOCKED') {
                        color = 'green';
                        name = 'Blocked';
                    }
					this.events.push({
                        id: event.id,
						start: event.start.slice(0, -3),
						products: event.products,
						user: event.user,
						end: event.end.slice(0, -3),
						name: name,
                        note: event.note,
                        path: event.path,
                        color: color
					});
				});
				this.getAvailableTimes()
				bus.$emit("all_events", this.events);
			});
		},
        openAddItemForm(event) {
	        event.alltimes = this.alltimes
            bus.$emit("open_add_blocked_date_form", event);
        },
	},
	mounted: function () {
		this.today = moment().format("Y-MM-DD HH:mm:ss");
	},
    created: function () {
        bus.$on("available_times_fetched", (event) => {
            this.openAddItemForm(event)
        });
        bus.$on("blocked_date_created", (event) => {
            this.getEvents();
        });
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
