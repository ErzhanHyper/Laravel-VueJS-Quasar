<template>
    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add" @click="dialogAdd = true"/>
        </div>
    </div>

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left">-</th>
            <th class="text-left">Текст</th>
            <th class="text-left">Сотрудник</th>
            <th class="text-left">Дата</th>
            <th class="text-left">Статус</th>
            <th class="text-left">Категория</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in items" :key="index">
            <td>
                <q-btn round size="sm" color="primary" @click="showItemDialog(item.id)">
                    <q-icon name="edit"/>
                </q-btn>
            </td>
            <td> {{ item.text }}</td>
            <td>{{ (item.employee) ? item.employee.fullname : '' }}</td>
            <td>{{ $moment(item.date).format('YYYY-MM-DD HH:mm:ss') }}</td>
            <td>
                <q-chip text-color="white" :color="setType(item)">{{ item.status.name }}</q-chip>
            </td>
            <td>{{ item.category.name }}</td>
        </tr>
        </tbody>
    </q-markup-table>

    <div class="row justify-center q-mt-md">
        <q-pagination
            :min="1"
            :max="totalPage"
            color="grey-8"
            @click="getList()"
            v-model="page"
        />
    </div>

    <q-dialog v-model="dialogAdd">
        <application-form />
    </q-dialog>

    <q-dialog v-model="dialogShow">
        <application-detail :id="item_id" />
    </q-dialog>

</template>

<script>

import {getApplicationList} from "@/services/application";
import ApplicationForm from "@/views/application/ApplicationForm.vue";
import ApplicationDetail from "@/js/views/application/ApplicationDetail";

export default {
    components: {ApplicationDetail, ApplicationForm},
    data() {
        return {
            items: [],

            dialogAdd: false,
            dialogShow: false,

            page: 1,
            totalPage: 1,
            item_id: null,

            params: {},
            user: JSON.parse(localStorage.getItem('auth-user')),
        }
    },

    methods: {

        showItemDialog(id) {
            this.item_id = id
            this.dialogShow = true
        },

        getList() {
            this.items = [];
            this.params.page = this.page
            getApplicationList(this.params).then((response) => {
                this.items = response.data
                this.totalPage = response.meta.last_page
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        setType(item) {
            let status = ''
            if (item.status.id === 2) {
                status = 'primary'
            } else if (item.status.id === 3) {
                status = 'success'
            } else {
                status = 'red'
            }
            return status
        }
    },

    created() {
        this.getList()
    },

    mounted() {
        this.$emitter.on('application_dialog', () => {
            this.dialogAdd = false
            this.getList()
        })
    }
}
</script>


<style lang="scss" scoped>


</style>
