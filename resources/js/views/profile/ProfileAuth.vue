<template>

    <q-card bordered flat class="q-mb-lg fullwidth-card">
        <q-card-section>

            <div class="q-gutter-sm row">
                <q-input v-model="user.name" type="text" class="col" label="Логин">
                    <template v-slot:prepend>
                        <q-icon name="person"/>
                    </template>
                </q-input>

                <q-btn @click="dialog = true" color="red" label="Изменить пароль" size="md" />
            </div>

        </q-card-section>
    </q-card>

    <q-dialog v-model="dialog">
        <q-card>
            <q-card-section>
                <div class="q-gutter-sm ">
                    <q-input v-model="user.password" type="text" class="col" label="Пароль">
                        <template v-slot:prepend>
                            <q-icon name="door_front"/>
                        </template>
                    </q-input>

                    <q-input v-model="user.password_confirmation" type="text" class="col" label="Подтверждение пароля">
                        <template v-slot:prepend>
                            <q-icon name="door_front"/>
                        </template>
                    </q-input>
                </div>
            </q-card-section>

            <q-card-section>
                <q-btn label="Сохранить" icon="save" color="primary" @click="updatePassword()"/>
            </q-card-section>
        </q-card>
    </q-dialog>

</template>

<script>

import API from '@/utils/api'

export default {

    props: ['employeeData'],

    data() {
        return {
            showPassword: false,
            dialog: false,
            user: {
                name: '',
                password: '',
                password_confirmation: ''
            },
            errors: [],
            errors2: [],
            nameRules: [
                v => !!v || 'объязательно к заполнению',
            ],
            valid: true
        }
    },

    methods: {

        updatePassword() {
            this.errors2 = []
            API.post('user/' + this.user.id + '/update/password', this.user).then(() => {
                this.dialog = false
            }).catch(error => {
                // this.errors2.push(error.response.data.messages)
            })
        },

        updateUser() {
            this.errors = []
            API.post('user/' + this.user.id + '/update', this.user).then(() => {
                this.$notify({
                    group: 'foo',
                    title: 'Профиль',
                    text: 'Успешно обновлен!',
                    type: 'primary'
                })
            }).catch(error => {
                this.errors.push(error.response.data.messages)
            })
        },

        getUser() {
            API.post('user/' + this.employeeData.user_id + '/get').then((res) => {
                this.user = res.data
            })
        }
    },

    created() {
        this.getUser()
    }

}
</script>

<style scoped>

</style>
