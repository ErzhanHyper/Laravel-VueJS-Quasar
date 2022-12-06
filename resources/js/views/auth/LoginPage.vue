<template>


    <div class="flex justify-center content-center column " style="height:100vh;" id="loginForm">


        <div class="main-logo flex flex-center q-my-md" style="width: 400px;">
            <div class="logo flex items-center">
                <q-avatar>
                    <img src="../../../img/logo_zd.jpg">
                </q-avatar>
                <span class="logo-title q-ml-sm text-overline" style="font-size: 20px;">Жасыл Даму</span>
            </div>
        </div>

        <q-card flat bordered class="q-pa-sm" style="width: 400px;">

            <q-card-section>
                <div class="text-h6">Авторизация</div>
            </q-card-section>

            <q-card-section>

                <q-banner dense inline-actions class="text-white bg-red" v-if="errors.length > 0">
                    <span v-for="error in errors" :key="errors.indexOf(error)">
                        <span v-for="(item, index) in error" :key="index">
                            {{ item[0] }}
                            <br>
                        </span>
                    </span>
                </q-banner>

                <q-form class="q-gutter-md">
                    <q-input square clearable dense v-model="auth.username" type="text" label="Логин" @keydown.enter="login"
                             :rules="rules" name="username">
                        <template v-slot:prepend>
                            <q-icon name="person"/>
                        </template>
                    </q-input>
                    <q-input square clearable dense v-model="auth.password" :type="(!isLock) ? 'password' : 'text'"
                             autocomplete="new-password" label="Пароль" @keydown.enter="login">
                        <template v-slot:prepend>
                            <q-icon name="lock"/>
                        </template>
                        <template v-slot:append>
                            <q-btn flat>
                                <q-icon name="visibility" v-if="isLock" @click="isLock = false"/>
                                <q-icon name="visibility_off" v-if="!isLock" @click="isLock = true"/>
                            </q-btn>
                        </template>
                    </q-input>
<!--                    <div class="text-right"><a href="#">Забыли пароль?</a></div>-->
                </q-form>
            </q-card-section>

            <q-card-actions class="q-px-md">
                <q-btn unelevated color="light-green-7" size="16px" class="full-width" label="Войти" @click="login()"
                       :loading="loading"/>
            </q-card-actions>

            <q-card-section class="text-center q-pa-none">
                <p class="text-grey-6">Не зарегистрированы? Обратитесь в Департамент управления человеческими ресурсами
                    и документационного обеспечения</p>
            </q-card-section>

        </q-card>

    </div>

</template>

<script>
import Api from "../../utils/api";
import authorization from "../../utils/Authorization";

export default {

    data() {
        return {
            auth: {username: '', password: ''},
            user: {},
            errors: [],
            show1: false,
            loading: false,
            isLock: false,
            rules: [val => val.length <= 25 || 'Максимум 25 символов']
        }
    },

    methods: {
        login() {
            this.loading = true
            let credentials = this.auth
            this.errors = []
            Api.post('sanctum/login', credentials)
                .then(response => {
                    localStorage.setItem('token', response.data.token)
                    localStorage.setItem('auth-user', JSON.stringify(authorization.user()))
                    localStorage.setItem('employee', JSON.stringify(response.data.employee))
                    this.$emitter.emit('loggedIn', true);
                    this.loading = false
                    this.$router.push('/home');
                })
                .catch(error => {
                    if (error.response.data.error) {
                        this.errors.push(error.response.data.error)
                    } else {
                        this.errors.push({message: [error.response.data.message]})
                    }
                    this.loading = false
                })
        },

    },


}
</script>

<style lang="scss">

#loginForm {

}

</style>
