<template>
    <div>
        <div class="birthday__block" v-if="employees.length > 0">
            <div class="birthday__background"></div>
            <q-separator class="q-my-sm"/>
            <span class="pa-4">Именинники месяца</span>
            <q-separator class="q-my-sm"/>
            <div class="employee" v-for="item in employees" :key="item.id" style="position: relative;z-index: 10">
            <span class="date"> <b>{{ $moment.unix(item.birthdate).format('D MMMM') }} </b></span>
                <a :href="'/employee/'+item.id+'/show'" target="_blank">
                <div class="fullname">{{ item.fullname }}</div>
                <div class="text">{{ item.department.name }} ({{ item.profession.name }})</div>
            </a>

            <br>

            </div>
        </div>
    </div>
    <div class="text-center">
        <q-circular-progress
            indeterminate
            rounded
            size="50px"
            color="red"
            class="q-ma-md"
            v-if="isLoad"
        />
    </div>
</template>

<script>

import Api from "../utils/api";

export default {
    data() {
        return {
            employees: [],
            isLoad: false
        }
    },

    methods: {
        getBirthdayMonth() {
            this.isLoad = true
            Api.post('employee/birthday/month').then(res => {
                this.employees = res.data.data
            }).finally(() => {
                this.isLoad = false
            })
        }
    },

    mounted() {
        this.getBirthdayMonth()
    }
}
</script>

<style scoped lang="scss">
.employee {
    .fullname {
        color: #25004f;
        font-family: 'Source Code Pro', monospace !important;
    }
    .text{
        font-size: 12px;
        font-style: italic;
    }

    .date {
        color: #ec8d8d;
    }

    span {
        border-radius: 5px;
        font-size: 14px;
    }
}

.birthday__background {
    background-image: url('@/../img/birthday.jfif');
    background-color: #1a202c;
    background-size: contain;
    background-repeat: repeat;
    opacity: 0.1;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
}

.birthday__block{
    position:relative;
    padding: 0 10px;
}

</style>
