<template>
    <q-select
        filled
        v-model="selected_employee"
        :options="employees"
        option-value="id"
        :option-label="(item) => item.lastname + ' ' + item.firstname + ' ' + item.middlename"
        label="Сотрудник"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<script>

import {getEmployeeNames} from "@/services/employee";
import { ref } from "vue";

export default {

    setup() {
        const selected_employee = ref("");
        return { selected_employee };
    },

    props: ['employee'],

    data() {
        return {
            employees: [],
        }
    },

    methods: {
        getEmployee() {
            getEmployeeNames().then((response) => {
                this.employees = response
            });
        }
    },

    created() {
        this.getEmployee()
    }
}
</script>

