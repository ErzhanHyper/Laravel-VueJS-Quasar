<template>
  <div>

    <div class="list-actions text-right" v-if="editAccess">
      <v-btn class="primary" @click="storeEvent">Сохранить</v-btn>
    </div>

    <v-row>

      <v-col class="col-md-6">
        <v-select
            :items="statuses"
            :item-text="item => item.name"
            :item-value="item => item.name"
            v-model="selected.status"
            label="Тип события"
            dense
        >
        </v-select>
      </v-col>

      <v-col class="col-md-6">
        <v-file-input
            multiple
            truncate-length="15"
            label="Прикрепляемые файлы"
            v-model="selected.files"
            counter
            @change="setFiles()"
            dense
        ></v-file-input>
      </v-col>

      <v-col class="col-md-12">
        <v-textarea v-model="selected.text" label="Текст" rows="2" dense/>
      </v-col>


      <v-col class="col-md-12">
        <v-autocomplete
            :items="employees"
            :item-text="item => item.lastname + ' ' + item.firstname + ' ' + ((item.middlename) ? item.middlename : '')"
            :item-value="item => item.id"
            :item-disabled="item => (item.id === selected.employee[0])"
            v-model="selected.employee"
            label="Сотрудники" multiple
            dense
        >
        </v-autocomplete>
      </v-col>

    </v-row>

    <div class="event-calendar">

      <div class="child1">
        <FullCalendar ref="calendar" :options="calendarOptions" v-if="initCalendar" class="main-calendar"/>
      </div>

      <div class="child2">
        <v-time-picker
            ampm-in-title
            format="24hr"
            full-width
            v-model="selected.time"
            class="main-time-picker"
            color="#2c3e50"
        ></v-time-picker>

        <div class="mt-5">
          <div class="mb-2">Выбранные даты:</div>
          <div class="dates_list mb-5" v-if="updated">
            <v-chip v-for="(date, index) in dates" :key="index" class="mr-1 mb-1" @click="removeDate(date)">
              {{ date }}
              <v-icon small>mdi-delete</v-icon>
            </v-chip>
          </div>
        </div>

      </div>

    </div>

    <dialog-event-calendar :data="dateEvent" :dialog="dialog"/>

  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import ruLocale from "@fullcalendar/core/locales/ru";
import API from "@/api";
import authorization from "@/utils/Authorization";
import notification from "@/functions/notification";
import DialogEventCalendar from "@/views/events/DialogEventCalendar";

export default {
  components: {
    FullCalendar,
    DialogEventCalendar
  },
  data() {
    return {
      dateEvent: [],
      initCalendar: true,
      editAccess: authorization.buttonAccess(this.$route.meta.section, 'update'),
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: ruLocale,
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick,
        events: this.handleEventList,
        eventDisplay: 'block',
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          meridiem: false,
        }
      },
      statuses: [],
      employees: [],
      dialog: 0,
      selected: {
        status: 'Совещание',
        employee: [JSON.parse(localStorage.getItem('employee')).id],
        files: [],
        dates: [],
        text: '',
        time: ''
      },
      dates: [],
      updated: true
    }
  },

  methods: {

    handleEventList: function (fetchInfo, successCallback, failureCallback) {
      failureCallback
      let self = this
      API.post('event/get', { id: JSON.parse(localStorage.getItem('employee')).id}).then(response => {
        let events = [];
        if (response.data.length > 0) {
          response.data.forEach(function (evt) {
            events.push({
              title: evt.title,
              date: self.$moment(evt.date).format('YYYY-MM-DD HH:mm'),
            });
          });
          successCallback(events);
        }
      })
    },

    setFiles() {
      this.selected.files = [
        ...this.selected.files
      ]
    },

    getEmployees() {
      API.post('employee/all', {filter: true}).then(response => {
        this.employees = response.data;
      })
    },

    getEventStatuses() {
      API.post('event/status/all').then(res => {
        this.statuses = res.data
      })
    },

    handleDateClick: function (arg) {
      this.updated = false
      this.dates.push(arg.dateStr);
      this.selected.dates = [...new Set(this.dates)]
      this.dates = [...new Set(this.dates)]
      this.updated = true
      this.$notify({
        group: 'foo',
        title: 'Дата',
        text: arg.dateStr
      })
    },

    handleEventClick(arg) {
      let params = {
        date: this.$moment(arg.event.startStr).format('YYYY-MM-DD'),
        title: arg.event.title,
        id: JSON.parse(localStorage.getItem('employee')).id
      }
      API.post('event/employee/get', params).then(response => {
        this.dialog = 1
        this.dateEvent = response.data
        this.dateEvent.datetime = this.$moment(arg.event.startStr).format('YYYY-MM-DD')
      });
    },

    storeEvent() {
      this.initCalendar = false
      const formData = new FormData();
      for (const i of Object.keys(this.selected.files)) {
        formData.append('files[]', this.selected.files[i])
      }
      formData.append('status', this.selected.status)
      formData.append('text', this.selected.text)
      formData.append('employee', this.selected.employee)
      formData.append('dates', this.selected.dates)
      formData.append('time', this.selected.time)

      API.post('event/store', formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(() => {
        this.dates = []
        this.dates_selected = []
        this.selected.files = []
        this.selected.employee = [JSON.parse(localStorage.getItem('employee')).id]
        this.initCalendar = true

        this.$notify({
          group: 'foo',
          title: 'Календарь',
          text: 'Успешно добавлено!',
          type: 'success'
        })
        this.dialog2 = false
        notification.showNotify('success', [])
      }).catch(error => {
        this.initCalendar = true
        notification.showNotify('error', error.response.data)
      })
    },

    removeDate(date) {
      this.selected.dates = this.selected.dates.filter(function (item) {
        return item !== date
      })
      this.dates = this.dates.filter(function (item) {
        return item !== date
      })
      this.$notify({
        group: 'foo',
        title: 'Дата',
        text: date,
        type: 'error'
      })
    }

  },

  mounted() {
    this.getEmployees()
    this.getEventStatuses()

    let self = this
    Event.$on('closeEventDialogEvent', function(){
      self.initCalendar = false
      self.dialog = 0
      setTimeout(function (){
        self.initCalendar = true
      },10)
    });
  }
}
</script>


<style lang="scss" scoped>




.main-calendar {
  max-height: calc(100vh - 160px);
}

.child1 {
  max-width: 62%;
  width: 100%;
}

.child2 {
  max-width: 35%;
  width: 100%;
}

.v-btn.file {
  margin-bottom: 10px;
}

.event-calendar {
  display: flex;
  justify-content: space-between;
}


</style>