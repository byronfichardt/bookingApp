<template>
	<div class="ma-2 pa-2">
		<v-form ref="form" v-model="valid" lazy-validation>
			<v-text-field
				v-model="name"
				:rules="nameRules"
				label="Name *"
                class="name-form-field"
				required
			></v-text-field>

			<v-text-field
				v-model="phone"
                :rules="phoneRules"
				label="Phone *"
                class="phone-form-field"
				required
			></v-text-field>

			<v-text-field
				v-model="email"
				:rules="emailRules"
                class="email-form-field"
				label="E-mail *"
				required
			></v-text-field>

            <v-file-input
                v-model="file"
                show-size
                accept="image/*"
                label="Inspiration picture. (optional)"
                @change="onFileChange()"
            ></v-file-input>

			<v-text-field v-model="booking_note" label="Note (optional)" :counter="255"></v-text-field>

			<v-btn
				color="green"
				class="mr-4 book-button"
				@click="validate"
			>
				Book
			</v-btn>
		</v-form>
	</div>
</template>
<script>
import { bus } from "../app";
import Swal from "sweetalert2";
export default {
	data: () => ({
		phone: "",
		valid: false,
		booking_note: "",
		name: "",
		nameRules: [(v) => !!v || "Name is required"],
		email: "",
        image: null,
        file: null,
		emailRules: [
			(v) => !!v || "E-mail is required",
			(v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
		],
        phoneRules: [
            (v) => !!v || "Phone is required"
        ]
	}),

	methods: {
        onFileChange() {
            const reader = new FileReader()
            reader.readAsDataURL(this.file)
            reader.onload = e => {
                this.image = e.target.result
            }
        },
		validate() {
            this.$refs.form.validate()

			let details = {
				products: this.$root.$children[0].selected_products,
				date_time: this.$root.$children[0].selected_date_time,
				minutes_total: this.$root.$children[0].minutes_total,
				email: this.email,
				name: this.name,
				phone: this.phone,
				booking_note: this.booking_note,
                image: this.image,
			};
			axios.post("api/bookings", details).then((response) => {
				bus.$emit("finished");
			});
		},
	},
};
</script>
