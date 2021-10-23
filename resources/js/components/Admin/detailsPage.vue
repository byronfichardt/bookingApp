<template>
	<div style="width: 100%; margin-top: 30px">
            <v-row>
                <v-col cols="12">
                    <label>About</label>
                    <v-textarea
                        solo
                        name="input-7-4"
                        label="About"
                        v-model="details.about"
                    ></v-textarea>
                    <label>Important</label>
                    <v-textarea
                        solo
                        name="input-7-4"
                        label="Important"
                        v-model="details.important"
                    ></v-textarea>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-btn
                        color="green"
                        class="mr-4 book-button"
                        @click="update"
                    >
                        Update
                    </v-btn>
                </v-col>
            </v-row>
	</div>
</template>
<script>
import Swal from "sweetalert2";

export default {
	data() {
		return {
			details: {
			    about: null,
                important: null
            },
		};
	},
	methods: {
        getDetails() {
            axios.get("api/details").then((response) => {
                this.details.about = response.data.about
                this.details.important = response.data.important.replace(/;/g,'\n')
            });
        },
        update() {
            let data = {
                important: this.details.important.replace(/\n/g,';'),
                about: this.details.about,
            }
            axios.post('api/details', data).then((response) => {
                if(response.data === 'success') {
                    Swal.fire("updated");
                }
            });
        }
	},
	mounted: function () {
        this.getDetails()
	},
};
</script>
