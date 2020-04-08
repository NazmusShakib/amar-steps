import localStorage from '~/services/localStorage';

export default function requireAuthCheck({next}) {
    const token = localStorage.get('token');
    const auth = localStorage.get('auth');

    if (auth && auth.phone_verified_at === null) {
        return next({name: 'VerifiedForm'});
    }

    if (!token) {
        next({name: 'Login'});
        return false;
    }

    return next()
}
