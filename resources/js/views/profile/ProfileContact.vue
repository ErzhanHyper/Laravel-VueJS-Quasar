<template>

    <q-card bordered flat class="q-mb-lg fullwidth-card">
        <q-card-section>
            <div class="q-ma-md q-gutter-sm row ">
                <q-input
                    v-model="employee.phone"
                    label="Моб. телефон"
                    mask="(###) ### - ####"
                    unmasked-value
                    hint="Шаблон: (###) ### - ####"
                    class="col"
                />

                <q-input v-model="employee.email" type="email" class="col">
                    <template v-slot:prepend>
                        <q-icon name="mail" />
                    </template>
                </q-input>

                <q-input v-model="employee.extension" type="text" class="col" >
                    <template v-slot:prepend>
                        <q-icon name="call_end" />
                    </template>
                </q-input>

                <q-input v-model="employee.cabinet" type="text" class="col" >
                    <template v-slot:prepend>
                        <q-icon name="door_front" />
                    </template>
                </q-input>

            </div>
        </q-card-section>

        <q-card-section>
            <q-btn @click="updateEmployee()" color="primary" icon="save" label="Сохранить"/>
        </q-card-section>
    </q-card>

</template>

<script>

import API from "@/utils/api";

export default {
    props: ['employeeData'],

    components: {},


    data() {
        return {
            employee: {},
            employee_id: this.employeeData.id,
            translations: {
                phoneNumberLabel: 'Моб. телефон',
            }
        }
    },

    methods: {
        updateEmployee() {
            API.post('employee/' + this.employee_id + '/update', this.employee).then(() => {
                this.getEmployee()
                this.$notify({
                    group: 'foo',
                    title: 'Профиль',
                    text: 'Успешно обновлено!',
                    type: 'action'
                })
            })
        },

        getEmployee() {
            API.post('employee/' + this.employee_id + '/get').then(response => {
                this.employee = {
                    phone: response.data.data[0].phone,
                    email: response.data.data[0].email,
                    ext: response.data.data[0].extension,
                    cabinet: response.data.data[0].cabinet,
                }
            })
        },
    },

    created() {
        this.getEmployee()
    }

}

</script>

<style>

.vue-phone-number-input input {
    border: 0;
    border-bottom: 1px solid #878787 !important;
    border-radius: 0 !important;
}

</style>
