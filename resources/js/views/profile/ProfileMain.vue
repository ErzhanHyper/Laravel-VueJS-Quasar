<template>
    <div class="q-pa-md">
        <div class="q-gutter-y-md">

            <div class="q-pa-md">
                <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">

                    <q-tabs v-model="tab" shrink stretch>
                        <q-tab name="tab1" >Общая информация</q-tab>
                        <q-tab name="tab2">Контакты</q-tab>
                        <q-tab name="tab3">Календарь</q-tab>
                        <q-tab name="tab4">Авторизация</q-tab>
                    </q-tabs>

                    <q-space/>

                    <q-btn flat label="Профиль"/>

                </q-toolbar>
            </div>

            <q-tab-panels v-model="tab" animated>

                <q-tab-panel name="tab1">
                    <profile-info :employeeData="employeeData"/>
                </q-tab-panel>

                <q-tab-panel name="tab2">
                    <profile-contact :employeeData="employeeData"/>
                </q-tab-panel>

                <q-tab-panel name="tab4">
                    <profile-auth :employeeData="employeeData"/>
                </q-tab-panel>

                <!--        <q-tab :key="2" value="tab-2">-->
                <!--          <profile-contact :employeeData="employeeData"/>-->
                <!--        </q-tab>-->

                <!--        <q-tab :key="3" value="tab-3">-->
                <!--          <profile-calendar :employeeData="employeeData"/>-->
                <!--        </q-tab>-->

                <!--        <q-tab :key="4" value="tab-4">-->
                <!--          <profile-auth :employeeData="employeeData"/>-->
                <!--        </q-tab>-->

                </q-tab-panels>

        </div>
    </div>
</template>


<script>

import ProfileInfo from "./ProfileInfo.vue"
import ProfileContact from './ProfileContact.vue'
import ProfileAuth from './ProfileAuth.vue'
// import ProfileCalendar from './ProfileCalendar'

export default {

    components: {
        ProfileInfo,
        ProfileContact,
        ProfileAuth,
        // ProfileCalendar,
    },

    data() {
        return {
            tab: 'tab1',
            employeeData: {}
        }
    },


    created() {
        this.employeeData = JSON.parse(localStorage.getItem('employee'))
    }

}

</script>

<style lang="scss">

</style>
