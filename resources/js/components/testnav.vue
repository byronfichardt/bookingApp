<template>
    <div>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>

        <v-navigation-drawer
            v-model="drawer"
            absolute
            top
            temporary
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
<style>
@media only screen and (max-width: 600px) {
    .v-application .align-start {

    }
}
</style>

