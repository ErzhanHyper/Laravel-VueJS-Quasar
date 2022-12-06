<template>

    <div class="list-actions text-right">
        <div class="q-pa-md q-gutter-sm">
            <q-btn round color="primary" icon="add"/>
        </div>
    </div>

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left">Фото</th>
            <th class="text-left">ФИО</th>
            <th class="text-left">Должность</th>
            <th class="text-left">Структурное подразделение</th>
            <th class="text-left">Внутренний номер</th>
            <th class="text-left">№ Кабинета</th>
            <th class="text-left">Статус</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in items" :key="index">
            <td>
                <router-link :to="'/employee/'+item.id +'/show'">
                    <q-avatar size="80px">
                        <img :src="item.photo" v-if="item.photo">
                        <q-icon size="lg" name="account_circle" v-else/>
                    </q-avatar>
                </router-link>
            </td>
            <td>
                <router-link :to="'/employee/'+item.id +'/show'">
                   {{ item.fullname }}
                </router-link>
            </td>
            <td>{{ (item.profession) ? item.profession.name : '-' }}</td>
            <td>{{ (item.department) ? item.department.name : '-' }}</td>
            <td>
                <q-icon name="phone" v-if="item.extension" />
                {{ (item.extension) ? item.extension : '-' }}
            </td>
            <td>{{ (item.cabinet) ? item.cabinet : '-' }}</td>
            <td> {{ (item.status) ? item.status.name : '-' }}</td>
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

</template>

<script>

import FileDownload from "js-file-download";
import Api from "@/utils/api";
import {getEmployeeList} from "@/services/employee";

export default {
    components: {},

    data() {
        return {
            items: [],
            showFilter: false,
            show: false,
            toggleExport: false,
            page: 1,
            totalPage: 1,
            params: {}
        }
    },

    methods: {

        getList() {
            this.items = [];
            this.params.page = this.page
            getEmployeeList(this.params).then((response) => {
                this.items = response.data
                this.totalPage = response.meta.last_page
            }).catch((reject) => {
            }).finally(() => {
            });
        },

        downloadExcel() {
            Api.get('export/employee', {responseType: 'arraybuffer'}).then(response => {
                FileDownload(response.data, 'Сотрудники.xlsx')
            })
        },


    },

    created() {
        this.getList()

    }
}
</script>


<style lang="scss">


</style>
