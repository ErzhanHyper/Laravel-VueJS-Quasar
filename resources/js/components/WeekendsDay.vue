<template>
    <div>

        <div class="event">
            <span>{{ $moment(dates[0].date).format('D MMMM') }}</span>
            <br>
            <div> {{ dates[0].title }}</div>
        </div>

    </div>
</template>

<script>
import { ref } from 'vue';
export default {

    data: () => ({
            dates: [
                {
                    date: '01-01',
                    title: 'Новый год'
                },
                {
                    date: '01-02',
                    title: 'Новый год'
                },
                {
                    date: '01-07',
                    title: 'Православное Рождество'
                },
                {
                    date: '03-08',
                    title: 'Международный женский день'
                },
                {
                    date: '03-21',
                    title: 'Наурыз мейрамы'
                },
                {
                    date: '03-22',
                    title: 'Наурыз мейрамы'
                },
                {
                    date: '03-23',
                    title: 'Наурыз мейрамы'
                },
                {
                    date: '05-01',
                    title: 'Праздник единства народа Казахстана'
                },
                {
                    date: '05-07',
                    title: 'День защитника Отечества'
                },
                {
                    date: '05-09',
                    title: 'День Победы'
                },
                {
                    date: '06-06',
                    title: 'День столицы'
                },
                {
                    date: '08-30',
                    title: 'День Конституции Республики Казахстан'
                },
                {
                    date: '10-25',
                    title: 'День Республики Казахстана'
                },
                {
                    date: '12-16',
                    title: 'День независимости Казахстана'
                }
            ],
            menu: false,
        }
    ),

    methods: {
        getWeekend() {
            let now = this.$moment().format('MM-DD');
            this.dates = this.dates.filter(function (date) {
                return date.date >= now
            });
        }
    }
    ,

    created() {
        this.getWeekend()
    }
}
</script>

<style scoped lang="scss">

.event {
    margin: 20px 10px;
    border-radius: 8px;
    width: 200px;

    span {
        padding: 4px;
        background: #ec8d8d;
        border-radius: 8px;
        color: #fff;
    }

    div {
        margin-top: 8px;
        letter-spacing: 1.1px;
    }
}
</style>
