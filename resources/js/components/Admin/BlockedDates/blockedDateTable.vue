<template>
    <div style="width: 100%">
        <v-card>
        <v-card-title>
            Blocked Dates
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
            <v-btn
                color="accent"
                elevation="2"
                outlined
                small
                @click="openAddItemForm"
            >Add item</v-btn>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="blockedDates"
            :search="search"
        >
            <template v-slot:item.actions="{ item }">
                <v-btn
                    color="red"
                    @click="deleteItem(item)"
                >
                    Delete
                </v-btn>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
        </v-data-table>
        </v-card>
        <add-item-form></add-item-form>
    </div>
</template>
<script>
import addItemForm from "./addItemForm.vue";
import {bus} from "../../../app";
export default {
    components: { addItemForm },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: 'Date',
                    align: 'left',
                    sortable: false,
                    value: 'date'
                },
                { text: 'Times', value: 'times' },
                { text: 'Delete', value: 'actions' },
            ],
            blockedDates: [],
        };
    },
    methods: {
        getBlockedDates() {
            axios.get("api/blocked").then((response) => {
                this.blockedDates = response.data.data;
            });
        },
        openAddItemForm() {
            bus.$emit("open_add_blocked_date_form");
        },
        deleteItem(item) {
            axios.delete("api/blocked/" + item.id ).then((response) => {
                if (response.data.status === "success") {
                    this.getBlockedDates();
                }
            });
        },
    },
    mounted: function () {
        this.getBlockedDates();
    },
    created: function () {
        bus.$on("blocked_date_created", (event) => {
            this.getBlockedDates();
        });
    }
};
</script>
