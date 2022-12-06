<template>

    <q-dialog v-model="dialog" transition-show="rotate" transition-hide="rotate">

        <q-card style="width: 960px; max-width: 80vw;">

            <q-card-section v-if="errors.length > 0">
                <q-banner inline-actions class="text-white bg-red" v-for="error in errors" :key="errors.indexOf(error)">
                     <span v-for="(item, index) in error" :key="index">
                        {{ item[0] }}
                     <br>
                        </span>
                </q-banner>
            </q-card-section>

            <q-card-section class="row items-center q-pb-none">
                <div class="text-h6">Бронирование конференц зала</div>
                <q-space/>
                <q-btn icon="close" flat round dense v-close-popup/>
            </q-card-section>

            <q-card-section style="max-height: 65vh" class="scroll ">
                <div class="q-py-md flex items-center justify-between">
                    <div class="flex column">
                        <q-btn color="primary" flat>Дата</q-btn>
                        <q-date v-model="selected.date" color="primary" label="fd"/>
                    </div>
                    <div class="flex column">
                        <q-btn color="primary" flat>Начало</q-btn>
                        <q-time
                            v-model="selected.start"
                            format24h
                            color="primary"
                        />
                    </div>
                    <div class="flex column">
                        <q-btn color="primary" flat>Конец</q-btn>
                        <q-time
                            v-model="selected.end"
                            format24h
                            color="primary"
                        />
                    </div>
                </div>

                <div class="q-py-md flex items-center justify-between">
                    <q-select v-model="selected.room" :options="rooms" label="Место проведение" style="width: 290px"/>
                    <q-select
                        v-model="department"
                        use-input
                        input-debounce="0"
                        label="Структурное подразделение"
                        :options="departments"
                        @filter="filterFn"
                        option-value="id"
                        option-label="name"
                        style="width: calc(100% - 320px) "
                        :rules="rules"
                    >
                        <template v-slot:no-option>
                            <q-item>
                                <q-item-section class="text-grey">
                                    Нет результатов
                                </q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </div>

                <div class="q-py-md" v-if="bookings.length > 0">
                    <span>Забронировано</span>
                    <q-banner dense class="bg-grey-3 q-mb-md" v-for="(item, i) in bookings" :key="i">
                        <template v-slot:avatar>
                            <q-icon name="schedule" color="primary"/>
                        </template>
                        {{ $moment.unix(item.date).format('YYYY-MM-DD') }} | с {{ item.time_start }} по {{
                            item.time_end
                        }}
                        | {{ item.room }}
                        <q-separator class="q-my-sm"/>
                        {{ (item.employee) ? item.employee.lastname + ' ' + item.employee.firstname : '-' }} | <span
                        v-if="item.department">{{ item.department.name }}</span>
                        <q-btn round color="red" v-if="editAccess || item.employee.id === employee.id" size="sm" icon="delete" style="float:right;"
                               @click="remove(item.id)">
                        </q-btn>
                    </q-banner>
                </div>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn label="Забронировать" color="primary" icon="save" @click="store()"/>
            </q-card-actions>

        </q-card>
    </q-dialog>

</template>

<script>
import Api from "@/utils/api";
import authorization from "@/utils/Authorization";

export default {
    components: {},
    data() {
        return {
            department: null,
            selected: {
                employee_id: null,
                start: null,
                end: null,
                date: null,
                room: 'Конференц-зал (6-этаж)',
            },
            employee: JSON.parse(localStorage.getItem('employee')),
            editAccess: false,
            bookings: [],
            errors: [],
            departments: [],
            loading: false,
            dialog: false,
            rooms: [
                'Конференц-зал (6-этаж)'
            ],
        }
    },

    methods: {

        handleDateClick() {

        },

        getTodayBooking() {
            Api.post('booking/conference/today').then(res => {
                this.bookings = res.data
            })
        },

        store(){
            this.selected.department_id = (this.department) ? this.department.id : this.department
            this.loading = true
            this.errors = []
            Api.post('booking/conference/store', this.selected).then(() => {
                this.getTodayBooking()
                this.$emitter.emit('bookingConferenceStoreEvent')
                this.loading = false
                this.dialog = false
            }).catch((err) => {
                this.errors.push(err.response.data)
                this.loading = false
            })
        },

        remove(id) {
            Api.post('booking/' + id + '/conference/delete').then(() => {
                this.getTodayBooking()
                this.$emitter.emit('bookingConferenceStoreEvent')
            })
        },

        getDepartments() {
            Api.post('department/all').then(res => {
                this.departments = res.data
            })
        }
    },

    created() {
        this.getTodayBooking()
        this.getDepartments()
        this.editAccess = authorization.buttonAccess('calendar', 'update')
    },

    mounted() {
        let self = this
        this.$emitter.on('bookingConferenceShow', (value) => {
            this.dialog = value
        })
    }

}
</script>

<style scoped lang="scss">
.time {
    padding: 5px 10px;
    background: #393389;
    color: #fff;
    border-radius: 4px;
    margin-right: 10px;

    .time_value {
        font-size: 18px;
    }
}

.booking {
    margin-bottom: 10px;
}
</style>
