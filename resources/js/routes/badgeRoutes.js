// Authenticated
import BadgeList from '~/components/badge/BadgeListComponent';

const index = [{
    path: '/badges',
    component: BadgeList,
    name: 'BadgeList',
    meta: {
        requireAuth: true,
        title: 'Badge List - App',
        metaTags: [{
            name: 'description',
            content: 'The Badge List page of our app.'
        }, {
            property: 'og:description',
            content: 'The Badge List page of our app.'
        }]
    }
}];

export default index;
