// Register
import Register from '~/components/auth/Register';
import Login from '~/components/auth/Login';
import Error404 from '~/components/errors/404';

// Authenticated
import Dashboard from '~/components/DashboardComponent';
import Profile from '~/components/ProfileComponent';
import Blank from '~/components/BlankComponent';


// Import Module Routes
import userRoutes from './userRoutes';
import badgeRoutes from './badgeRoutes';


const baseRoutes = [{
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            title: 'Login Page - App',
            guest: true
        }
    },
    {
        path: '/register',
        component: Register,
        name: 'Register',
        meta: {
            title: 'Register Page - App',
            guest: true
        }
    },
    {
        path: '/blank',
        component: Blank,
        name: 'Blank',
        meta: {
            title: 'Blank Page - App',
            guest: true
        }
    },
    {
        path: '*',
        component: Error404,
        name: '404',
        meta: {
            title: 'Not Found - App'
        }
    },
    {
        path: '/dashboard',
        component: Dashboard,
        name: 'Dashboard',
        meta: {
            requireAuth: true,
            title: 'Dashboard - App',
            metaTags: [{
                    name: 'description',
                    content: 'The home page of our app.'
                },
                {
                    property: 'og:description',
                    content: 'The home page of our app.'
                }
            ]
        }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: Profile,
        meta: {
            requireAuth: true,
            title: 'Profile - App',
            metaTags: [{
                    name: 'description',
                    content: 'The profile page of our app.'
                },
                {
                    property: 'og:description',
                    content: 'The profile page of our app.'
                }
            ]
        },
    },
];

const routes = baseRoutes.concat(
    userRoutes, badgeRoutes
);

export default routes;
