<template>

    <div class="list-actions text-right">
        <router-link to="/docs/trust/create">
            <q-btn color="primary" round icon="add" />
        </router-link>
    </div>

    <main-filter/>

    <q-markup-table flat bordered :wrap-cells="true">
        <thead>
        <tr>
            <th class="text-left">Наименование</th>
            <th class="text-left">Номер доверенности</th>
            <th class="text-left">Инициатор (СП)</th>
            <th class="text-left">Поверенный</th>
            <th class="text-left">Срок действия</th>
            <th class="text-left">Дата выдачи</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(item, index) in items" :key="index">
            <td>
                <router-link :to="'/docs/trust/'+item.id+'/edit'">
                    {{ item.name }}
                    <q-icon name="description"/>
                </router-link>
            </td>
            <td>{{ item.warrant_id }}</td>
            <td>{{ (item.department) ? item.department.name : '-' }}</td>
            <td>{{ item.agent }}</td>
            <td>с {{ $moment.unix(item.date_expiration_start).format('YYYY-MM-DD') }} по
                {{ $moment.unix(item.date_expiration_end).format('YYYY-MM-DD') }}
            </td>
            <td>с {{ $moment.unix(item.date).format('YYYY-MM-DD') }}</td>

        </tr>
        </tbody>
    </q-markup-table>

    <div class="row justify-center q-mt-md">
        <q-pagination
            :min="1"
            :max="size"
            color="grey-8"
            @click="getList()"
            v-model="page"
        />
    </div>

</template>


<script>

import API from '@/utils/api'
import MainFilter from "@/components/MainFilter.vue";
import {getTrustList} from "@/services/trust";

export default {

    components: {
        MainFilter
    },

    data() {
        return {
            items: [],
            search: '',
            page: 1,
            size: 1,
            params: {},
            showFilter: false
        }
    },

    methods: {

        getList() {
            this.params.page = this.page
            getTrustList(this.params).then((response) => {
                this.items = response.data
                this.totalPage = response.meta.last_page
            }).catch((reject) => {

            }).finally(() => {

            });
        },


    },

    mounted() {
        this.getList()
    }
}
</script>
