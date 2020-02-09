// Authenticated
import ListOfUsers from '~/components/users/ListOfUsersComponent';
import UserCreate from '~/components/users/UserCreateComponent';
import UserEdit from '~/components/users/UserEditComponent';

// Middleware
import verifiedPhone from '~/routes/middleware/ensurePhoneIsVerified'
import requireAuth from '~/routes/middleware/requireAuthCheck'

const index = [
    {
        path: '/users', component: ListOfUsers,
        name: 'ListOfUsers',
        meta: {
            requireAuth: true,
            title: 'Users - App',
            middleware: [requireAuth, verifiedPhone],
            metaTags: [
                {
                    name: 'description',
                    content: 'The List Of Users page of our app.'
                },
                {
                    property: 'og:description',
                    content: 'The List Of Users page of our app.'
                }
            ]
        }
    },
    {
        path: '/users/create',
        name: 'UserCreate',
        component: UserCreate,
        meta: {
            requireAuth: true,
            title: 'User Create - App',
            middleware: [requireAuth, verifiedPhone],
            metaTags: [
                {
                    name: 'description',
                    content: 'The User Create page of our app.'
                },
                {
                    property: 'og:description',
                    content: 'The User Create page of our app.'
                }
            ]
        },
    },
];

export default index;
