// Register
import Register from '~/components/auth/Register';
import Login from '~/components/auth/Login';
import Error404 from '~/components/errors/404';
import VerifiedForm from '~/components/auth/VerifyPhone';

// Authenticated
import Dashboard from '~/components/DashboardComponent';
import Profile from '~/components/ProfileComponent';
import Blank from '~/components/BlankComponent';


// Middleware
import requireAuth from '~/routes/middleware/requireAuthCheck'

// Import Module Routes
import userRoutes from './userRoutes';
import badgeRoutes from './badgeRoutes';


const baseRoutes = [{
        path: '/login',
        component: Login,
        name: 'Login',
        meta: {
            title: 'Login - App',
            guest: true,
        }
    },
    {
        path: '/register',
        component: Register,
        name: 'Register',
        meta: {
            title: 'Register - App',
            guest: true,
        }
    },
    {
        path: '/verify',
        component: VerifiedForm,
        name: 'VerifiedForm',
        meta: {
            guest: true,
            title: 'Verify Phone - App',
        }
    },
    {
        path: '/blank',
        component: Blank,
        name: 'Blank',
        meta: {
            middleware: [requireAuth],
            title: 'Blank - App',
            guest: true
        }
    },
    {
        path: '*',
        component: Error404,
        name: '404',
        meta: {
            middleware: [requireAuth],
            title: 'Not Found - App'
        }
    },
    {
        path: '/',
        component: Dashboard,
        name: 'Dashboard',
        meta: {
            middleware: [requireAuth],
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
            middleware: [requireAuth],
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
