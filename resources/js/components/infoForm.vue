<template>
	<div class="ma-2 pa-2">
		<v-form ref="form" v-model="valid" lazy-validation>
			<v-text-field
				v-model="name"
				:counter="10"
				:rules="nameRules"
				label="Name *"
				required
			></v-text-field>

			<v-text-field
				v-model="phone"
				label="Phone *"
				required
			></v-text-field>

			<v-text-field
				v-model="email"
				:rules="emailRules"
				label="E-mail *"
				required
			></v-text-field>

			<v-text-field v-model="booking_note" label="Note (optional)"></v-text-field>

			<v-btn
				:disabled="!valid"
				color="success"
				class="mr-4"
				@click="validate"
			>
				Book
			</v-btn>
		</v-form>
	</div>
</template>
<script>
import { bus } from "../app";
export default {
	data: () => ({
		phone: "",
		valid: true,
		booking_note: "",
		name: "",
		nameRules: [(v) => !!v || "Name is required"],
		email: "",
		emailRules: [
			(v) => !!v || "E-mail is required",
			(v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
		],
	}),

	methods: {
		validate() {
			let details = {
				products: this.$root.$children[0].selected_products,
				date_time: this.$root.$children[0].selected_date_time,
				minutes_total: this.$root.$children[0].minutes_total,
				email: this.email,
				name: this.name,
				phone: this.phone,
				booking_note: this.booking_note,
			};
			axios.post("api/bookings", details).then((response) => {
				bus.$emit("finished");
			});
		},
	},
};
</script>
