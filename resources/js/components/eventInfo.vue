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
					Event
				</div>
			</v-card-title>

			<v-card-text>
				<pre>{{ event }}</pre>
                <h5>Total price: {{ eventPrice() }} Dkk</h5>
                <h5>Total Time: {{ eventTime() }} minutes</h5>
			</v-card-text>
			<v-card-actions class="justify-end">
				<v-btn @click="selected_open = false" color="error"
					>Close</v-btn
				>
                <v-btn @click="cancelEvent()" color="error"
                >Cancel</v-btn
                >
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>
<script>
import { bus } from "../app";
import Swal from "sweetalert2";
export default {
	data: function () {
		return {
			selected_open: false,
			event: null,
		};
	},
	methods: {
	    cancelEvent() {
            axios.get("api/bookings/" + this.event.id + "/cancel").then((response) => {
                if(response.data.status === 'success') {
                    Swal.fire("Canceled");
                    this.selected_open = false
                }
            });
        },
        eventPrice() {
	        let sum = 0
	        this.event.products.forEach( (product) => {
                sum += product.price * product.quantity;
            })
            return sum;
        },
        eventTime() {
            let sum = 0
            this.event.products.forEach( (product) => {
                sum += product.minutes * product.quantity;
            })
            return sum;
        }
    },
	mounted: function () {
		bus.$on("open_event_info", (event) => {
			this.selected_open = true;
			this.event = event;
		});
	},
};
</script>
