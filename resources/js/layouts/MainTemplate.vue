<template>
    <q-layout view="hHh lpR fFf">

        <q-header elevated class="bg-white text-dark" height-hint="98">
            <q-toolbar>
                <q-btn dense flat round icon="menu" @click="toggleLeftDrawer"/>
                <q-toolbar-title>
                    <router-link to="/home">
                        <div class="logo flex items-center">
                            <q-avatar>
                                <img src="../../img/logo_zd.jpg">
                            </q-avatar>
                            <span class="logo-title q-ml-sm">Жасыл Даму</span>
                        </div>
                    </router-link>
                </q-toolbar-title>

                <div id="main_links" class="flex flex-center">
                    <div class="flex q-pa-xs q-gutter-x-sm">
                        <div v-for="(item, i) in links" :key="i">
                            <a :href="item.link" target="_blank">
                                <q-btn class="bg-success text-white text-overline q-mb-xs" size="12px">{{
                                        item.name
                                    }}
                                </q-btn>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="q-pr-lg">
                    <div id="bookingBtn" class="flex items-center no-wrap" @click="bookingConference()">
                        <q-icon left name="alarm" size="xs"/>
                        <div class="title">Бронь конференц зала</div>
                        <div class="time" v-if="conference.time">{{ conference.time }}</div>
                    </div>
                </div>

                <div class="row items-center">
                    <a href="#" class="flex column items-end">
                        <span>Шалдыбаев Ержан Дауылулы</span>
                        <span class="text-caption">(Программист-Разработчик)</span>
                    </a>
                    <q-btn dense flat round color="secondary">
                        <q-avatar size="24px">
                            <img :src="employee.photo" v-if="employee.photo">
                             <q-icon size="sm" name="account_circle" v-else/>
                        </q-avatar>
                    </q-btn>

                    <q-menu
                        transition-show="flip-right"
                        transition-hide="flip-left"
                        anchor="center middle"
                        self="center middle"
                        fit
                    >
                        <q-list style="min-width: 100px">
                            <q-item clickable>
                                <q-item-section>
                                    <router-link to="/profile">Профиль</router-link>
                                </q-item-section>
                                <q-icon size="1.4rem" name="account_circle"/>
                            </q-item>
                            <q-separator/>
                            <q-item clickable @click="logout()">
                                <q-item-section>Выйти</q-item-section>
                                <q-icon size="1.4rem" name="logout"/>
                            </q-item>
                        </q-list>
                    </q-menu>

                </div>

                <div class="row items-center">
                    <q-btn dense flat round color="secondary" icon="event"/>
                </div>

                <q-btn dense flat round icon="menu" @click="toggleRightDrawer"/>


            </q-toolbar>


        </q-header>

        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered width="260">
            <main-menu/>
        </q-drawer>

        <q-drawer show-if-above v-model="rightDrawerOpen" side="right" bordered>
            <q-scroll-area style="height: calc(100vh - 100px);">
                <weekends-day/>
                <quotes-day />
                <birthday-month/>
            </q-scroll-area>
        </q-drawer>

        <q-page-container>
            <page-template :title="$route.name"/>
        </q-page-container>

        <q-footer class="bg-grey-10 text-white" id="main_footer">
            <q-toolbar>
                <q-toolbar-title class="flex justify-between">
                    <div class="text-overline">АО «Жасыл Даму»</div>
                    <div class="text-overline">Департамент информационных технологий</div>
                    <div class="text-overline">2022</div>
                </q-toolbar-title>
            </q-toolbar>
        </q-footer>

        <booking-conference />
    </q-layout>
</template>

<script>
import {ref} from 'vue'

import PageTemplate from "./PageTemplate.vue";
import MainMenu from "../components/menu/MainMenu.vue";
import WeekendsDay from "../components/WeekendsDay.vue";
import BirthdayMonth from "../components/BirthdayMonth.vue"
import QuotesDay from "../components/QuotesDay.vue"

import BookingConference from "@/components/booking/BookingConference.vue";
import Authorization from "@/utils/Authorization";
import Api from "../utils/api";

const links = [
    {
        name: 'МЭГПР РК',
        link: 'https://www.gov.kz/memleket/entities/ecogeo?lang=kk'
    },
    {
        name: 'recycle.kz',
        link: 'https://recycle.kz/'
    },
    {
        name: 'e-Otinish.kz',
        link: 'https://eotinish.gov.kz/'
    },
    {
        name: 'Documentolog',
        link: 'https://jd.documentolog.kz/'
    },
    {
        name: 'Mail.recycle.kz',
        link: 'https://mail.recycle.kz/'
    },
    {
        name: 'e-gov.kz',
        link: 'https://egov.kz/cms/ru'
    },
    {
        name: 'Госзакупки',
        link: 'https://www.goszakup.gov.kz/'
    },
    {
        name: 'ИПС «Әділет»',
        link: 'https://adilet.zan.kz/'
    },
]

export default {
    components: {
        BirthdayMonth,
        WeekendsDay,
        PageTemplate,
        MainMenu,
        BookingConference,
        QuotesDay
    },
    setup() {
        const leftDrawerOpen = ref(false)
        const rightDrawerOpen = ref(false)

        return {
            links,
            leftDrawerOpen,
            toggleLeftDrawer() {
                leftDrawerOpen.value = !leftDrawerOpen.value
            },

            rightDrawerOpen,
            toggleRightDrawer() {
                rightDrawerOpen.value = !rightDrawerOpen.value
            }
        }
    },

    data() {
        return {
            bookingConferenceDialog: false,
            employee: JSON.parse(localStorage.getItem('employee')),
            user: {},
            conference: {
                time: ''
            }
        }
    },

    methods: {
        logout() {
            Authorization.logout()
        },

        bookingConference() {
            this.bookingConferenceDialog = true
            this.$emitter.emit('bookingConferenceShow', true)
        },

        getLastTodayBooking() {
            Api.post('booking/conference/today/last').then(res => {
                if (res.data.time_start) {
                    this.conference = {
                        time: res.data.time_start
                    }
                }
            })
        },

    },

    created() {

        this.getLastTodayBooking();

        let self = this
        this.$emitter.on('contentLoaded', function (value) {
            self.isLoading = value
        });

        Authorization.user().then(response => {
            this.user = response
        })

        this.$emitter.on('loggedOut', function () {
            self.$router.push('/login')
        });

        // this.sockets.subscribe("application_channel_send", function (message) {
        //     let params1 = {
        //         group: 'support',
        //         title: 'Заявка',
        //         text: 'Вам поступила новая заявка <br><br> <a style="color:#fff!important" href=\'/application\'>Заявки</a>',
        //         type: 'primary',
        //         duration: -1,
        //         ignoreDuplicates: true,
        //     }

        //     let params2 = {
        //         group: 'support',
        //         title: 'Заявка',
        //         text: 'Ваша заявка исполнена!',
        //         type: 'success',
        //         duration: -1,
        //         ignoreDuplicates: true,
        //     }

        //     if (self.user.role.name === 'support' && message.type === 1 || self.user.role.name === 'admin' && message.status === 'new') {
        //         self.$notify(params1)
        //     } else if (self.user.role.name === 'support' && message.type === 2 && message.status === 'new') {
        //         self.$notify(params1)
        //     }

        //     if (message.status === 'success' && message.data.employee_id === this.employee.id) {
        //         self.$notify(params2)
        //     }
        // });


    },

    mounted() {
        // this.emitter.on('bookingConferenceStoreEvent', () => {
        //     this.getLastTodayBooking()
        // })
        // this.sockets.subscribe("main_data_channel_update", () => {
        //     this.getLastTodayBooking()
        // })
    }

}
</script>

<style lang="scss" scoped>

.line {
    &:after {
        content: '';
        display: block;
        height: 100%;
        width: 1px;
        background: #fff;
        position: absolute;
        right: 55px;
        top: 0;
    }
}

</style>
