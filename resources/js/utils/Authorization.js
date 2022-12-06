import API from './api.js'

export default {
    /*
        access: {
            requiresLogin: true,
        },
     */

    buttonAccess(current_section, type) {
        let permission = false
        // let user = JSON.parse(window.localStorage.getItem('auth-user'))
        // for (let i = 0; i < user.permission.length; i++) {
        //     let section = user.permission[i].section
        //     let access = user.permission[i].access.indexOf(type) > -1
        //     if (current_section === section && access) {
        //         permission = true
        //     }
        // }

        return permission

    },


    async user() {
        let user = {}
        await API.post('user').then(response => {
            window.localStorage.setItem('auth-user', JSON.stringify(response.data.user[0]))
            window.localStorage.setItem('employee', JSON.stringify(response.data.employee[0]))
            user = response.data.user[0]
        })
        return user
    },

    logout() {
        API.post('/logout').then(() => {
            localStorage.removeItem('auth-user');
            localStorage.removeItem('token');
            localStorage.removeItem('employee');
            emitter.emit('loggedOut');
        })
    },

    async authorize(requiresLogin, type, section) {

        // this.user()

        let authenticated = false

        let user = JSON.parse(window.localStorage.getItem('auth-user')) || undefined
        let hasPermissionSection = false
        let token = window.localStorage.getItem('token') || undefined

        if (requiresLogin === true && (token === undefined && user === undefined)) {
            authenticated = false
        }
        if (requiresLogin === true && (token !== undefined && user !== undefined)) {
            authenticated = true
        }
        if (type && section) {
            await this.user().then(res => {
                let sections = res.permission
                for (let i = 0; i < sections.length; i++) {
                    if (sections[i].section === section) {
                        hasPermissionSection = sections[i].access.indexOf(type) > -1
                    }
                }
            }).catch(() => {
                authenticated = false
            })
        } else {
            hasPermissionSection = true
        }

        return {
            access: hasPermissionSection,
            authenticated: authenticated
        }
    }
}
