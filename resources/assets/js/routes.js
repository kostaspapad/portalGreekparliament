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
        path: '/speaker/:speaker_name',
        component: require('./components/speaker.vue')
    },
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