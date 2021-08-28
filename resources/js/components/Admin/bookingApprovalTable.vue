<template>
	<div style="width: 100%; margin-top: 30px">
        <v-data-table
            :headers="headers"
            :items="bookings"
        >
            <template v-slot:item.actions="{ item }">
                <v-btn
                    color="blue"
                    @click="changeTime(item)"
                >
                    Change
                </v-btn>
                <v-btn
                    color="green"
                    @click="approveItem(item)"
                >
                    Approve
                </v-btn>
                <v-btn
                    color="red"
                    @click="cancelItem(item)"
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="red"
                    @click="cancelItem(item, 0)"
                >
                    No Email
                </v-btn>
            </template>
            <template v-slot:item.date="{ item }">
                {{ getBookingDate(item) }}
            </template>
            <template v-slot:item.time_needed="{ item }">
                {{ getBookingTimeNeeded(item) }} Hours
            </template>
            <template v-slot:item.start_time="{ item }">
                {{ getBookingStartTime(item) }}
            </template>
        </v-data-table>
        <change-time-form></change-time-form>
	</div>
</template>
<script>
import { bus } from "../../app";
import addItemForm from "./addItemForm.vue";
import editItemForm from "./editItemForm.vue";
import Swal from "sweetalert2";
import ChangeTimeForm from "./ChangeTimeForm";
export default {
	components: {ChangeTimeForm, addItemForm, editItemForm },
	data() {
		return {
			bookings: [],
            headers: [
                {
                    text: 'Name',
                    align: 'left',
                    value: 'user.name'
                },
                { text: 'Notes', value: 'name' },
                { text: 'Booking Date', value: 'date' },
                { text: 'Time Needed', value: 'time_needed' },
                { text: 'Start time', value: 'start_time' },
                { text: 'Actions', value: 'actions' },
            ],
		};
	},
	methods: {
	    getBookingDate(booking) {
	        return booking.start.substring(0,10);
        },
        getBookingTimeNeeded(booking) {
            const start = new Date(booking.start);
            const endDate = new Date(booking.end);
            const diffMs = endDate - start;
            return ((diffMs / 60000) / 60).toFixed(2);
        },
        getBookingStartTime(booking) {
	        let startpos = booking.start.length-9;
            return booking.start.substring(startpos,25);
        },
        getPendingBookings() {
			axios.get("api/bookings/pending").then((response) => {
				this.bookings = response.data.data;
			});
		},
		approveItem(item) {
			axios.get("api/bookings/" + item.id + "/approve").then((response) => {
			    console.log(response);
				if (response.data.status === "success") {
					this.getPendingBookings();
				}
				if (response.data.status === "exists") {
                    Swal.fire("A booking is already active for this date and time.");
                }
			});
		},
        cancelItem(item, sendEmail = 1) {
            axios.get("api/bookings/" + item.id + "/cancel?sendEmail=" + sendEmail).then((response) => {
                console.log(response);
                if (response.data.status === "success") {
                    this.getPendingBookings();
                }
            });
        },
        changeTime(item) {
            bus.$emit('open_change_time_form', item)
        },
	},
	mounted: function () {
		this.getPendingBookings();
        bus.$on("time_saved", (event) => {
            this.getPendingBookings();
        });
	},
};
</script>
