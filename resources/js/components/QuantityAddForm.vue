<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Enter Quantity?</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    label="Quantity*"
                                    v-model="item.quantity"
                                    required
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="close">
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
import { bus } from "../app";
export default {
    data: () => ({
        dialog: false,
        item: {
            quantity: 0
        },
    }),
    methods: {
        saveItem() {
            bus.$emit('quantity_form_saved', this.item)
            this.dialog = false;
        },
        close() {
            bus.$emit('closed_not_saved', this.item)
            this.dialog = false;
        },
    },
    created: function () {
        bus.$on("show_quantity_form", (event) => {
            this.item = event
            this.dialog = true;
        });
    },
};
</script>
