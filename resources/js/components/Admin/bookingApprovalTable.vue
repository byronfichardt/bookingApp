<template>
	<div style="width: 100%">
		<v-simple-table>
			<template v-slot:default>
				<thead>
					<tr>
						<th class="text-left">Name</th>
                        <th class="text-left">Notes</th>
						<th class="text-left">Date</th>
						<th class="text-left">Time needed</th>
                        <th class="text-left">Appointment time</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="item in bookings" :key="item.id">
						<td>{{ item.user.name }}</td>
                        <td>{{ item.name }}</td>
						<td>{{ getBookingDate(item) }}</td>
                        <td>{{ getBookingTimeNeeded(item) }} Hours</td>
                        <td>{{ getBookingStartTime(item) }}</td>
						<td>
                            <v-btn
                                color="blue"
                                @click="changeTime(item)"
                            >
                                Change Start Time
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
                                Cancel No Email
                            </v-btn>
						</td>
					</tr>
				</tbody>
			</template>
		</v-simple-table>
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
			bookings: null,
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
            return (diffMs / 60000) / 60;
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
