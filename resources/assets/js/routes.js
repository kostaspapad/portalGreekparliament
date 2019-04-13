import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./views/Home.vue')
    },
    {
        path: '/about',
        component: require('./views/About.vue')
    },
    {
        path: '/login',
        component: require('./views/Login.vue')
    },
    {
        path: '/register',
        component: require('./views/Register.vue')
    },
    // {
    //     path: '/dashboard',
    //     component: require('./views/Dashboard.vue')
    // },
    {
        path: '/conferences',
        component: require('./components/conferences.vue')
    },
    {
        path: '/conference/:conference_date/speeches',
        component: require('./components/conference.vue')
    },
    {
        path: '/speakers',
        component: require('./components/speakers.vue')
    },
    {
        path: '/speaker/:speaker_id',
        component: require('./components/speaker.vue')
    },
    {
        path: '/parties',
        component: require('./components/parties.vue')
    },
    {
        path: '/party/:party_name',
        component: require('./components/party.vue')
    },
    {
        path: '/speech/:speech_id',
        component: require('./components/speech.vue')
    },
    {
        path: '/news',
        component: require('./views/News.vue')
    },
    {
        path: '/contact',
        component: require('./views/Contact.vue')
    },
    {
        path: '/search',
        component: require('./components/search.vue')
    }
    // {
    //     path: '/donate',
    //     component: require('./views/Donate.vue')
    // },
    // {
    //     path: '/policy',
    //     component: require('./views/Policy.vue')
    // },
];

const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {                
        if (!auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });

            return;
        }
    }

    next();
})

export default router;