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
					{{ date_start }}
				</div>
			</v-card-title>

			<v-card-text>
				<div class="mt-2 mb-2">
					<v-label class="disabled">Available times</v-label>
				</div>

                <v-btn v-for="time in available_times" :key="time" elevation="2" @click="setDateTime(time)">{{ hourToString(time) }}</v-btn>

				<v-alert
					class="mt-2"
					v-if="intercept_alert"
					color="red"
					dense
					outlined
					type="error"
					>{{ intercept_alert_message }}</v-alert
				>

			</v-card-text>
			<v-card-actions class="justify-end">
				<v-btn @click="selected_open = false" color="error"
					>Close</v-btn
				>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>
<script>
import { bus } from "../app";
import { Datetime } from "vue-datetime";
import moment from "moment";
export default {
	props: ["startDate", 'events', "blockedDates"],
	components: {
		datetime: Datetime,
	},
	data: function () {
		return {
			selected_open: false,
			valid: true,
			date_start: "",
			date_end: "",
			intercept_alert: false,
			intercept_alert_message: "",
            available_times : [],
            times: [],
            date_start_date: ""
		};
	},
	methods: {
	    hourToString(time) {
            return ('0' + time).slice(-2) + ":00"
        },
		setDateTime(time) {
			let dateTime = moment(this.date_start)
				.set("hour", time)
				.set("minute", 0)
				.set("second", 0);
			bus.$emit("selected_date_time", dateTime);
			setTimeout(() => {
                this.selected_open = false;
                bus.$emit("move_next");
			}, 500);
		},
        intercept(start1, end1, start2, end2) {
            return (
                Math.max(
                    0,
                    Math.min(end2, end1) - Math.max(start1, start2) + 1
                ) > 0
            );
        },
        arraySearch(arr,val) {
            for (var i=0; i<arr.length; i++)
                if (arr[i] === val)
                    return i;
            return false;
        },
        checkTimes() {
	        let alltimes = [9, 13, 16];
	        this.times = [];
	        let length = 0;
            this.blockedDates.forEach((element) => {
                if(element.date === this.date_start_date) {
                    let blocked_times = element.times.split(',')
                    blocked_times.forEach((time) => {
                        this.times.push(parseInt(time.trim()));
                    })
                }
            })
            for (const event of this.events){
                let start = moment(event.start).format('YYYY-MM-DD');
		        if(start === this.date_start_date) {
		            let starthour = new Date(event.start).getHours();
                    this.times.push(starthour);
                    length = moment(event.end).diff(moment(event.start), 'hours');
                    alltimes.forEach( (element, index) => {
                        if((starthour + length) > alltimes[index]) {
                            this.times.push(alltimes[index]);
                        }
                    })
                }
            }
            this.available_times = alltimes.filter(x => !this.times.includes(x));
        },
	},
	watch: {
		startDate: function (newVal) {
			this.date_start = moment(newVal).format("YYYY-MM-DD HH:mm:ss");
            this.date_start_date = moment(newVal).format("YYYY-MM-DD");
            this.checkTimes();
		},
	},
	mounted: function () {
		bus.$on("open_event_form", () => {
			this.selected_open = true;
		});
	}
};
</script>
