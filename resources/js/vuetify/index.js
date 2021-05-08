import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

import colors from 'vuetify/lib/util/colors'

Vue.use(Vuetify)

export default new Vuetify({
    icons: {
        iconfont: 'mdi', // default - only for display purposes
    },
    theme: {
        themes: {
            light: {
                primary: '#0db6f0',
                secondary: '#00f1ff',
                accent: colors.lightBlue.base,
                error: '#e86161',
                info: '#54a8ea',
                success: '#78da9b',
                warning: '#FFC107',
            },
        },
    },
})

// header bar #abffff

//btn #71c3c3