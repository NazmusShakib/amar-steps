export default function ensurePhoneIsVerified ({ next, store }) {

    console.log('Middleware:: ensurePhoneIsVerified');
    let authCheck = store.getters.globalAuth;
    if(authCheck) {
        if(!authCheck.phone_verified_at){
            return next({
                name: 'VerifyPhone'
            })
        }
    }

    return next()
}
