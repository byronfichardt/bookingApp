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
                                    readonly
								></v-text-field>

                                <v-btn v-for="time in item.times" :key="time" @click="blockTime(item, time)" elevation="2" :class="timeClass(time)">{{ hourToString(time) }}</v-btn>
							</v-col>
						</v-row>
                        <v-row>
                            <v-col cols="12">
                                <v-btn elevation="2" @click="blockAvailableTimes(item)">All available</v-btn>
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
        date:'',
		item: {
			date: null,
            times: [],
            alltimes: []
		},
	}),
	methods: {
        timeClass(time) {
            return "time-button-" + time;
        },
        hourToString(time) {
            return ('0' + time).slice(-2) + ":00"
        },
        blockTime(item, time){
            let blockItem = {
                date: item.date,
                times: [time]
            }
            this.saveItem(blockItem)
        },
        blockAvailableTimes(item){
            let blockItem = {
                date: item.date,
                times: item.times
            }
            this.saveItem(blockItem)
        },
		saveItem(blockItem) {
			axios.post("api/blocked", blockItem).then((response) => {
				if (response.data.status === "success") {
					bus.$emit("blocked_date_created");
					this.dialog = false;
				}
			});
		},
	},
	created: function () {
		bus.$on("open_add_blocked_date_form", (event) => {
		    this.item.date = event.date
            this.item.times = event.times
            this.item.alltimes = event.alltimes
			this.dialog = true;
		});
	},
};
</script>
