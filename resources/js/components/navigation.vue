<template>
    <div style="min-height: 800px; width: 0px">
        <v-app-bar-nav-icon @click.stop="drawer = !drawer" style="z-index: 1000;"></v-app-bar-nav-icon>

        <v-navigation-drawer
            v-model="drawer"
            absolute
            top
            temporary
            style="z-index: 2000;"
        >
            <v-list
                nav
                dense
            >
                <v-list-item-group
                    v-model="group"
                    active-class="deep-purple--text text--accent-4"
                >
                    <v-list-item>
                        <router-link to="calender">Calender</router-link>
                    </v-list-item>

                    <v-list-item>
                        <router-link to="products">Products</router-link>
                    </v-list-item>

                    <v-list-item>
                        <router-link to="approvals">Approvals</router-link>
                    </v-list-item>
                    <v-list-item>
                        <router-link to="details">Details</router-link>
                    </v-list-item>

                </v-list-item-group>
            </v-list>
        </v-navigation-drawer>
    </div>
</template>
<script>
export default {
    data: () => ({
        drawer: false,
        group: null,
    }),

    watch: {
        group () {
            this.drawer = false
        },
    },
}
</script>

