<template>
	<v-row justify="center">
		<v-dialog v-model="dialog" persistent max-width="600px">
			<v-card>
				<v-card-title>
					<span class="headline">Add Item</span>
				</v-card-title>
				<v-card-text>
					<v-container>
						<v-row>
							<v-col cols="12">
								<v-text-field
									label="Name*"
									v-model="item.name"
									required
								></v-text-field>
							</v-col>
							<v-col cols="12">
								<v-text-field
									label="Price*"
									v-model="item.price"
									required
								></v-text-field>
							</v-col>
							<v-col cols="12">
								<v-text-field
									label="Time in minutes*"
									v-model="item.minutes"
									required
								></v-text-field>
							</v-col>
							<v-col cols="12">
								<v-checkbox
									v-model="item.display_quantity"
									:label="`Display Quantity?`"
								></v-checkbox>
							</v-col>
                            <v-col cols="12">
                                <v-text-field
                                    required
                                    v-model="item.sort_order"
                                    label="Sort Order starting from 1 and sorting desc the higher the number"
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
import { bus } from "../../app";
export default {
	data: () => ({
		dialog: false,
		item: {
			name: "",
			price: 0,
			minutes: 0,
			display_quantity: null,
            sort_order: null,
		},
	}),
	methods: {
		saveItem() {
			axios
				.patch("api/products/" + this.item.id, this.item)
				.then((response) => {
					if (response.data === "success") {
						bus.$emit("item_created");
						this.dialog = false;
					}
				});
		},
	},
	created: function () {
		bus.$on("open_edit_item_form", (event) => {
			this.dialog = true;
			this.item = event;
		});
	},
};
</script>
