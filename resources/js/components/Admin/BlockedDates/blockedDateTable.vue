<template>
    <div style="width: 100%">
        <v-card
            class="d-flex flex-row-reverse"
            color="none"
            flat
            tile
            style="padding-right: 15px"
        >
            <v-btn
                color="accent"
                elevation="2"
                outlined
                small
                @click="openAddItemForm"
            >Add item</v-btn
            >
        </v-card>
        <v-simple-table>
            <template v-slot:default>
                <thead>
                <tr>
                    <th class="text-left">Date</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in blockedDates" :key="item.id">
                    <td>{{ item.date }}</td>
                    <td>
                        <v-btn
                            color="red"
                            @click="deleteItem(item)"
                        >
                            Delete
                        </v-btn>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
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
