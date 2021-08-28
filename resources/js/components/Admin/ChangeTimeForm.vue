<template>
	<v-row justify="center">
		<v-dialog v-model="dialog" persistent max-width="600px">
			<v-card>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-text-field
									label="New Time"
									v-model="item.start"
									required
								></v-text-field>
							</v-col>
						</v-row>
					</v-container>
				</v-card-text>
				<v-card-actions>
					<v-spacer></v-spacer>
					<v-btn color="blue darken-1" text @click="dialog = false">
						Close
					</v-btn>
					<v-btn color="blue darken-1" text @click="saveItem">
						Save
					</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-row>
</template>

<script>
import { bus } from "../../app";
export default {
	data: () => ({
		dialog: false,
		item: {
			start_time: null,
			id: null,
		},
	}),
	methods: {
		saveItem() {
            axios.patch("api/bookings/" + this.item.id, this.item).then((response) => {
                if (response.data.status === "success") {
                    bus.$emit('time_saved')
                    this.dialog = false
                }
            });
		},
	},
	created: function () {
		bus.$on("open_change_time_form", (event) => {
		    this.item = event;
			this.dialog = true;
		});
	},
};
</script>
