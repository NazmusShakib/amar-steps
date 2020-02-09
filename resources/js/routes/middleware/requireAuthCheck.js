import localStorage from '~/services/localStorage';

export default function requireAuthCheck({next, store}) {
    console.log('Middleware:: requireAuthCheck');
    const token = localStorage.get('token');
    if (!token) {
        console.log('Middleware:: aaaaaaaaa');
       return next({name: 'login'});
    }

    return next()
}
