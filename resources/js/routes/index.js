import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);
import store from '~/store'
import localStorage from '../services/localStorage';

import middlewarePipeline from './middlewarePipeline'

import MenuRoutes from './menuRoutes';


const router = new VueRouter({
    mode: 'history',
    // base: process.env.MIX_API_URL,
    routes: [
        ...MenuRoutes
    ], // you may use only 'routes' short for routes: routes
    linkExactActiveClass: 'nav-item active'
});

export default router


// This callback runs before every route change, including on page load.
router.beforeEach((to, from, next) => {
    NProgress.start();
    NProgress.set(0.1);

    // This goes through the matched routes from last to first, finding the closest route with a title.
    // eg. if we have /some/deep/nested/route and /some, /deep, and /nested have titles, nested's will be chosen.
    const nearestWithTitle = to.matched.slice().reverse().find(r => r.meta && r.meta.title);

    // Find the nearest route element with meta tags.
    const nearestWithMeta = to.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);
    const previousNearestWithMeta = from.matched.slice().reverse().find(r => r.meta && r.meta.metaTags);

    // If a route with a title was found, set the document (page) title to that value.
    if(nearestWithTitle) document.title = nearestWithTitle.meta.title;

    // Remove any stale meta tags from the document using the key attribute we set below.
    Array.from(document.querySelectorAll('[data-vue-router-controlled]')).map(el => el.parentNode.removeChild(el));

    if(nearestWithMeta) {
        // Turn the meta tag definitions into actual elements in the head.
        nearestWithMeta.meta.metaTags.map(tagDef => {
            const tag = document.createElement('meta');

            Object.keys(tagDef).forEach(key => {
                tag.setAttribute(key, tagDef[key]);
            });

            // We use this to track which meta tags we create, so we don't interfere with other ones.
            tag.setAttribute('data-vue-router-controlled', '');

            return tag;
        })
        // Add the meta tags to the document head.
            .forEach(tag => document.head.appendChild(tag));
    }

    /*const token = localStorage.get('token');
    if (to.meta.requireAuth) {
        let user = localStorage.get('auth');
        const token = localStorage.get('token');
        if (!token) {
            next({ path: '/login' });
            return false;
        } else {
            next();
        }
    }*/

    if (!to.meta.middleware) {
        return next()
    }
    const middleware = to.meta.middleware;

    const context = {
        to,
        from,
        next,
        store
    };

    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    })
});

router.afterEach((to, from) => {
    setTimeout(() => NProgress.done(), 500);
});

router.beforeResolve((to, from, next) => {
    // If this isn't an initial page load.
    if (to.name) {
        // Start the route progress bar.
        NProgress.start()
    }
    next()
});

router.afterEach((to, from) => {
    // Complete the animation of the route progress bar.
    NProgress.done()
});
