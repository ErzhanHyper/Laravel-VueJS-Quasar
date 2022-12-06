/**
 * This is where all the authorization login is stored
 */
import Authorization from './Authorization'

export default function UserHasPermissions(router) {
    /**
     * Before each route we will see if the current user is authorized
     * to access the given route
     */
    router.beforeEach((to, from, next) => {
        let authorized = false

        /**
         * Remember that access object in the routes? Yup this why we need it.
         *
         */

        if (to.meta !== undefined) {

            (async () => {
                authorized = await Authorization.authorize(
                    to.meta.requiresLogin,
                    to.meta.type,
                    to.meta.section
                )

                if (!authorized.authenticated && !authorized.access) {
                    // next('/login');
                }else if(authorized.authenticated && !authorized.access){
                    // next('/notAccess')
                }
                next()
            })()
        }

    })
}