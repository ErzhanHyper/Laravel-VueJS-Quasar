<template>
        <q-card bordered flat class="q-mb-lg fullwidth-card">
            <q-card-section>
                <ProfileAvatar :getPhoto='getPhoto' :setPhoto="setPhoto"></ProfileAvatar>
            </q-card-section>

            <q-card-section>

                <div class="q-ma-md q-gutter-sm row ">
                    <q-input label="Фамилия" class="col" v-model="employee.lastname" />
                    <q-input label="Имя" class="col" v-model="employee.firstname"/>
                    <q-input label="Отчества" class="col" v-model="employee.middlename"/>

                </div>

                <div class="q-ma-md q-gutter-sm row ">
                    <q-select
                        :options="statuses"
                        option-value="id"
                        option-label="name"
                        v-model="employee.status"
                        class="col"
                    />

                    <q-select
                        :options="departments"
                        option-value="id"
                        option-label="name"
                        v-model="employee.department"
                        class="col"
                    />

                    <q-select
                        :options="professions"
                        option-value="id"
                        option-label="name"
                        v-model="employee.profession"
                        class="col"
                    />


                </div>

                <div class="q-ma-md q-gutter-sm row ">
                    <q-input v-model="employee.birthdate"  type="date" hint="День рождения" style="width: 200px"/>
                </div>

                <div class="q-ma-md q-gutter-sm row ">
                    <q-input
                        v-model="employee.description"
                        filled
                        type="textarea"
                        label="Описание"
                        class="col"
                    />
                </div>


            </q-card-section>

            <q-card-section>
                <q-btn @click="updateEmployee()" color="primary" icon="save" label="Сохранить"/>
            </q-card-section>
        </q-card>

</template>

<script>
import API from '@/utils/api'
import ProfileAvatar from "@/components/ProfileAvatar.vue";

export default {

    props: ['employeeData'],

    components: {
        ProfileAvatar,
    },

    data() {

        return {
            date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
            dateFormatted: this.formatDate((new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)),
            menu: false,
            user: {},
            departments: [],
            professions: [],
            statuses: [],
            errors: [],
            department_id: null,
            profession_id: null,
            employee: {},
            employee_id: this.employeeData.id,
            employee_status_id: null,
            permission: false,
            photo: '',
            avatarShow: false,
            setPhoto: ''
        }
    },

    computed: {
        computedDateFormatted() {
            return this.formatDate(this.date)
        },
    },

    watch: {
        date() {
            this.dateFormatted = this.formatDate(this.date)
        },
    },


    methods: {

        getPhoto(data) {
            this.photo = data
        },

        formatDate(date) {
            if (!date) return null

            return this.$moment(date).format('YYYY-MM-DD')
        },
        parseDate(date) {
            if (!date) return null

            return this.$moment(date).format('YYYY-MM-DD')
        },

        updateEmployee() {
            this.employee.profession_id = this.profession_id
            this.employee.department_id = this.department_id
            this.employee.employee_status_id = this.employee_status_id
            this.employee.birthdate = this.date
            this.employee.photo = this.photo
            API.post('employee/' + this.employee_id + '/update', this.employee).then(() => {
                this.getEmployee()
            })
        },

        getEmployee() {
            API.post('employee/' + this.employee_id + '/get').then(response => {
                this.employee = response.data.data[0]
                this.department_id = response.data.data[0].department.id
                this.profession_id = response.data.data[0].profession.id
                this.employee_status_id = response.data.data[0].employee_status_id
                this.user = response.data.data[0].user
                this.permission = (response.data.data[0].user.role.name === 'admin')
                this.date = this.$moment.unix(this.employee.birthdate).format('YYYY-MM-DD')
                this.photo = response.data.data[0].photo_base64
                this.avatarShow = true
                this.setPhoto = response.data.data[0].photo_base64
            })
        },

        getDepartments() {
            API.post('department/all').then(response => {
                this.departments = response.data
            })
        },

        getProfessions() {
            API.post('profession/all').then(response => {
                this.professions = response.data
            })
        },

        getStatuses() {
            API.post('employee/status/all').then(response => {
                this.statuses = response.data
            })
        },

    },

    created() {
        this.getEmployee()
        this.getDepartments()
        this.getProfessions()
        this.getStatuses()
    },

    mounted() {
    }

}

</script>

<style scoped>

</style>
