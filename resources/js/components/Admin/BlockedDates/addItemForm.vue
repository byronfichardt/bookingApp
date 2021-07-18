<template>
	<v-row justify="center">
		<v-dialog v-model="dialog" persistent max-width="600px">
			<v-card>
				<v-card-title>
					<span class="headline">Add Date</span>
				</v-card-title>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-text-field
									label="Date YYYY-MM-DD"
									v-model="item.date"
									required
								></v-text-field>
                                <v-text-field
                                    label="times (9, 13, 16)"
                                    v-model="item.times"
                                    required
                                ></v-text-field>
							</v-col>
						</v-row>
					</v-container>
					<small>*indicates required field</small>
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
import { bus } from "../../../app";
export default {
	data: () => ({
		dialog: false,
		item: {
			date: null,
            times: ""
		},
	}),
	methods: {
		saveItem() {
			axios.post("api/blocked", this.item).then((response) => {
				if (response.data.status === "success") {
					bus.$emit("blocked_date_created");
					this.dialog = false;
				}
			});
		},
	},
	created: function () {
		bus.$on("open_add_blocked_date_form", (event) => {
			this.dialog = true;
		});
	},
};
</script>
