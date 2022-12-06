<template>
    <q-select
        filled
        v-model="item"
        :options="items"
        option-value="id"
        option-label="name"
        label="Департамент"
    />
</template>

<script>

import {getDepartments} from "@/services/employee";
import { ref } from "vue";

export default {

    setup() {
        const item = ref("");
        return { item };
    },

    data() {
        return {
            items: [],
        }
    },

    methods: {
        getList() {
            getDepartments().then((response) => {
                this.items = response
            });
        }
    },

    created() {
        this.getList()
    }
}
</script>

